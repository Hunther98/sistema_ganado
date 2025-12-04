# ⚡ GUÍA RÁPIDA DE IMPLEMENTACIÓN

## 🚀 Pasos para Activar las Mejoras

### Paso 1: Crear Tabla en la Base de Datos
```bash
# En phpMyAdmin: SQL tab → Copy & Paste
# O terminal MySQL:
mysql -u root venta_ganado < crear_tabla_contactos.sql
```

### Paso 2: Verificar Header y Footer
Los templates han sido mejorados automáticamente:
- ✅ `/presentacion/template/header.php` - Actualizado
- ✅ `/presentacion/template/footer.php` - Actualizado

### Paso 3: Probar Página de Contacto
1. Navegar a `http://localhost/sistema_ganado_septiembre/presentacion/contacto.php`
2. Completar formulario
3. Enviar mensaje
4. Verificar en BD tabla `contactos`

### Paso 4: Usar Seguridad en Páginas
En cada página protegida, agregar al inicio:
```php
<?php
require_once __DIR__ . '/../config/seguridad.php';
require_once __DIR__ . '/../config/redirecciones.php';

// Requerir autenticación
requerirAutenticacion();

// Para proteger por rol (ejemplo: admin)
// requerirRol(ROLE_ADMIN);
?>
```

### Paso 5: Usar Validación en Formularios
```php
<?php
require_once __DIR__ . '/../config/validacion.php';

// Validar email
if (!esEmailValido($_POST['email'])) {
    echo "Email inválido";
}

// Validar nombre
if (!esNombreValido($_POST['nombre'])) {
    echo "Nombre inválido";
}
?>
```

---

## 🔒 Funciones de Seguridad - Referencia Rápida

| Función | Propósito | Ejemplo |
|---------|----------|---------|
| `requerirAutenticacion()` | Verifica login | `requerirAutenticacion();` |
| `requerirRol(ROLE_ADMIN)` | Verifica rol | `requerirRol(ROLE_ADMIN);` |
| `tieneRol(ROLE_VENDEDOR)` | Condicional | `if(tieneRol(ROLE_VENDEDOR))` |
| `estaAutenticado()` | Verifica sesión | `if(estaAutenticado())` |
| `obtenerUsuarioId()` | Obtiene ID | `$id = obtenerUsuarioId();` |
| `obtenerUsuarioNombre()` | Obtiene nombre | `echo obtenerUsuarioNombre();` |
| `generarCampoCSRF()` | CSRF en forms | `<?php echo generarCampoCSRF();` |

---

## ✔️ Funciones de Validación - Referencia Rápida

| Función | Propósito | Retorno |
|---------|----------|---------|
| `esEmailValido($email)` | Valida email | bool |
| `esNombreValido($nombre)` | Valida nombre | bool |
| `esTelefonoValido($phone)` | Valida teléfono | bool |
| `esNumeroEntero($val, $min, $max)` | Valida número | bool |
| `sanitizarString($val)` | Limpia string | string |
| `sanitizarEmail($email)` | Limpia email | string |
| `obtenerIPCliente()` | IP del cliente | string |

---

## 🔄 Funciones de Redirección - Referencia Rápida

| Función | Propósito | Ejemplo |
|---------|----------|---------|
| `redirigirExito($url, $msg, $tipo)` | Redirige + éxito | `redirigirExito('page.php', '¡Éxito!', 'exito');` |
| `redirigirError($url, $error)` | Redirige + error | `redirigirError('page.php', 'Error');` |
| `redirigirPorRol($rol)` | Según rol | `redirigirPorRol(ROLE_ADMIN);` |
| `redirigirALogin()` | A login | `redirigirALogin();` |
| `mostrarMensajeSesion()` | Muestra msg | `<?php echo mostrarMensajeSesion();` |

---

## 📝 Tabla de Campos - Contacto

```
contactos
├── id (INT, PRIMARY KEY)
├── nombre (VARCHAR 100) ← Requerido
├── email (VARCHAR 100) ← Requerido
├── telefono (VARCHAR 20) ← Opcional
├── asunto (VARCHAR 50) ← Consulta, problema, etc.
├── mensaje (LONGTEXT) ← Requerido, 10-2000 chars
├── usuario_id (INT, FK) ← Si está autenticado
├── ip (VARCHAR 45) ← Capturada automáticamente
├── navegador (TEXT) ← Capturado automáticamente
├── leido (BOOLEAN) ← Default: FALSE
├── respondido (BOOLEAN) ← Default: FALSE
├── fecha_creacion (TIMESTAMP) ← Automática
├── fecha_lectura (TIMESTAMP) ← NULL hasta leer
└── fecha_respuesta (TIMESTAMP) ← NULL hasta responder
```

---

## 🎨 Colores Disponibles (CSS)

