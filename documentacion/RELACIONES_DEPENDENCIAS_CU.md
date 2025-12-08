# 🔗 RELACIONES Y DEPENDENCIAS ENTRE CASOS DE USO

## Descripción

Este documento mapea las dependencias y relaciones entre todos los casos de uso del sistema.

---

## 📊 MATRIZ DE RELACIONES

### Notación
- **→ REQUIERE**: Necesita que otro caso de uso sea completado antes
- **← HABILITA**: Permite que otro caso de uso se ejecute
- **INCLUYE**: Otro caso de uso es parte de este
- **EXTIENDE**: Otro caso de uso puede extender este bajo ciertas condiciones

---

## 🔗 RELACIONES DETALLADAS

### AUTENTICACIÓN

```
┌─ Registrar Usuario
│  ├─ INCLUYE: Validar Datos
│  └─ HABILITA: Iniciar Sesión
│
├─ Iniciar Sesión
│  ├─ INCLUYE: Validar Datos
│  ├─ REQUIERE: Registrar Usuario (previo)
│  └─ HABILITA: Todos los demás casos de uso
│
├─ Cerrar Sesión
│  ├─ REQUIERE: Iniciar Sesión (previo)
│  └─ DESHABILITA: Todos los casos de uso autenticados
│
└─ Validar Datos
   ├─ INCLUIDO EN: Registrar Usuario
   └─ INCLUIDO EN: Iniciar Sesión
```

---

### GESTIÓN DE GANADO

```
┌─ Registrar Ganado
│  ├─ REQUIERE: Iniciar Sesión (ser vendedor)
│  ├─ INCLUYE: Validar Datos
│  ├─ HABILITA: Actualizar Ganado
│  ├─ HABILITA: Eliminar Ganado
│  ├─ HABILITA: Ver Detalle Ganado
│  ├─ HABILITA: Registrar Vacunaciones
│  ├─ HABILITA: Actualizar Estado
│  └─ HABILITA: Listar/Buscar Ganado
│
├─ Actualizar Ganado
│  ├─ REQUIERE: Registrar Ganado (previo)
│  ├─ REQUIERE: Ser propietario del ganado
│  ├─ INCLUYE: Validar Datos
│  └─ HABILITA: Ver Detalle Ganado
│
├─ Eliminar Ganado
│  ├─ REQUIERE: Registrar Ganado (previo)
│  ├─ REQUIERE: Ser propietario
│  ├─ REQUIERE: No tener compras pendientes
│  └─ DESHABILITA: Realizar Compra (para este ganado)
│
├─ Listar/Buscar Ganado
│  ├─ REQUIERE: Registrar Ganado (al menos uno)
│  ├─ HABILITA: Ver Detalle Ganado
│  ├─ HABILITA: Realizar Compra
│  └─ EXTIENDE: Explorar Catálogo
│
├─ Ver Detalle Ganado
│  ├─ REQUIERE: Listar/Buscar Ganado (previo)
│  ├─ INCLUYE: Ver Ubicación en Mapa
│  ├─ INCLUYE: Ver Historial de Vacunaciones
│  └─ HABILITA: Realizar Compra
│
├─ Registrar Vacunaciones
│  ├─ REQUIERE: Registrar Ganado (previo)
│  ├─ REQUIERE: Ser propietario
│  ├─ INCLUIDO EN: Ver Detalle Ganado
│  └─ VISIBLE EN: Ver Historial de Vacunaciones
│
└─ Actualizar Estado
   ├─ REQUIERE: Registrar Ganado (previo)
   ├─ REQUIERE: Confirmar Venta (para cambiar a "vendido")
   └─ IMPACTA: Realizar Compra (disponible/no disponible)
```

---

### PROCESO DE COMPRA

