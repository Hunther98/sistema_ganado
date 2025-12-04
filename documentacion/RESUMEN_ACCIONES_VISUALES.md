# 📊 RESUMEN VISUAL - ACCIONES DEL SISTEMA DE VENTA DE GANADO

## 🎯 ACCIONES PRINCIPALES POR ACTOR

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SISTEMA DE VENTA DE GANADO                               │
│                                                                               │
│  ╔════════════════════╗  ╔════════════════════╗  ╔════════════════════╗   │
│  ║    VENDEDOR        ║  ║    COMPRADOR       ║  ║  ADMINISTRADOR     ║   │
│  ║                    ║  ║                    ║  ║                    ║   │
│  ║ ✓ Registrarse      ║  ║ ✓ Registrarse      ║  ║ ✓ Registrarse      ║   │
│  ║ ✓ Iniciar Sesión   ║  ║ ✓ Iniciar Sesión   ║  ║ ✓ Iniciar Sesión   ║   │
│  ║ ✓ Publicar Ganado  ║  ║ ✓ Explorar Catálogo║  ║ ✓ Ver Usuarios     ║   │
│  ║ ✓ Editar Ganado    ║  ║ ✓ Buscar Ganado    ║  ║ ✓ Editar Usuarios  ║   │
│  ║ ✓ Eliminar Ganado  ║  ║ ✓ Ver Detalles     ║  ║ ✓ Eliminar Usuarios║   │
│  ║ ✓ Vacunaciones     ║  ║ ✓ Ver Vacunaciones ║  ║ ✓ Asignar Roles    ║   │
│  ║ ✓ Recibir Compras  ║  ║ ✓ Ver Ubicación    ║  ║ ✓ Ver Estadísticas ║   │
│  ║ ✓ Confirmar Venta  ║  ║ ✓ Realizar Compra  ║  ║ ✓ Generar Reportes ║   │
│  ║ ✓ Ver Mis Ventas   ║  ║ ✓ Ver Mis Compras  ║  ║ ✓ Mantener Sistema ║   │
│  ║ ✓ Ver Perfil       ║  ║ ✓ Ver Perfil       ║  ║ ✓ Ver Auditoría    ║   │
│  ║ ✓ Cambiar Contraseña║ ║ ✓ Cambiar Contraseña║ ║ ✓ Backups          ║   │
│  ║ ✓ Eliminar Cuenta  ║  ║ ✓ Eliminar Cuenta  ║  ║                    ║   │
│  ║                    ║  ║                    ║  ║                    ║   │
│  ╚════════════════════╝  ╚════════════════════╝  ╚════════════════════╝   │
│                                                                               │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 📋 CATEGORÍAS DE CASOS DE USO

### 1️⃣ AUTENTICACIÓN (Casos de Uso: 3)
```
┌──────────────────────────────────────┐
│  AUTENTICACIÓN                       │
├──────────────────────────────────────┤
│  ✓ Registrar Usuario                │
│  ✓ Iniciar Sesión (Login)           │
│  ✓ Cerrar Sesión (Logout)           │
└──────────────────────────────────────┘
```

### 2️⃣ GESTIÓN DE GANADO (Casos de Uso: 7)
```
┌──────────────────────────────────────┐
│  GESTIÓN DE GANADO                   │
├──────────────────────────────────────┤
│  ✓ Registrar Ganado                 │
│  ✓ Actualizar Ganado                │
│  ✓ Eliminar Ganado                  │
│  ✓ Listar/Buscar Ganado             │
│  ✓ Ver Detalles                     │
│  ✓ Registrar Vacunaciones           │
│  ✓ Actualizar Estado de Ganado      │
└──────────────────────────────────────┘
```

### 3️⃣ COMPRAS (Casos de Uso: 7)
```
┌──────────────────────────────────────┐
│  PROCESO DE COMPRAS                  │
├──────────────────────────────────────┤
│  ✓ Explorar Catálogo                │
│  ✓ Buscar Ganado                    │
│  ✓ Ver Detalles del Animal          │
│  ✓ Ver Historial de Vacunaciones    │
│  ✓ Ver Ubicación en Mapa            │
│  ✓ Realizar Compra                  │
│  ✓ Ver Mis Compras                  │
└──────────────────────────────────────┘
```

### 4️⃣ VENTAS (Casos de Uso: 6)
```
┌──────────────────────────────────────┐
│  PROCESO DE VENTAS                   │
├──────────────────────────────────────┤
│  ✓ Recibir Solicitud de Compra      │
│  ✓ Confirmar Venta                  │
│  ✓ Cancelar Venta                   │
│  ✓ Ver Mis Ventas                   │
│  ✓ Ver Estado de Ventas             │
│  ✓ Generar Reporte de Comisiones    │
└──────────────────────────────────────┘
```

