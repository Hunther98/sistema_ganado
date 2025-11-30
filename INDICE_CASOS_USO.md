# 🎯 ÍNDICE DE CASOS DE USO - SISTEMA DE VENTA DE GANADO

## 📚 Documentación Completa de Casos de Uso

Este documento sirve como índice principal para acceder a todos los casos de uso del sistema.

---

## 🗂️ ARCHIVOS DISPONIBLES

### 📊 Diagramas en DrawIO

| # | Archivo | Tema | Actores | CU | Descripción |
|---|---------|------|--------|----|----|
| 0 | **CasosDeUso_0_GENERAL.drawio** | Sistema Completo | Todos | 45 | Diagrama integral con todos los casos de uso |
| 1 | **CasosDeUso_1_Autenticacion.drawio** | Autenticación | Usuario, Sistema | 4 | Registro, login, logout y validación |
| 2 | **CasosDeUso_2_GestionGanado.drawio** | Gestión de Ganado | Vendedor, Sistema | 7 | CRUD de ganado y vacunaciones |
| 3 | **CasosDeUso_3_Compras.drawio** | Compras | Comprador, Sistema | 7 | Búsqueda, detalles y compra |
| 4 | **CasosDeUso_4_Ventas.drawio** | Ventas | Vendedor, Sistema | 6 | Recepción, confirmación y comisiones |
| 5 | **CasosDeUso_5_GestionUsuarios.drawio** | Usuarios | Usuario, Admin | 8 | Perfiles y administración de usuarios |
| 6 | **CasosDeUso_6_ReportesAnalisis.drawio** | Reportes | Usuario, Admin | 8 | Estadísticas y generación de reportes |
| 7 | **CasosDeUso_7_Administracion.drawio** | Administración | Admin | 6 | Mantenimiento del sistema |

### 📖 Documentación Markdown

| Archivo | Contenido |
|---------|-----------|
| **CASOS_DE_USO_DOCUMENTACION.md** | Documentación completa y detallada de todos los 45 casos de uso |
| **RESUMEN_ACCIONES_VISUALES.md** | Resumen visual con tablas y flujos de procesos |
| **INDICE_CASOS_USO.md** | Este archivo (índice de navegación) |

---

## 🎯 CASOS DE USO POR CATEGORÍA

### 1. AUTENTICACIÓN (3 Casos)

```
Archivo: CasosDeUso_1_Autenticacion.drawio

Actores:
  • Usuario (no autenticado)
  • Sistema

Casos de Uso:
  1. Registrar Usuario
  2. Iniciar Sesión
  3. Cerrar Sesión
  4. Validar Datos (include)
  
Archivos relacionados:
  • presentacion/pRegistro.php
  • presentacion/pLogin.php
  • negocio/nUsuario.php
  • datos/dUsuario.php
```

**Flujo Típico**: Usuario no registrado → Registrarse → Sistema valida → Cuenta creada → Login

---

### 2. GESTIÓN DE GANADO (7 Casos)

```
Archivo: CasosDeUso_2_GestionGanado.drawio

Actores:
  • Vendedor
  • Sistema

Casos de Uso:
  1. Registrar Ganado
  2. Actualizar Ganado
  3. Eliminar Ganado
  4. Listar/Buscar Ganado
  5. Ver Detalle Ganado
  6. Registrar Vacunaciones (extend)
  7. Actualizar Estado Ganado
  
Archivos relacionados:
  • presentacion/pGanado.php
  • presentacion/pVacunacion.php
  • presentacion/admin/listar_ganado.php
  • negocio/nGanado.php
  • negocio/nVacunacion.php
  • datos/dGanado.php
  • datos/dVacunacion.php
```

**Flujo Típico**: Vendedor → Publicar Ganado → Sistema valida → Aparece en catálogo → Actualiza vacunaciones

---

### 3. COMPRAS (7 Casos)

