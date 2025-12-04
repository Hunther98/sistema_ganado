# CASOS DE USO - SISTEMA DE VENTA DE GANADO

## 📋 Descripción General

Sistema web completo para comprar y vender ganado de calidad. La plataforma conecta vendedores y compradores de forma segura, permitiendo publicar, buscar, y gestionar transacciones de animales.

---

## 👥 Actores del Sistema

### 1. **Vendedor**
- Usuario que publica y vende ganado
- Gestiona su inventario de animales
- Realiza seguimiento de ventas
- Registra información de vacunaciones

### 2. **Comprador**
- Usuario que busca y compra ganado
- Explora el catálogo de animales
- Visualiza detalles y ubicaciones
- Realiza compras y seguimiento

### 3. **Administrador**
- Gestiona usuarios del sistema
- Genera reportes y estadísticas
- Mantiene la integridad del sistema
- Asigna roles y permisos

### 4. **Sistema**
- Valida datos y reglas de negocio
- Procesa compras y ventas
- Mantiene integridad referencial
- Genera reportes automáticos

---

## 🎯 CASO DE USO 1: AUTENTICACIÓN

### Descripción
Permite a los usuarios registrarse, iniciar sesión y cerrar sesión en el sistema.

### Actores Involucrados
- Usuario no autenticado
- Sistema

### Casos de Uso Incluidos

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Registrar Usuario** | Crear nueva cuenta en el sistema | Usuario no registrado | Usuario registrado en base de datos |
| **Iniciar Sesión** | Acceder al sistema con credenciales | Usuario registrado | Sesión activa, datos en $_SESSION |
| **Cerrar Sesión** | Terminar sesión actual | Usuario autenticado | Sesión destruida |
| **Validar Datos** | Verificar campos requeridos | Datos ingresados | Validación completada |

### Reglas de Negocio
- Email único en el sistema
- Contraseña mínimo 6 caracteres
- Formato de email válido
- Ambas contraseñas deben coincidir

### Diagrama
`CasosDeUso_1_Autenticacion.drawio`

---

## 📦 CASO DE USO 2: GESTIÓN DE GANADO

### Descripción
Permite a vendedores publicar, actualizar, eliminar y gestionar sus animales, así como registrar información de vacunaciones.

### Actores Involucrados
- Vendedor
- Sistema

### Casos de Uso Incluidos

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Registrar Ganado** | Publicar nuevo animal | Vendedor autenticado | Ganado disponible en catálogo |
| **Actualizar Ganado** | Modificar datos de animal | Ganado existente, propiedad del vendedor | Datos actualizados |
| **Eliminar Ganado** | Remover animal del catálogo | Ganado disponible | Ganado eliminado de sistema |
| **Listar/Buscar Ganado** | Ver animales disponibles | Sistema activo | Lista filtrada de ganado |
| **Ver Detalle Ganado** | Información completa del animal | Ganado publicado | Detalles mostrados |
| **Registrar Vacunaciones** | Añadir historial de vacunación | Ganado registrado | Vacunación guardada |
| **Actualizar Estado** | Cambiar estado (disponible/vendido/reservado) | Ganado existente | Estado actualizado |

### Datos del Ganado
- Nombre y descripción
- Raza y edad
- Peso y precio
- Imagen
- Ubicación (dirección, latitud, longitud)
- Estado (disponible, vendido, reservado)

### Información de Vacunaciones
- Nombre de la vacuna
- Fecha de aplicación
- Próxima vacunación
- Observaciones

### Diagrama
`CasosDeUso_2_GestionGanado.drawio`

---

## 🛍️ CASO DE USO 3: COMPRAS

### Descripción
Permite a compradores explorar el catálogo, buscar animales específicos y realizar compras.

### Actores Involucrados
- Comprador
- Sistema

### Casos de Uso Incluidos

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Explorar Catálogo** | Ver listado general de ganado | Sistema activo | Catálogo mostrado |
| **Buscar Ganado** | Filtrar por criterios específicos | Ganado disponible | Resultados filtrados |
| **Ver Detalles del Animal** | Información completa del animal | Ganado en catálogo | Detalles mostrados |
| **Ver Historial de Vacunaciones** | Información sanitaria | Ganado con vacunaciones | Historial mostrado |
| **Realizar Compra** | Procesar compra de animal | Ganado disponible, comprador autenticado | Venta registrada |
| **Ver Mis Compras** | Historial de compras del usuario | Comprador autenticado | Listado de compras |
| **Ver Ubicación en Mapa** | Localización geográfica del animal | Coordenadas disponibles | Mapa mostrado |