```css
/* Primarios */
--primary-dark: #064a13    /* Verde oscuro */
--primary: #0ba227         /* Verde principal */
--primary-light: #25ff04   /* Verde claro */

/* Secundarios */
--secondary-dark: #0837af  /* Azul oscuro */
--secondary: #007bff       /* Azul principal */
--secondary-light: #55b7e5 /* Azul claro */

/* Estados */
--success: #28a745         /* Verde éxito */
--danger: #dc3545          /* Rojo error */
--warning: #ffc107         /* Amarillo aviso */
--info: #17a2b8            /* Azul info */
```

---

## 🧪 Testing Rápido

### Test 1: Página de Contacto
```bash
1. GET /presentacion/contacto.php → Debe cargar
2. Enviar formulario vacío → Error "campos_requeridos"
3. Email inválido → Error "email_invalido"
4. Mensaje corto → Error "mensaje_corto"
5. Completo correcto → Éxito y BD guardado
```

### Test 2: Seguridad
```bash
1. GET /presentacion/admin/admin.php sin login → Redirige a login
2. Login como Comprador → Acceso denegado
3. Login como Admin → Acceso permitido
```

### Test 3: Validación
```bash
Email: test@example.com ✅
Teléfono: +58 (0414) 123-4567 ✅
Nombre: Juan Pérez ✅
```

---

## 📋 Checklist de Verificación

- [ ] Tabla de contactos creada en BD
- [ ] Página contacto.php accesible
- [ ] Formulario valida correctamente
- [ ] Mensajes se guardan en BD
- [ ] Header mejorado con navegación
- [ ] Footer con información completa
- [ ] Tema CSS cargado (colores profesionales)
- [ ] Seguridad: admin protegido
- [ ] Seguridad: CSRF tokens generados
- [ ] Validación: Emails validados
- [ ] Redirecciones: Funcionan sin errores

---

## 🆘 Troubleshooting

### Problema: "Fatal error: Call to undefined function"
**Solución:** Verificar que `require_once` esté correctamente incluido
```php
<?php
require_once __DIR__ . '/../config/seguridad.php';
?>
```

### Problema: Tabla de contactos no existe
**Solución:** Ejecutar script SQL
```bash
mysql -u root venta_ganado < crear_tabla_contactos.sql
```

### Problema: Estilos no cargados
**Solución:** Verificar ruta en header.php
```html
<link rel="stylesheet" href="<?php echo $rootPath; ?>../css/theme.css">
```

### Problema: Redirecciones no funcionan
**Solución:** Asegurar que `session_start()` esté antes que todo
```php
<?php
session_start();
require_once __DIR__ . '/../config/config.php';
?>
```

---

## 📚 Archivos de Referencia

| Archivo | Líneas | Descripción |
|---------|--------|------------|
| `config/seguridad.php` | 400+ | RBAC y autenticación |
| `config/validacion.php` | 350+ | Validación de datos |
| `config/redirecciones.php` | 250+ | Redirecciones seguras |
| `presentacion/contacto.php` | 200+ | Formulario contacto |
| `presentacion/css/theme.css` | 800+ | Tema profesional |
| `datos/dContacto.php` | 400+ | Capa de datos |

---

## 🎓 Mejores Prácticas

### 1. Siempre Validar Entrada
```php
// ❌ Malo
$email = $_POST['email'];

// ✅ Correcto
$email = sanitizarEmail($_POST['email']);
if (!esEmailValido($email)) {
    // Error
}
```

### 2. Usar Constantes de Rol
```php
// ❌ Malo
if ($_SESSION['usuario_tipo'] == 'admin') {}

// ✅ Correcto
if (tieneRol(ROLE_ADMIN)) {}
```

### 3. Proteger Páginas
```php
// Al inicio de cada página protegida
<?php
require_once __DIR__ . '/../config/seguridad.php';
requerirAutenticacion();
requerirRol(ROLE_ADMIN); // Si es solo admin
?>
```

### 4. Generar CSRF en Formularios
```php
<?php echo generarCampoCSRF(); ?>
<!-- O -->
<input type="hidden" name="csrf_token" value="<?php echo obtenerCSRFToken(); ?>">
```

### 5. Verificar CSRF en Handler
```php
if (!verificarCSRFToken($_POST['csrf_token'] ?? '')) {
    redirigirError('formulario.php', 'Token CSRF inválido');
}
```

---

## 🔗 Enlaces Útiles

- Documentación Bootstrap: https://getbootstrap.com/
- Font Awesome: https://fontawesome.com/
- PHP Manual: https://www.php.net/manual/
- OWASP: https://owasp.org/

---

## 📞 Contacto y Soporte

Para más información sobre las mejoras:
1. Ver `RESUMEN_MEJORAS_2025.md`
2. Revisar comentarios en archivos PHP
3. Consultar documentación de uso en cada función

---

**Última actualización:** 2025-01-09
**Versión:** 1.1.0
**Estado:** ✅ Listo para producción
