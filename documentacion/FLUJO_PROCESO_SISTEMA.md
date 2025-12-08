# Flujo de Proceso del Sistema de Ganado

## Arquitectura General: Modelo de Threads Concurrentes

El sistema está diseñado con una arquitectura de **3 threads principales** que trabajan en paralelo para manejar las operaciones de forma eficiente y segura:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SISTEMA DE GESTIÓN DE GANADO                             │
│                                                                              │
│  Thread 1: Interfaz de Usuario    │  Thread 2: Procesador de Comandos  │   │
│  Thread 3: Ejecutor de Tareas                                           │   │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## Thread 1: Capa de Presentación (Interfaz de Usuario)

### Estados
- **Idle**: Esperando interacción del usuario
- **User Action**: Procesando acciones del usuario (clic, envío de formulario, filtros)
- **Post Command**: Enviando comando al queue

### Flujo
```
┌─────────────────────┐
│     Usuario         │
│   (Cliente Web)     │
└──────────┬──────────┘
           │ Interacción (clic, formulario, etc.)
           ▼
┌─────────────────────┐
│      Idle           │ ◄─────────────────┐
└──────────┬──────────┘                   │
           │ Acción detectada             │
           ▼                               │
┌─────────────────────┐                   │
│   User Action       │                   │
│ - Validar datos     │                   │
│ - Preparar request  │                   │
│ - Sanitizar input   │                   │
└──────────┬──────────┘                   │
           │                               │
           ▼                               │
┌─────────────────────┐                   │
│   Post Command      │                   │
│ - Enviar al Queue   │                   │
│ - Mostrar feedback  │                   │
└──────────┬──────────┘                   │
           │                               │
           └───────────────────────────────┘
```

### Acciones Disponibles

#### 1. **Gestión de Ganado (Vendedores)**
- ✓ Publicar nuevo animal
- ✓ Editar animal existente
- ✓ Eliminar animal
- ✓ Ver mis animales
- ✓ Subir imágenes
- ✓ Geolocalizar propiedad

#### 2. **Compras (Compradores)**
- ✓ Ver catálogo
- ✓ Filtrar por raza, edad, precio, ubicación
- ✓ Ver detalles del animal
- ✓ Iniciar compra
- ✓ Ver historial de compras

#### 3. **Administración (Admins)**
- ✓ Gestionar usuarios
- ✓ Asignar roles
- ✓ Ver reportes
- ✓ Auditoría de acciones
- ✓ Gestionar contenido

#### 4. **Sistema General**
- ✓ Autenticación/Login
- ✓ Registro de usuarios
- ✓ Gestión de perfil
- ✓ Contacto
- ✓ Recuperación de contraseña

---

## Thread 2: Capa de Procesamiento (Command Queue)

### Estados
- **Idle**: Sin comandos en la cola
- **Check for New Commands**: Verificando si hay nuevos comandos
- **Command Queue**: Almacenando comandos pendientes
- **Critical Section**: Protegiendo acceso a base de datos

### Flujo
```
┌─────────────────────────────────┐
│  Idle                           │ ◄─────────────────┐
│ (Sin comandos)                  │                   │
└──────────┬──────────────────────┘                   │
           │                                           │
           ▼                                           │
┌─────────────────────────────────┐                   │
│  Check for New Commands         │                   │
│ - Verificar Queue               │                   │
│ - Validar seguridad CSRF        │                   │
│ - Validar autenticación         │                   │
│ - Revisar permisos              │                   │
└──────────┬──────────────────────┘                   │
           │                                           │
      ┌────┴─────────────┐                           │
      │ ¿Queue vacío?    │                           │
      └────┬────────┬────┘                           │
           │ NO     │ SÍ                              │
           ▼        └────────────────────────────────┘
┌─────────────────────────────────┐
│  Command Queue                  │
│ - Almacenar comando             │
│ - Asignar ID único              │
│ - Guardar timestamp             │
│ - Registrar usuario             │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│  Dispatch Command Worker Thread │
│ - Extraer del queue             │
│ - Preparar contexto             │
│ - Ejecutar validaciones         │
│ - Enviar a Thread 3             │
└──────────┬──────────────────────┘
           │
           └───────────────────────────────────────────────▶
```

### Validaciones en este Thread

1. **Seguridad CSRF**
   - Verificar token CSRF válido
   - Validar origen de la solicitud
   - Prevenir ataques de falsificación

2. **Autenticación**
   - Verificar sesión activa
   - Validar token de sesión
   - Revisar tiempo de expiración

3. **Autorización (RBAC)**
   - Verificar rol del usuario
   - Validar permisos específicos
   - Auditar intento de acceso

4. **Validación de Datos**
   - Verificar tipos de datos
   - Validar formato de campos
   - Revisar límites permitidos

---

## Thread 3: Capa de Ejecución (Ejecutor de Tareas)