```
┌─ Explorar Catálogo
│  ├─ REQUIERE: Iniciar Sesión (ser comprador)
│  ├─ REQUIERE: Registrar Ganado (al menos uno)
│  ├─ EXTIENDE: Listar/Buscar Ganado
│  ├─ HABILITA: Buscar Ganado
│  ├─ HABILITA: Ver Detalles del Animal
│  └─ HABILITA: Realizar Compra
│
├─ Buscar Ganado
│  ├─ REQUIERE: Explorar Catálogo (previo)
│  ├─ HABILITA: Ver Detalles del Animal
│  ├─ HABILITA: Realizar Compra
│  └─ INCLUIDO EN: Listar/Buscar Ganado
│
├─ Ver Detalles del Animal
│  ├─ REQUIERE: Buscar Ganado O Explorar Catálogo (previo)
│  ├─ INCLUYE: Ver Historial de Vacunaciones
│  ├─ INCLUYE: Ver Ubicación en Mapa
│  └─ HABILITA: Realizar Compra
│
├─ Ver Historial de Vacunaciones
│  ├─ REQUIERE: Ver Detalles del Animal (previo)
│  ├─ INCLUIDO EN: Ver Detalles del Animal
│  └─ INFORMA: Decisión de compra
│
├─ Ver Ubicación en Mapa
│  ├─ REQUIERE: Ver Detalles del Animal (previo)
│  ├─ INCLUIDO EN: Ver Detalles del Animal
│  └─ INFORMA: Decisión de compra
│
├─ Realizar Compra
│  ├─ REQUIERE: Ver Detalles del Animal (previo)
│  ├─ REQUIERE: Ganado en estado "disponible"
│  ├─ REQUIERE: No ser propietario del ganado
│  ├─ HABILITA: Recibir Solicitud de Compra (en vendedor)
│  ├─ HABILITA: Ver Mis Compras
│  ├─ HABILITA: Confirmar Venta O Cancelar Venta (en vendedor)
│  └─ CREA: Venta en estado "pendiente"
│
└─ Ver Mis Compras
   ├─ REQUIERE: Realizar Compra (al menos una previo)
   ├─ REQUIERE: Iniciar Sesión
   ├─ MUESTRA: Estado de compras (pendientes/completadas)
   └─ DEPENDE DE: Confirmar Venta (vendedor)
```

---

### PROCESO DE VENTA

```
┌─ Recibir Solicitud de Compra
│  ├─ REQUIERE: Registrar Ganado (previo)
│  ├─ REQUIERE: Realizar Compra (por comprador)
│  ├─ HABILITA: Confirmar Venta
│  ├─ HABILITA: Cancelar Venta
│  ├─ HABILITA: Ver Mis Ventas
│  └─ CREA: Venta en estado "pendiente"
│
├─ Confirmar Venta
│  ├─ REQUIERE: Recibir Solicitud de Compra (previo)
│  ├─ INCLUYE: Calcular Comisión (5%)
│  ├─ HABILITA: Ver Estado de Ventas
│  ├─ HABILITA: Generar Reporte de Comisiones
│  ├─ ACTUALIZA: Estado de Ganado a "vendido"
│  ├─ ACTUALIZA: Estado de Venta a "completada"
│  ├─ DESHABILITA: Realizar Compra (para este ganado)
│  └─ HABILITA: Ver Mis Compras (para comprador)
│
├─ Cancelar Venta
│  ├─ REQUIERE: Recibir Solicitud de Compra (previo)
│  ├─ ACTUALIZA: Estado de Venta a "cancelada"
│  ├─ MANTIENE: Estado de Ganado en "disponible"
│  └─ HABILITA: Realizar Compra (para otro comprador)
│
├─ Ver Mis Ventas
│  ├─ REQUIERE: Recibir Solicitud de Compra (al menos una previo)
│  ├─ REQUIERE: Iniciar Sesión (ser vendedor)
│  ├─ MUESTRA: Todas las transacciones del vendedor
│  ├─ EXTIENDE: Ver Estado de Ventas
│  └─ HABILITA: Generar Reporte de Comisiones
│
├─ Ver Estado de Ventas
│  ├─ REQUIERE: Ver Mis Ventas (previo)
│  ├─ INCLUIDO EN: Ver Mis Ventas
│  ├─ MUESTRA: Estados (pendiente/completada/cancelada)
│  └─ INFORMA: Génesis de ingresos
│
└─ Generar Reporte de Comisiones
   ├─ REQUIERE: Confirmar Venta (al menos una previo)
   ├─ REQUIERE: Iniciar Sesión
   ├─ USA: Cálculo de comisión (5% del precio)
   ├─ DEPENDE DE: Ver Mis Ventas
   └─ INFORMA: Ganancias del vendedor
```