### Filtros de Búsqueda
- Raza
- Rango de edad
- Rango de precio
- Ubicación
- Estado de disponibilidad

### Diagrama
`CasosDeUso_3_Compras.drawio`

---

## 💰 CASO DE USO 4: VENTAS

### Descripción
Permite a vendedores recibir solicitudes de compra, confirmar transacciones y gestionar comisiones.

### Actores Involucrados
- Vendedor
- Sistema

### Casos de Uso Incluidos

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Recibir Solicitud de Compra** | Sistema registra nueva compra | Comprador inicia compra | Venta en estado "pendiente" |
| **Confirmar Venta** | Vendedor aprueba la transacción | Venta pendiente | Venta en estado "completada" |
| **Cancelar Venta** | Vendedor rechaza la transacción | Venta pendiente | Venta en estado "cancelada" |
| **Ver Mis Ventas** | Historial de ventas del vendedor | Vendedor autenticado | Listado de ventas |
| **Ver Estado de Ventas** | Detalles de cada transacción | Ventas existentes | Estado mostrado |
| **Generar Reporte de Comisiones** | Calcular ganancias por ventas | Ventas completadas | Reporte generado |

### Estados de Venta
- **Pendiente**: Esperando confirmación del vendedor
- **Completada**: Venta exitosa (comisión 5% del precio)
- **Cancelada**: Venta rechazada

### Cálculo de Comisión
```
Comisión = Precio de Venta × 5%
Ganancia del Vendedor = Precio de Venta - Comisión
```

### Diagrama
`CasosDeUso_4_Ventas.drawio`

---

## 👤 CASO DE USO 5: GESTIÓN DE USUARIOS

### Descripción
Permite a usuarios gestionar sus perfiles y permite al administrador gestionar todos los usuarios.

### Actores Involucrados
- Usuario
- Administrador
- Sistema

### Casos de Uso para Usuarios

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Ver/Editar Perfil** | Administrar información personal | Usuario autenticado | Perfil actualizado |
| **Cambiar Contraseña** | Actualizar contraseña de acceso | Usuario autenticado | Contraseña actualizada |
| **Restablecer Contraseña** | Recuperar acceso a cuenta | Usuario no autenticado | Contraseña restablecida |
| **Eliminar Cuenta** | Borrar cuenta del usuario | Usuario autenticado, sin ganado ni ventas pendientes | Cuenta eliminada |

### Casos de Uso para Administrador

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Listar Usuarios** | Ver todos los usuarios registrados | Admin autenticado | Lista mostrada |
| **Editar Usuario** | Modificar información de usuario | Usuario existente | Datos actualizados |
| **Eliminar Usuario** | Borrar usuario del sistema | Usuario existente | Usuario eliminado |
| **Asignar Roles** | Establecer tipo de usuario | Usuario existente | Rol asignado |

### Tipos de Usuarios
- **Comprador**: Puede comprar ganado
- **Vendedor**: Puede vender ganado
- **Admin**: Control total del sistema

### Validaciones para Eliminar Cuenta
- No tener ganado registrado
- No tener ventas pendientes
- Confirmación con contraseña

### Diagrama
`CasosDeUso_5_GestionUsuarios.drawio`

---

## 📊 CASO DE USO 6: REPORTES Y ANÁLISIS

### Descripción
Genera reportes estadísticos y permite análisis de datos del sistema.

### Actores Involucrados
- Usuario
- Administrador
- Sistema

### Casos de Uso para Usuarios

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Ver Estadísticas Generales** | Información del sistema en general | Sistema activo | Estadísticas mostradas |
| **Ver Mis Estadísticas** | Estadísticas personales | Usuario autenticado | Estadísticas mostradas |
| **Exportar Datos** | Descargar información en formatos | Datos disponibles | Archivo generado |

### Casos de Uso para Administrador

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Descargar Reportes PDF** | Generar reportes en PDF | Datos disponibles | PDF generado (TCPDF) |
| **Listar Reportes Parametrizados** | Acceder a reportes predefinidos | Sistema configurado | Reportes mostrados |
| **Reportes de Ventas por Usuario** | Análisis de ventas | Ventas registradas | Reporte generado |
| **Reporte de Ganado por Estado** | Ganado según su estado | Ganado registrado | Reporte generado |
| **Ver Gráficos Estadísticos** | Visualización de datos | Datos disponibles | Gráficos mostrados |

### Estadísticas Disponibles
- Total de usuarios
- Total de animales registrados
- Total de ventas realizadas
- Total de ubicaciones
- Razas disponibles
- Estados de ganado