### Flujo
```
┌─────────────────────────────────┐
│  Process Command                │
│ - Recibir comando               │
│ - Preparar transacción          │
│ - Validaciones finales          │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│  Critical Section               │
│ - Lock de base de datos         │
│ - Acceso exclusivo              │
│ - Una operación a la vez        │
└──────────┬──────────────────────┘
           │
      ┌────┴──────────────────────┐
      │ Tipo de Operación         │
      └────┬──────────────────────┘
           │
    ┌──────┴──────────────┬─────────────────┬──────────────┐
    │                     │                 │              │
    ▼                     ▼                 ▼              ▼
┌────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────┐
│   CREATE   │  │    UPDATE    │  │    DELETE    │  │  SELECT  │
│ - INSERT   │  │ - UPDATE SET │  │ - DELETE FROM│  │ - READ   │
│ - Validar  │  │ - Validar    │  │ - Validar    │  │ - Caché  │
│ - Generar  │  │ - Actualizar │  │ - Soft Del   │  │ - Log    │
│   ID       │  │   Audit Log  │  │ - Audit Log  │  │          │
└────────────┘  └──────────────┘  └──────────────┘  └──────────┘
    │                     │                 │              │
    └──────────┬──────────┴─────────────────┴──────────────┘
               │
               ▼
┌─────────────────────────────────┐
│  Unlock & Commit                │
│ - Ejecutar commit               │
│ - Liberar lock                  │
│ - Guardar en log                │
│ - Actualizar caché              │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│  Return to Thread 1             │
│ - Enviar resultado              │
│ - Actualizar vista              │
│ - Mostrar confirmación          │
└─────────────────────────────────┘
```

### Operaciones Ejecutadas

#### 1. **Operaciones CRUD**

**CREATE (Inserción)**
```
usuario_id: 123
accion: crear_ganado
datos:
  nombre: "Vaca Holstein"
  raza: "Holstein"
  edad: 3
  precio: 5000
  imagen: "vaca_001.jpg"
  latitud: 14.634915
  longitud: -90.506882

Ejecución:
1. Validar datos completos
2. Verificar permisos (solo vendedor)
3. Generar ID único para animal
4. Insertar en tabla ganado
5. Registrar en auditoría
6. Actualizar caché
7. Retornar confirmación
```

**UPDATE (Actualización)**
```
usuario_id: 123
accion: actualizar_ganado
ganado_id: 456
datos:
  nombre: "Vaca Holstein Mejorada"
  precio: 5500

Ejecución:
1. Verificar que el ganado existe
2. Verificar que el usuario es propietario
3. Validar nuevos datos
4. Ejecutar UPDATE en BD
5. Registrar cambios en auditoría
6. Invalidar caché
7. Retornar confirmación
```

**DELETE (Eliminación - Soft Delete)**
```
usuario_id: 456
accion: eliminar_ganado
ganado_id: 789

Ejecución:
1. Verificar que ganado existe
2. Verificar que usuario es propietario
3. Marcar como eliminado (soft delete)
4. Guardar timestamp de eliminación
5. Registrar en auditoría
6. Notificar cambio de estado
7. Retornar confirmación
```

**SELECT (Lectura)**
```
accion: listar_ganado
filtros:
  raza: "Angus"
  edad_min: 2
  edad_max: 5
  precio_max: 10000
pagina: 1
por_pagina: 20

Ejecución:
1. Validar filtros
2. Construir query de búsqueda
3. Aplicar índices (optimización)
4. Ejecutar query con paginación
5. Cachear resultados
6. Retornar datos paginados
```

#### 2. **Operaciones Transaccionales Complejas**

**Procesamiento de Venta**
```
Thread 1: Usuario inicia compra
    ↓
Thread 2: Validar stock, permisos, datos
    ↓
Thread 3: CRITICAL SECTION
    ├─ 1. Verificar ganado disponible
    ├─ 2. Crear registro de venta
    ├─ 3. Actualizar estado del animal
    ├─ 4. Crear factura
    ├─ 5. Registrar movimiento contable
    ├─ 6. Registrar auditoría
    └─ 7. COMMIT - Liberar lock
    ↓
Thread 1: Mostrar confirmación + email
```

---

## Diagrama de Flujo Completo Integrado

```
┌────────────────────────────────────────────────────────────────────────────┐
│                         USUARIO FINAL                                      │
└────────────────────────┬─────────────────────────────────────────────────────┘
                         │
                         ▼
        ┌────────────────────────────────────────┐
        │    THREAD 1: PRESENTACIÓN               │
        │                                        │
        │  1. Detectar acción del usuario       │
        │  2. Validar datos (cliente)           │
        │  3. Preparar comando                  │
        │  4. Enviar al queue                   │
        └────────────────────┬───────────────────┘
                             │
                             ▼
        ┌────────────────────────────────────────┐
        │    THREAD 2: PROCESAMIENTO              │
        │                                        │
        │  1. Recibir comando del queue         │
        │  2. Validar CSRF token                │
        │  3. Verificar autenticación           │
        │  4. Revisar permisos (RBAC)           │
        │  5. Validar datos (servidor)          │
        │  6. Verificar negocio                 │
        │  7. Despachar worker thread           │
        └────────────────────┬───────────────────┘
                             │
                             ▼
        ┌────────────────────────────────────────┐
        │    THREAD 3: EJECUCIÓN                  │
        │                                        │
        │  SECCIÓN CRÍTICA (con lock):          │
        │  1. Comenzar transacción              │
        │  2. Ejecutar operación BD             │
        │  3. Actualizar estado del sistema     │
        │  4. Registrar en auditoría            │
        │  5. Commit o Rollback                 │
        │  6. Liberar lock                      │
        │  7. Actualizar caché                  │
        └────────────────────┬───────────────────┘
                             │
                             ▼
        ┌────────────────────────────────────────┐
        │    RESULTADO AL USUARIO                 │
        │                                        │
        │  ✓ Confirmación                        │
        │  ✓ Datos actualizados                  │
        │  ✓ Email de notificación               │
        │  ✓ Auditoría registrada                │
        └────────────────────────────────────────┘
```