---

### GESTIÓN DE USUARIOS

```
┌─ Ver/Editar Perfil
│  ├─ REQUIERE: Iniciar Sesión (previo)
│  ├─ HABILITA: Cambiar Contraseña
│  ├─ HABILITA: Eliminar Cuenta
│  ├─ PERMITE: Cambiar tipo (comprador ↔ vendedor)
│  └─ ACTUALIZA: Datos personales
│
├─ Cambiar Contraseña
│  ├─ REQUIERE: Ver/Editar Perfil (previo)
│  ├─ REQUIERE: Iniciar Sesión
│  └─ ACTUALIZA: Credenciales de acceso
│
├─ Restablecer Contraseña
│  ├─ REQUIERE: Iniciar Sesión = FALSE
│  ├─ HABILITA: Iniciar Sesión (con nueva contraseña)
│  └─ ALTERNATIVA A: Cambiar Contraseña
│
├─ Eliminar Cuenta
│  ├─ REQUIERE: Ver/Editar Perfil (previo)
│  ├─ REQUIERE: Sin ganado registrado
│  ├─ REQUIERE: Sin ventas pendientes
│  ├─ REQUIERE: Confirmación de contraseña
│  ├─ DESHABILITA: Iniciar Sesión (cuenta eliminada)
│  └─ DESACTIVA: Todos los casos de uso del usuario
│
├─ Listar Usuarios (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ HABILITA: Editar Usuario
│  ├─ HABILITA: Eliminar Usuario
│  ├─ HABILITA: Asignar Roles
│  └─ MUESTRA: Todos los usuarios del sistema
│
├─ Editar Usuario (ADMIN)
│  ├─ REQUIERE: Listar Usuarios (previo)
│  ├─ REQUIERE: Ser administrador
│  ├─ PERMITE: Cambiar datos del usuario
│  ├─ PERMITE: Cambiar tipo de usuario
│  ├─ PERMITE: Activar/desactivar usuario
│  └─ IMPACTA: Permisos del usuario
│
├─ Eliminar Usuario (ADMIN)
│  ├─ REQUIERE: Listar Usuarios (previo)
│  ├─ REQUIERE: Ser administrador
│  ├─ REQUIERE: Usuario sin ganado
│  ├─ REQUIERE: Usuario sin ventas pendientes
│  ├─ DESHABILITA: Iniciar Sesión (para ese usuario)
│  └─ ELIMINA: Todos los datos del usuario
│
└─ Asignar Roles (ADMIN)
   ├─ REQUIERE: Listar Usuarios (previo)
   ├─ REQUIERE: Ser administrador
   ├─ ACTUALIZA: Tipo de usuario (comprador/vendedor/admin)
   ├─ HABILITA: Nuevos casos de uso (según rol)
   └─ DESHABILITA: Casos de uso antiguos
```

---

### REPORTES Y ANÁLISIS

```
┌─ Ver Estadísticas Generales
│  ├─ REQUIERE: Iniciar Sesión
│  ├─ MUESTRA: Datos agregados del sistema
│  ├─ INCLUYE: Total usuarios, animales, ventas, ubicaciones
│  ├─ EXTIENDE: Obtener Estadísticas (backend)
│  └─ GENERA: Gráficos estadísticos
│
├─ Ver Mis Estadísticas
│  ├─ REQUIERE: Iniciar Sesión
│  ├─ REQUIERE: Tener actividad en el sistema
│  ├─ INCLUYE: Ver Gráficos Estadísticos
│  ├─ MUESTRA: Datos personalizados del usuario
│  └─ USA: Datos de Compras O Ventas (según rol)
│
├─ Exportar Datos
│  ├─ REQUIERE: Iniciar Sesión
│  ├─ REQUIERE: Ver Estadísticas (previo)
│  ├─ GENERA: Archivo de datos (CSV/Excel/PDF)
│  └─ BASADO EN: Ver Mis Estadísticas
│
├─ Descargar Reportes PDF (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ USA: TCPDF (librería)
│  ├─ GENERA: Archivo PDF descargable
│  ├─ BASADO EN: Datos del sistema
│  └─ HABILITA: Ver Reportes Parametrizados
│
├─ Listar Reportes Parametrizados (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ MUESTRA: Reportes predefinidos disponibles
│  ├─ HABILITA: Seleccionar parámetros
│  └─ GENERA: Reportes customizados
│
├─ Reportes de Ventas por Usuario (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ REQUIERE: Existan ventas en el sistema
│  ├─ BASADO EN: Ver Mis Ventas (datos de usuarios)
│  ├─ USA: Tabla ventas
│  ├─ GENERA: Reporte PDF
│  └─ MUESTRA: Desglose por vendedor
│
├─ Reporte de Ganado por Estado (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ REQUIERE: Ganado registrado
│  ├─ BASADO EN: Listar/Buscar Ganado
│  ├─ USA: Tabla ganado con filtro estado
│  ├─ GENERA: Reporte PDF
│  └─ MUESTRA: Disponible/Vendido/Reservado
│
└─ Ver Gráficos Estadísticos
   ├─ INCLUIDO EN: Ver Mis Estadísticas
   ├─ BASADO EN: Datos estadísticos procesados
   ├─ MUESTRA: Visualización de datos
   └─ GENERADO POR: Backend (nEstadisticas.php)
```