### 5️⃣ GESTIÓN DE USUARIOS (Casos de Uso: 8)
```
┌──────────────────────────────────────┐
│  GESTIÓN DE USUARIOS                 │
├──────────────────────────────────────┤
│  Para Usuarios:                      │
│  ✓ Ver/Editar Perfil                │
│  ✓ Cambiar Contraseña               │
│  ✓ Restablecer Contraseña           │
│  ✓ Eliminar Cuenta                  │
│                                      │
│  Para Administrador:                 │
│  ✓ Listar Usuarios                  │
│  ✓ Editar Usuario                   │
│  ✓ Eliminar Usuario                 │
│  ✓ Asignar Roles                    │
└──────────────────────────────────────┘
```

### 6️⃣ REPORTES Y ANÁLISIS (Casos de Uso: 8)
```
┌──────────────────────────────────────┐
│  REPORTES Y ANÁLISIS                 │
├──────────────────────────────────────┤
│  Para Usuarios:                      │
│  ✓ Ver Estadísticas Generales       │
│  ✓ Ver Mis Estadísticas             │
│  ✓ Exportar Datos                   │
│                                      │
│  Para Administrador:                 │
│  ✓ Descargar Reportes PDF           │
│  ✓ Listar Reportes Parametrizados   │
│  ✓ Reportes de Ventas por Usuario   │
│  ✓ Reporte de Ganado por Estado     │
│  ✓ Ver Gráficos Estadísticos        │
└──────────────────────────────────────┘
```

### 7️⃣ ADMINISTRACIÓN DEL SISTEMA (Casos de Uso: 6)
```
┌──────────────────────────────────────┐
│  ADMINISTRACIÓN DEL SISTEMA          │
├──────────────────────────────────────┤
│  ✓ Ver Estructura de Tablas         │
│  ✓ Realizar Mantenimiento           │
│  ✓ Gestionar Copias de Seguridad    │
│  ✓ Gestionar Permisos               │
│  ✓ Ver Auditoría del Sistema        │
│  ✓ Configurar Parámetros            │
└──────────────────────────────────────┘
```

---

## 📊 ESTADÍSTICAS DE CASOS DE USO

| Categoría | Cantidad | Actores Involucrados |
|-----------|----------|----------------------|
| Autenticación | 3 | Usuario, Sistema |
| Gestión de Ganado | 7 | Vendedor, Sistema |
| Compras | 7 | Comprador, Sistema |
| Ventas | 6 | Vendedor, Sistema |
| Gestión de Usuarios | 8 | Usuario, Admin, Sistema |
| Reportes y Análisis | 8 | Usuario, Admin, Sistema |
| Administración | 6 | Admin, Sistema |
| **TOTAL** | **45** | **3 Actores principales** |

---

## 🔄 FLUJOS DE PROCESOS CLAVE

### 📈 FLUJO DE COMPRA - VENDEDOR A COMPRADOR

```
VENDEDOR                           SISTEMA                        COMPRADOR
   │                                  │                               │
   ├─ Registra Ganado ────────────────>                               │
   │                                  ├─ Valida Datos                │
   │                                  ├─ Guarda Ganado              │
   │                                  ├─ Publica en Catálogo        │
   │                                  │                               │
   │                                  │<──── Busca Ganado ───────────┤
   │                                  ├─ Filtra Resultados           │
   │                                  ├─ Muestra Detalles ──────────>│
   │                                  │                               │
   │                                  │<─── Ver Vacunaciones ────────┤
   │                                  ├─ Muestra Historial ─────────>│
   │                                  │                               │
   │                                  │<──── Ver Ubicación ──────────┤
   │                                  ├─ Muestra Mapa ──────────────>│
   │                                  │                               │
   │                                  │<─── Realizar Compra ─────────┤
   │<──── Recibe Solicitud ──────────┤                               │
   │                                  ├─ Crea Venta "Pendiente"     │
   │ Revisa Compra                   │                               │
   ├─ Confirma Venta ─────────────────>                               │
   │                                  ├─ Actualiza a "Completada"   │
   │                                  ├─ Calcula Comisión (5%)       │
   │                                  ├─ Actualiza Estado Ganado     │
   │                                  ├─ Notifica Comprador ────────>│
   │                                  │                               │
   │ Ve Venta Completada            │<──── Ve Compra en Historial  │
   │                                  │                               │
```