---

## Mecanismos de Seguridad por Thread

### Thread 1: Seguridad en Cliente
- ✓ Validación de formularios
- ✓ Sanitización de entrada
- ✓ Verificación de tipos
- ✓ Límites de caracteres

### Thread 2: Seguridad en Servidor
- ✓ CSRF Protection (tokens únicos)
- ✓ CORS Policy
- ✓ Rate Limiting
- ✓ IP Whitelist (opcional)
- ✓ User Agent validation

### Thread 3: Seguridad en Base de Datos
- ✓ Prepared Statements (SQL Injection)
- ✓ Transacciones ACID
- ✓ Locks (Concurrencia)
- ✓ Auditoría completa
- ✓ Soft Deletes (recuperación)

---

## Roles y Permisos (RBAC)

### Admin
- Gestionar todos los usuarios
- Asignar roles
- Ver reportes
- Acceder a auditoría
- Eliminar contenido
- Configurar sistema

### Vendedor
- Publicar ganado
- Editar sus animales
- Ver sus ventas
- Exportar reportes propios
- Gestionar vacunaciones

### Comprador
- Ver catálogo
- Filtrar y buscar
- Comprar animales
- Ver historial de compras
- Contactar vendedores

---

## Estados de Datos en el Sistema

```
Ganado (Animal):
├─ Disponible (venta)
├─ Reservado
├─ Vendido
├─ Eliminado (soft delete)
└─ Archivado

Usuario:
├─ Activo
├─ Inactivo
├─ Bloqueado
└─ Eliminado

Venta:
├─ Pendiente
├─ Confirmada
├─ Completada
├─ Cancelada
└─ Rechazada
```

---

## Manejo de Errores

```
┌─ Error en Thread 1
│  └─ Validación cliente falla
│     └─ Mostrar mensaje al usuario
│
├─ Error en Thread 2
│  └─ Validación servidor falla
│     └─ Registrar en log
│     └─ Rechazar operación
│     └─ Retornar error al usuario
│
└─ Error en Thread 3
   └─ Falla en BD o transacción
      └─ ROLLBACK automático
      └─ Liberar lock
      └─ Registrar en auditoría
      └─ Retornar error controlado
      └─ Alertar administrador
```

---

## Resumen de Capas y Responsabilidades

| Layer | Thread | Responsabilidad | Herramientas |
|-------|--------|-----------------|--------------|
| **Presentación** | 1 | UI/UX, Validación cliente, Captura de eventos | HTML, CSS, JavaScript |
| **Aplicación** | 2 | Lógica de negocio, Seguridad, Enrutamiento | PHP (Negocio) |
| **Persistencia** | 3 | Acceso a datos, Transacciones, Auditoría | PHP (Datos), MySQL |

---

## Flujo de Ejemplo: Compra de Animal

```
┌─ Usuario ve catálogo
│  └─ Thread 1 carga lista de animales
│
├─ Usuario filtro por raza "Angus"
│  └─ Thread 1 valida filtro
│  └─ Thread 2 ejecuta búsqueda
│  └─ Thread 3 query con índices
│  └─ Resultado: 15 animales
│
├─ Usuario selecciona animal ID=789
│  └─ Thread 1 solicita detalles
│  └─ Thread 2 verifica permisos de lectura
│  └─ Thread 3 obtiene datos (caché si existe)
│  └─ Resultado: Datos detallados + geolocalización
│
├─ Usuario hace clic en "Comprar"
│  └─ Thread 1 valida carrito
│  └─ Thread 2 verifica autenticación
│     └─ Verifica que no sea vendedor del animal
│     └─ Valida límites de compra
│  └─ Thread 3 inicia transacción (LOCK)
│     ├─ Crear registro de venta
│     ├─ Actualizar estado animal = "vendido"
│     ├─ Crear factura
│     ├─ Registrar auditoría
│     └─ COMMIT (libera LOCK)
│
└─ Resultado: 
   ├─ Email de confirmación al comprador
   ├─ Email de notificación al vendedor
   ├─ Actualizar inventario
   └─ Registrar en reportes
```

---

**Conclusión**: El sistema utiliza un modelo de concurrencia seguro con 3 capas diferenciadas que garantiza integridad, seguridad y escalabilidad.