---

### ADMINISTRACIÓN DEL SISTEMA

```
┌─ Ver Estructura de Tablas (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ MUESTRA: Esquema de base de datos
│  ├─ LISTA: Usuarios, Ganado, Vacunaciones, Ventas
│  ├─ INFORMA: Integridad de datos
│  └─ HABILITA: Realizar Mantenimiento
│
├─ Realizar Mantenimiento (ADMIN)
│  ├─ REQUIERE: Ver Estructura de Tablas (previo)
│  ├─ REQUIERE: Acceso administrativo
│  ├─ OPTIMIZA: Base de datos
│  ├─ LIMPIA: Datos innecesarios
│  └─ IMPACTA: Rendimiento del sistema
│
├─ Gestionar Copias de Seguridad (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ PERMITE: Crear backup
│  ├─ PERMITE: Restaurar backup
│  ├─ CRÍTICO PARA: Recuperación ante desastres
│  └─ PROTEGE: Integridad de datos
│
├─ Gestionar Permisos (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ RELACIONADO CON: Asignar Roles
│  ├─ ACTUALIZA: Control de acceso
│  ├─ DEFINE: Qué casos de uso puede hacer cada usuario
│  └─ HABILITA/DESHABILITA: Funcionalidades por rol
│
├─ Ver Auditoría del Sistema (ADMIN)
│  ├─ REQUIERE: Iniciar Sesión (ser admin)
│  ├─ REQUIERE: Auditoría habilitada
│  ├─ MUESTRA: Log de actividades
│  ├─ REGISTRA: Quién hizo qué y cuándo
│  └─ CRÍTICO PARA: Seguridad y compliance
│
└─ Configurar Parámetros del Sistema (ADMIN)
   ├─ REQUIERE: Iniciar Sesión (ser admin)
   ├─ PERMITE: Ajustar configuración global
   ├─ USA: archivo config/config.php
   ├─ IMPACTA: Comportamiento de todo el sistema
   └─ REQUIERE: Reinicio para aplicar cambios
```

---

## 🔄 DEPENDENCIAS CRÍTICAS

### Cadena Autenticación → Todo
```
Iniciar Sesión
    ↓
[REQUIERE PARA TODOS LOS DEMÁS CASOS DE USO]
    ↓
Gestión de Ganado, Compras, Ventas, Usuarios, Reportes, Administración
```

### Cadena Publicación → Búsqueda → Compra
```
Registrar Ganado
    ↓
Listar/Buscar Ganado
    ↓
Ver Detalles del Animal
    ↓
Realizar Compra
    ↓
Recibir Solicitud de Compra
    ↓
Confirmar Venta
```

### Ciclo de Vida de una Transacción
```
Registrar Ganado → Realizar Compra → Recibir Solicitud → Confirmar Venta
                   (pendiente)         (sistema crea)     (se completa)
```

---

## 🎯 DIAGRAMA DE DEPENDENCIAS SIMPLIFICADO