```
Archivo: CasosDeUso_3_Compras.drawio

Actores:
  • Comprador
  • Sistema

Casos de Uso:
  1. Explorar Catálogo
  2. Buscar Ganado
  3. Ver Detalles del Animal (extend)
  4. Ver Historial de Vacunaciones (include)
  5. Ver Ubicación en Mapa (include)
  6. Realizar Compra
  7. Ver Mis Compras
  
Archivos relacionados:
  • presentacion/catalogo.php
  • presentacion/detalle_ganado.php
  • presentacion/mis_compras.php
  • presentacion/procesar_venta.php
  • negocio/nGanado.php
  • datos/dGanado.php
  • datos/dVenta.php
```

**Flujo Típico**: Comprador → Buscar → Ver detalles → Ver vacunaciones → Ver ubicación → Comprar → Pendiente confirmación

---

### 4. VENTAS (6 Casos)

```
Archivo: CasosDeUso_4_Ventas.drawio

Actores:
  • Vendedor
  • Sistema

Casos de Uso:
  1. Recibir Solicitud de Compra
  2. Confirmar Venta
  3. Cancelar Venta
  4. Ver Mis Ventas
  5. Ver Estado de Ventas (extend)
  6. Generar Reporte de Comisiones
  
Archivos relacionados:
  • presentacion/mis_ventas.php
  • presentacion/pVenta.php
  • presentacion/detalle_compra.php
  • negocio/nVenta.php
  • datos/dVenta.php
```

**Flujo Típico**: Comprador realiza compra → Venta "pendiente" → Vendedor ve solicitud → Confirma/Cancela → Venta completada/cancelada

---

### 5. GESTIÓN DE USUARIOS (8 Casos)

```
Archivo: CasosDeUso_5_GestionUsuarios.drawio

Actores:
  • Usuario
  • Administrador
  • Sistema

Para Usuarios:
  1. Ver/Editar Perfil
  2. Cambiar Contraseña
  3. Restablecer Contraseña
  4. Eliminar Cuenta

Para Administrador:
  5. Listar Usuarios
  6. Editar Usuario
  7. Eliminar Usuario
  8. Asignar Roles
  
Archivos relacionados:
  • presentacion/perfil.php
  • presentacion/pRestablecer_password.php
  • presentacion/admin/editar_usuario.php
  • presentacion/admin/listar_usuario.php
  • presentacion/admin/procesarAsignarRol.php
  • negocio/nUsuario.php
  • negocio/nAsignarRol.php
  • datos/dUsuario.php
```

**Flujo Típico**: Usuario edita perfil → Ver cambios → Admin lista usuarios → Edita/asigna roles

---

### 6. REPORTES Y ANÁLISIS (8 Casos)

```
Archivo: CasosDeUso_6_ReportesAnalisis.drawio

Actores:
  • Usuario
  • Administrador
  • Sistema

Para Usuarios:
  1. Ver Estadísticas Generales
  2. Ver Mis Estadísticas
  3. Exportar Datos

Para Administrador:
  4. Descargar Reportes PDF
  5. Listar Reportes Parametrizados
  6. Reportes de Ventas por Usuario
  7. Reporte de Ganado por Estado
  8. Ver Gráficos Estadísticos (include)
  
Archivos relacionados:
  • presentacion/obtener_estadisticas.php
  • presentacion/admin/descargarReportes.php
  • presentacion/admin/descargarReportesPDF.php
  • presentacion/admin/listarReportesParametrizados.php
  • negocio/nEstadisticas.php
  • datos/dEstadisticas.php
  • tcpdf/ (librería de reportes PDF)
```

**Flujo Típico**: Admin → Genera reporte → Descarga PDF → Analiza datos

---

### 7. ADMINISTRACIÓN DEL SISTEMA (6 Casos)