### 💾 DATOS ALMACENADOS EN LA TRANSACCIÓN

```
TABLA: Ventas
┌─────────────────────────────────────────┐
│ id              │ 1                     │
│ comprador_id    │ 5                     │
│ vendedor_id     │ 2                     │
│ ganado_id       │ 10                    │
│ fecha           │ 2025-11-23 12:00:00  │
│ precio_venta    │ 2500.00              │
│ comisión        │ 125.00 (5%)          │
│ estado          │ completada           │
└─────────────────────────────────────────┘

TABLA: Ganado (actualizado)
┌─────────────────────────────────────────┐
│ estado          │ vendido (antes: disponible)
└─────────────────────────────────────────┘
```

---

## 🛡️ REGLAS DE NEGOCIO APLICADAS

### Autenticación
- ✓ Email único en el sistema
- ✓ Contraseña mínimo 6 caracteres
- ✓ Formato de email válido
- ✓ Contraseñas coincidentes al registrar

### Gestión de Ganado
- ✓ Solo vendedores pueden publicar
- ✓ Solo propietario puede editar
- ✓ Imagen requerida
- ✓ Valores numéricos positivos

### Compras y Ventas
- ✓ No se puede autocompra
- ✓ Solo ganado "disponible" puede comprarse
- ✓ Comisión automática (5%)
- ✓ Venta requiere confirmación

### Eliminación de Cuenta
- ✓ Usuario debe no tener ganado
- ✓ Usuario debe no tener ventas pendientes
- ✓ Requiere confirmación de contraseña

### Vacunaciones
- ✓ Asociadas al ganado específico
- ✓ Historial completo disponible
- ✓ Información sanitaria importante

---

## 📁 ARCHIVOS DEL PROYECTO RELACIONADOS

### Archivos de Negocio (Lógica)
```
negocio/
  ├── nUsuario.php              (Gestión de usuarios)
  ├── nGanado.php               (Gestión de ganado y vacunaciones)
  ├── nVenta.php                (Gestión de ventas)
  ├── nEstadisticas.php         (Estadísticas)
  ├── nAsignarRol.php           (Roles)
  ├── nListarRol.php            (Listado de roles)
  └── nVacunacion.php           (Vacunaciones)
```

### Archivos de Datos (DAOs)
```
datos/
  ├── dUsuario.php              (Acceso a datos de usuarios)
  ├── dGanado.php               (Acceso a datos de ganado)
  ├── dVacunacion.php           (Acceso a datos de vacunaciones)
  ├── dVenta.php                (Acceso a datos de ventas)
  ├── dConexion.php             (Conexión a BD)
  ├── dEstadisticas.php         (Datos estadísticos)
  └── dListarRol.php            (Listado de roles)
```

### Archivos de Presentación (Interfaz)
```
presentacion/
  ├── index.php                 (Página de inicio)
  ├── catalogo.php              (Catálogo de ganado)
  ├── pGanado.php               (Publicar/Editar ganado)
  ├── pLogin.php                (Inicio de sesión)
  ├── pRegistro.php             (Registro de usuarios)
  ├── perfil.php                (Perfil de usuario)
  ├── mis_compras.php           (Historial de compras)
  ├── mis_ventas.php            (Historial de ventas)
  ├── pVacunacion.php           (Gestión de vacunaciones)
  ├── pVenta.php                (Gestión de ventas)
  ├── detalle_ganado.php        (Detalles del animal)
  ├── obtener_estadisticas.php  (Estadísticas)
  └── admin/                    (Panel administrativo)
      ├── admin.php
      ├── listar_usuario.php
      ├── editar_usuario.php
      ├── listar_ganado.php
      ├── descargarReportes.php
      └── ...
```

---

## 🎓 CONCLUSIÓN

El sistema **Sistema de Venta de Ganado** es una plataforma completa que implementa:

✅ **45 casos de uso** distribuidos en **7 categorías principales**
✅ **3 actores principales** (Vendedor, Comprador, Administrador)
✅ **Múltiples reglas de negocio** para garantizar integridad
✅ **Reportes y análisis** avanzados
✅ **Seguridad** en autenticación y manejo de datos
✅ **Interfaz intuitiva** basada en Bootstrap 5
✅ **Base de datos relacional** con MySQL

El proyecto cubre desde la autenticación básica hasta la administración completa del sistema, incluyendo casos de uso especializados para vendedores y compradores.

---

**Documento Generado**: 23 de Noviembre de 2025  
**Total de Diagramas**: 8  
**Total de Casos de Uso Documentados**: 45