### Diagrama
`CasosDeUso_6_ReportesAnalisis.drawio`

---

## 🔧 CASO DE USO 7: ADMINISTRACIÓN DEL SISTEMA

### Descripción
Permite al administrador mantener y configurar el sistema.

### Actores Involucrados
- Administrador
- Sistema

### Casos de Uso Incluidos

| Caso de Uso | Descripción | Precondiciones | Postcondiciones |
|-------------|-------------|-----------------|-----------------|
| **Ver Estructura de Tablas** | Revisar esquema de base de datos | Admin autenticado | Tablas mostradas |
| **Realizar Mantenimiento** | Optimizar y limpiar datos | Acceso administrativo | Mantenimiento completado |
| **Gestionar Copias de Seguridad** | Backup y restauración | Acceso administrativo | Backup realizado |
| **Gestionar Permisos** | Asignar permisos a usuarios | Admin autenticado | Permisos actualizados |
| **Ver Auditoría del Sistema** | Registro de actividades | Auditoría habilitada | Log mostrado |
| **Configurar Parámetros del Sistema** | Ajustes generales | Admin autenticado | Parámetros actualizados |

### Tabla de Estructura
- **Usuarios**: Datos de usuarios (id, nombre, email, contraseña, tipo, etc.)
- **Ganado**: Información de animales (id, usuario_id, raza, edad, peso, precio, etc.)
- **Vacunaciones**: Historial de vacunas (id, ganado_id, vacuna, fecha, etc.)
- **Ventas**: Transacciones (id, comprador_id, vendedor_id, ganado_id, estado, etc.)

### Diagrama
`CasosDeUso_7_Administracion.drawio`

---

## 📈 DIAGRAMA GENERAL

Diagrama que integra todos los actores y casos de uso principales:

`CasosDeUso_0_GENERAL.drawio`

---

## 🗺️ Flujos Principales

### Flujo de Compra
1. Comprador explora catálogo
2. Comprador busca por criterios
3. Comprador ve detalles del animal
4. Comprador realiza compra
5. Venta queda pendiente de confirmación
6. Vendedor confirma venta
7. Comprador puede ver su compra

### Flujo de Venta
1. Vendedor registra ganado
2. Vendedor añade información de vacunaciones
3. Ganado aparece en catálogo
4. Comprador realiza compra
5. Vendedor recibe solicitud
6. Vendedor confirma o cancela venta
7. Venta se completa

### Flujo de Administración
1. Administrador lista usuarios
2. Administrador gestiona roles
3. Administrador genera reportes
4. Administrador realiza mantenimiento
5. Administrador revisa auditoría

---

## 📁 Archivos Generados

```
CasosDeUso_0_GENERAL.drawio                 - Diagrama integral
CasosDeUso_1_Autenticacion.drawio          - Autenticación
CasosDeUso_2_GestionGanado.drawio          - Gestión de ganado
CasosDeUso_3_Compras.drawio                - Proceso de compras
CasosDeUso_4_Ventas.drawio                 - Proceso de ventas
CasosDeUso_5_GestionUsuarios.drawio        - Gestión de usuarios
CasosDeUso_6_ReportesAnalisis.drawio       - Reportes y análisis
CasosDeUso_7_Administracion.drawio         - Administración del sistema
CASOS_DE_USO_DOCUMENTACION.md              - Este archivo
```

---

## 🔐 Notas de Seguridad

- Las contraseñas se encriptan con bcrypt ($2y$10$...)
- Validación de datos en entrada
- Control de acceso basado en roles
- Verificación de propiedad antes de operaciones
- No se permite autocompra
- Restricciones para eliminación de cuentas

---

## 💡 Consideraciones Técnicas

### Stack Tecnológico
- **Backend**: PHP (MVC)
- **Base de Datos**: MySQL
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Reportes**: TCPDF
- **Mapas**: Integración de mapas geográficos

### Componentes Principales
- **Presentación**: Archivos PHP en `/presentacion`
- **Negocio**: Clases en `/negocio`
- **Datos**: DAOs en `/datos`
- **Configuración**: `/config`

### Validaciones
- Email válido
- Campos requeridos
- Tipos de datos correctos
- Rango de valores

---

## 📞 Contacto y Soporte

Para más información sobre los casos de uso o el sistema, consultar la documentación en el archivo de base de datos: `database_ganado.sql`

---

**Documento generado**: 23 de Noviembre de 2025  
**Sistema**: Sistema de Venta de Ganado  
**Versión**: 1.0