```
Archivo: CasosDeUso_7_Administracion.drawio

Actores:
  • Administrador
  • Sistema

Casos de Uso:
  1. Ver Estructura de Tablas
  2. Realizar Mantenimiento
  3. Gestionar Copias de Seguridad
  4. Gestionar Permisos
  5. Ver Auditoría del Sistema
  6. Configurar Parámetros del Sistema
  
Archivos relacionados:
  • presentacion/admin/admin.php
  • presentacion/admin/ver_tablas.php
  • config/config.php
  • datos/dConexion.php
```

**Flujo Típico**: Admin accede panel → Ve estructura BD → Realiza mantenimiento

---

## 📊 ESTADÍSTICAS GENERALES

### Resumen de Casos de Uso

```
┌─────────────────────────────────────────┐
│ Categoría              │ Cantidad        │
├──────────────────────────────────────────┤
│ 1. Autenticación       │ 3 casos         │
│ 2. Gestión de Ganado   │ 7 casos         │
│ 3. Compras             │ 7 casos         │
│ 4. Ventas              │ 6 casos         │
│ 5. Gestión Usuarios    │ 8 casos         │
│ 6. Reportes            │ 8 casos         │
│ 7. Administración      │ 6 casos         │
├──────────────────────────────────────────┤
│ TOTAL                  │ 45 casos        │
└─────────────────────────────────────────┘
```

### Distribución por Actor

```
┌───────────────────────────────────────────┐
│ Actor              │ Casos de Uso        │
├───────────────────────────────────────────┤
│ Usuario            │ 11 casos            │
│ Vendedor           │ 13 casos            │
│ Comprador          │ 7 casos             │
│ Administrador      │ 16 casos            │
│ Sistema            │ 45 casos (todos)    │
└───────────────────────────────────────────┘
```

---

## 🔍 CÓMO USAR ESTE ÍNDICE

### Para consultar un caso de uso específico:

1. **Identifica la categoría** que te interesa (ej: Compras)
2. **Abre el archivo DrawIO correspondiente** (ej: CasosDeUso_3_Compras.drawio)
3. **Revisa la documentación** en CASOS_DE_USO_DOCUMENTACION.md
4. **Localiza los archivos del proyecto** relacionados

### Para ver todos los casos de uso:

1. **Abre CasosDeUso_0_GENERAL.drawio** para ver la visión completa
2. **Lee CASOS_DE_USO_DOCUMENTACION.md** para detalles
3. **Consulta RESUMEN_ACCIONES_VISUALES.md** para flujos

---

## 🚀 ACCIONES RÁPIDAS

### Vendedor quiere publicar ganado:
→ Ver: CasosDeUso_2_GestionGanado.drawio + CASOS_DE_USO_DOCUMENTACION.md (Caso 2)

### Comprador quiere buscar ganado:
→ Ver: CasosDeUso_3_Compras.drawio + RESUMEN_ACCIONES_VISUALES.md

### Admin quiere generar reportes:
→ Ver: CasosDeUso_6_ReportesAnalisis.drawio + CASOS_DE_USO_DOCUMENTACION.md (Caso 6)

### Usuario quiere cambiar perfil:
→ Ver: CasosDeUso_5_GestionUsuarios.drawio

### Entender flujo de compra completo:
→ Ver: RESUMEN_ACCIONES_VISUALES.md (Sección FLUJOS DE PROCESOS CLAVE)

---

## 📋 CHECKLIST DE CASOS DE USO

### Autenticación ✓
- [x] Registrar Usuario
- [x] Iniciar Sesión
- [x] Cerrar Sesión
- [x] Validar Datos

### Gestión de Ganado ✓
- [x] Registrar Ganado
- [x] Actualizar Ganado
- [x] Eliminar Ganado
- [x] Listar/Buscar Ganado
- [x] Ver Detalles
- [x] Registrar Vacunaciones
- [x] Actualizar Estado

### Compras ✓
- [x] Explorar Catálogo
- [x] Buscar Ganado
- [x] Ver Detalles
- [x] Ver Vacunaciones
- [x] Ver Ubicación en Mapa
- [x] Realizar Compra
- [x] Ver Mis Compras