```
                    ┌─────────────────────┐
                    │  Iniciar Sesión     │
                    └────────────┬────────┘
                                 │
                 ┌───────────────┼───────────────┐
                 │               │               │
        ┌────────▼──────┐  ┌─────▼──────┐  ┌────▼────────┐
        │ Gestión Ganado│  │   Compras   │  │   Ventas    │
        └────────┬──────┘  └─────┬──────┘  └────┬────────┘
                 │               │              │
        ┌────────▼──────┐  ┌─────▼──────┐  ┌────▼────────┐
        │ Registrar     │  │ Explorar   │  │ Recibir     │
        │ Ganado        │  │ Catálogo   │  │ Solicitud   │
        └────────┬──────┘  └─────┬──────┘  └────┬────────┘
                 │               │              │
        ┌────────▼──────┐  ┌─────▼──────┐  ┌────▼────────┐
        │ Registrar     │  │ Buscar     │  │ Confirmar   │
        │ Vacunaciones  │  │ Ganado     │  │ Venta       │
        └───────────────┘  └─────┬──────┘  └────┬────────┘
                                 │              │
                        ┌────────▼──────┐  ┌────▼────────┐
                        │ Ver Detalles  │  │ Generar     │
                        └────────┬──────┘  │ Comisiones  │
                                 │        └─────────────┘
                        ┌────────▼──────┐
                        │ Realizar      │
                        │ Compra        │
                        └───────────────┘
```

---

## 📝 MATRIZ DE INCLUSIONES

```
INCLUYE              │ Registrar │ Iniciar │ Buscar │ Ver Det │ Registr │
                     │ Usuario   │ Sesión  │ Ganado │ Ganado  │ Vacunas │
─────────────────────┼───────────┼─────────┼────────┼─────────┼─────────┤
Validar Datos        │    ✓      │    ✓    │   -    │    -    │    -    │
Ver Detalles         │    -      │    -    │   ✓    │    ✓    │    -    │
Ver Vacunaciones     │    -      │    -    │   -    │    ✓    │    -    │
Ver Ubicación Mapa   │    -      │    -    │   -    │    ✓    │    -    │
Gráficos Estadísticos│    -      │    -    │   -    │    -    │    -    │
```

---

## 📌 REGLAS DE DEPENDENCIA

### Regla 1: Autenticación Primaria
Todos los casos de uso (excepto Registrar y Restablecer Contraseña) **REQUIEREN** Iniciar Sesión.

### Regla 2: Propiedad
Casos de uso como Editar, Eliminar, Actualizar ganado **REQUIEREN** ser propietario.

### Regla 3: Disponibilidad
Realizar Compra **REQUIERE** ganado en estado "disponible".

### Regla 4: Confirmación
Confirmar Venta **REQUIERE** previo Recibir Solicitud de Compra.

### Regla 5: No Autocompra
Realizar Compra no permite al vendedor comprar su propio ganado.

### Regla 6: Eliminación Condicionada
Eliminar Cuenta **REQUIERE** sin ganado y sin ventas pendientes.

---

## 🚀 SECUENCIA TÍPICA DE EVENTOS

### Escenario: Comprador realiza su primera compra

```
1. Registrar Usuario
   └─ Incluye: Validar Datos

2. Iniciar Sesión
   ├─ Incluye: Validar Datos
   └─ Habilita: todos los demás CU

3. Explorar Catálogo
   └─ Habilita: Buscar Ganado

4. Buscar Ganado
   └─ Habilita: Ver Detalles

5. Ver Detalles del Animal
   ├─ Incluye: Ver Vacunaciones
   ├─ Incluye: Ver Ubicación Mapa
   └─ Habilita: Realizar Compra

6. Realizar Compra
   ├─ Crea: Venta (pendiente)
   ├─ Habilita: Ver Mis Compras
   └─ Habilita: Recibir Solicitud (vendedor)

7. Ver Mis Compras
   └─ Muestra: Compra pendiente

[VENDEDOR CONFIRMA VENTA]

8. Confirmar Venta (vendedor)
   ├─ Actualiza: Venta a "completada"
   ├─ Actualiza: Ganado a "vendido"
   └─ Habilita: Ver Mis Compras (actualizado)

9. Ver Mis Compras (comprador)
   └─ Muestra: Compra completada
```

---

**Documento Generado**: 23 de Noviembre de 2025  
**Total de Relaciones Mapeadas**: 50+  
**Casos de Uso Interrelacionados**: 45