### Ventas ✓
- [x] Recibir Solicitud
- [x] Confirmar Venta
- [x] Cancelar Venta
- [x] Ver Mis Ventas
- [x] Ver Estado
- [x] Generar Reporte de Comisiones

### Gestión de Usuarios ✓
- [x] Ver/Editar Perfil
- [x] Cambiar Contraseña
- [x] Restablecer Contraseña
- [x] Eliminar Cuenta
- [x] Listar Usuarios (Admin)
- [x] Editar Usuario (Admin)
- [x] Eliminar Usuario (Admin)
- [x] Asignar Roles (Admin)

### Reportes y Análisis ✓
- [x] Ver Estadísticas Generales
- [x] Ver Mis Estadísticas
- [x] Exportar Datos
- [x] Descargar PDF
- [x] Reportes Parametrizados
- [x] Reportes de Ventas por Usuario
- [x] Reporte de Ganado por Estado
- [x] Ver Gráficos

### Administración ✓
- [x] Ver Estructura de Tablas
- [x] Realizar Mantenimiento
- [x] Gestionar Backups
- [x] Gestionar Permisos
- [x] Ver Auditoría
- [x] Configurar Parámetros

---

## 💾 ARCHIVOS GENERADOS

```
📁 sistema_ganado_septiembre/
│
├── 📊 DIAGRAMAS DRAWIO
│   ├── CasosDeUso_0_GENERAL.drawio
│   ├── CasosDeUso_1_Autenticacion.drawio
│   ├── CasosDeUso_2_GestionGanado.drawio
│   ├── CasosDeUso_3_Compras.drawio
│   ├── CasosDeUso_4_Ventas.drawio
│   ├── CasosDeUso_5_GestionUsuarios.drawio
│   ├── CasosDeUso_6_ReportesAnalisis.drawio
│   └── CasosDeUso_7_Administracion.drawio
│
├── 📖 DOCUMENTACIÓN MARKDOWN
│   ├── CASOS_DE_USO_DOCUMENTACION.md (Completa y detallada)
│   ├── RESUMEN_ACCIONES_VISUALES.md (Visual y flujos)
│   └── INDICE_CASOS_USO.md (Este archivo)
│
└── [Otros archivos del proyecto...]
```

---

## 🎓 GUÍA DE LECTURA RECOMENDADA

**Para principiantes:**
1. Lee RESUMEN_ACCIONES_VISUALES.md
2. Abre CasosDeUso_0_GENERAL.drawio
3. Consulta CasosDeUso_1_Autenticacion.drawio a CasosDeUso_7_Administracion.drawio

**Para desarrolladores:**
1. Lee CASOS_DE_USO_DOCUMENTACION.md (Secciones 2-7)
2. Abre diagramas específicos según lo que quieras trabajar
3. Localiza archivos PHP en el proyecto

**Para administradores:**
1. Enfócate en CasosDeUso_7_Administracion.drawio
2. Lee sección 7 de CASOS_DE_USO_DOCUMENTACION.md
3. Consulta CasosDeUso_6_ReportesAnalisis.drawio

**Para stakeholders/Clientes:**
1. Lee RESUMEN_ACCIONES_VISUALES.md
2. Visualiza CasosDeUso_0_GENERAL.drawio
3. Revisa el resumen de actores

---

## 📞 INFORMACIÓN DE CONTACTO

Para más información sobre los casos de uso o el sistema:

- **Documentación Técnica**: Ver `database_ganado.sql`
- **Código Fuente**: Revisar carpetas `presentacion/`, `negocio/`, `datos/`
- **Configuración**: Ver `config/config.php`

---

## 📅 VERSIONADO

| Versión | Fecha | Cambios |
|---------|-------|---------|
| 1.0 | 23-11-2025 | Documentación inicial completa de 45 casos de uso |

---

**Generado**: 23 de Noviembre de 2025  
**Total Diagramas**: 8  
**Total Documentos**: 3  
**Casos de Uso Documentados**: 45  
**Estado**: ✅ Completo
