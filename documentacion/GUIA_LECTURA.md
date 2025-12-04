# 📚 GUÍA DE LECTURA - CASOS DE USO DEL SISTEMA

## ¿Por dónde empezar?

Dependiendo de tu rol y necesidades, sigue esta guía de lectura recomendada.

---

## 👨‍💻 PARA DESARROLLADORES

### Lectura Rápida (30 minutos)
1. 📖 **RESUMEN_EJECUTIVO.md** (este proyecto)
   - Visión general del sistema
   - Actores y sus roles
   - Estadísticas clave

2. 🎯 **CasosDeUso_0_GENERAL.drawio**
   - Visualización completa del sistema
   - Todos los actores interconectados
   - Relación entre componentes

### Lectura Detallada (2-3 horas)
3. 📊 **RESUMEN_ACCIONES_VISUALES.md**
   - Categorías de casos de uso
   - Flujos de procesos principales
   - Reglas de negocio aplicadas

4. 📝 **CASOS_DE_USO_DOCUMENTACION.md**
   - Documentación completa de los 45 CU
   - Precondiciones y postcondiciones
   - Datos involucrados en cada CU

5. 🔗 **RELACIONES_DEPENDENCIAS_CU.md**
   - Dependencias entre casos de uso
   - Cadenas críticas
   - Matriz de inclusiones

### Lectura Especializada (Por Feature)
Según lo que vayas a implementar, consulta:
- Autenticación → **CasosDeUso_1_Autenticacion.drawio**
- Gestión de Ganado → **CasosDeUso_2_GestionGanado.drawio**
- Compras → **CasosDeUso_3_Compras.drawio**
- Ventas → **CasosDeUso_4_Ventas.drawio**
- Usuarios → **CasosDeUso_5_GestionUsuarios.drawio**
- Reportes → **CasosDeUso_6_ReportesAnalisis.drawio**
- Admin → **CasosDeUso_7_Administracion.drawio**

---

## 🧪 PARA TESTERS/QA

### Lectura Rápida (45 minutos)
1. 📖 **RESUMEN_EJECUTIVO.md**
   - Entender la propuesta del sistema
   - Ver matriz de funcionalidades

2. 📊 **RESUMEN_ACCIONES_VISUALES.md**
   - Flujos de procesos clave
   - Secuencia de eventos típicos
   - Reglas de negocio

### Lectura Detallada (2 horas)
3. 🔗 **RELACIONES_DEPENDENCIAS_CU.md**
   - Entiende las dependencias
   - Secuencias típicas de eventos
   - Casos especiales

4. 📋 **INDICE_CASOS_USO.md**
   - Checklist de implementación
   - Cómo verificar cada CU
   - Archivo relacionado

### Creación de Casos de Prueba
Para cada CU, consulta:
- **CASOS_DE_USO_DOCUMENTACION.md** → Sección específica del CU
- Extrae: Precondiciones, pasos, postcondiciones
- Crea: Casos de prueba positivos y negativos

---

## 👨‍💼 PARA STAKEHOLDERS/CLIENTES

### Lectura Esencial (15 minutos)
1. 📖 **RESUMEN_EJECUTIVO.md**
   - Qué hace el sistema
   - Quién lo usa
   - Características principales

2. 🎯 **CasosDeUso_0_GENERAL.drawio**
   - Visualización completa
   - Tres actores y sus interacciones

### Lectura Opcional (30 minutos)
3. 📊 **RESUMEN_ACCIONES_VISUALES.md**
   - Flujo de compra
   - Flujo de venta
   - Matriz de funcionalidades

---

## 🔐 PARA ADMINISTRADORES/SYSADMIN

### Lectura Prioritaria (1 hora)
1. 📖 **RESUMEN_EJECUTIVO.md** (solo sección "Para Administradores")

2. 🎯 **CasosDeUso_7_Administracion.drawio**
   - Todos los CU administrativos

3. 📝 **CASOS_DE_USO_DOCUMENTACION.md** → Sección 7
   - Ver Estructura de Tablas
   - Mantenimiento del Sistema
   - Gestión de Permisos
   - Auditoría

### Lectura Complementaria (30 minutos)
4. 📊 **RESUMEN_ACCIONES_VISUALES.md**
   - Reglas de negocio
   - Archivos del proyecto relacionados

---

## 📊 PARA PROJECT MANAGERS

### Lectura Esencial (1 hora)
1. 📖 **RESUMEN_EJECUTIVO.md**
   - Estadísticas clave
   - Visión del proyecto

2. 📋 **INDICE_CASOS_USO.md**
   - Estructura de entrega
   - Archivos generados
   - Checklist de implementación

### Lectura Operativa (30 minutos)
3. 🔗 **RELACIONES_DEPENDENCIAS_CU.md**
   - Dependencias críticas
   - Orden de desarrollo
   - Cadenas de impacto

---

## 🎓 PARA ARQUITECTOS DE SOFTWARE

### Lectura Completa (3 horas)
1. 📖 **RESUMEN_EJECUTIVO.md**
2. 🎯 **CasosDeUso_0_GENERAL.drawio**
3. 📝 **CASOS_DE_USO_DOCUMENTACION.md**
4. 🔗 **RELACIONES_DEPENDENCIAS_CU.md**
5. 📊 **RESUMEN_ACCIONES_VISUALES.md**

### Análisis Profundo
6. Todos los diagramas individuales
7. Identificar patrones de integración
8. Definir interfaces entre módulos

---

## 📚 LECTURA POR OBJETIVO

### Objetivo: "Quiero entender TODO el sistema"
**Tiempo**: 4-5 horas

1. RESUMEN_EJECUTIVO.md
2. CasosDeUso_0_GENERAL.drawio
3. Todos los 7 diagramas específicos
4. CASOS_DE_USO_DOCUMENTACION.md
5. RELACIONES_DEPENDENCIAS_CU.md
6. RESUMEN_ACCIONES_VISUALES.md
7. INDICE_CASOS_USO.md

---

### Objetivo: "Quiero implementar una característica X"
**Tiempo**: 1-2 horas por característica

**Pasos:**
1. Busca la categoría en **INDICE_CASOS_USO.md**
2. Abre el diagrama DrawIO correspondiente
3. Lee la sección en **CASOS_DE_USO_DOCUMENTACION.md**
4. Consulta **RELACIONES_DEPENDENCIAS_CU.md** para dependencias
5. Localiza los archivos PHP del proyecto

**Ejemplo para "Publicar Ganado":**
- Categoría: Gestión de Ganado
- Diagrama: `CasosDeUso_2_GestionGanado.drawio`
- Documentación: Sección 2 de `CASOS_DE_USO_DOCUMENTACION.md`
- Dependencias: Ver en `RELACIONES_DEPENDENCIAS_CU.md` → Gestión de Ganado

---

### Objetivo: "Quiero crear casos de prueba"
**Tiempo**: 3-4 horas

1. Lee **RESUMEN_ACCIONES_VISUALES.md** completo
2. Para cada CU:
   - Consulta **CASOS_DE_USO_DOCUMENTACION.md**
   - Extrae: Precondiciones, pasos, postcondiciones
   - Crea: Caso de prueba con datos de entrada/salida
3. Consulta **RELACIONES_DEPENDENCIAS_CU.md** para casos de prueba de secuencias
4. Verifica matriz de funcionalidades en **RESUMEN_EJECUTIVO.md**

---

### Objetivo: "Debo hacer mantenimiento del sistema"
**Tiempo**: 1 hora

1. Lee sección "Para Administradores" en **RESUMEN_EJECUTIVO.md**
2. Abre **CasosDeUso_7_Administracion.drawio**
3. Consulta Caso de Uso específico en **CASOS_DE_USO_DOCUMENTACION.md** Sección 7
4. Ubica archivos en **RESUMEN_ACCIONES_VISUALES.md** sección "Archivos del Proyecto Relacionados"

---

## ⏱️ PLANES DE LECTURA POR TIEMPO DISPONIBLE

### Si tienes 15 minutos
1. RESUMEN_EJECUTIVO.md (completo)

### Si tienes 30 minutos
1. RESUMEN_EJECUTIVO.md
2. CasosDeUso_0_GENERAL.drawio

### Si tienes 1 hora
1. RESUMEN_EJECUTIVO.md
2. CasosDeUso_0_GENERAL.drawio
3. RESUMEN_ACCIONES_VISUALES.md (solo secciones 1-2)

### Si tienes 2 horas
1. RESUMEN_EJECUTIVO.md
2. CasosDeUso_0_GENERAL.drawio
3. RESUMEN_ACCIONES_VISUALES.md (completo)
4. INDICE_CASOS_USO.md

### Si tienes 4 horas
1. Todo lo anterior +
2. CASOS_DE_USO_DOCUMENTACION.md (completo)
3. Primeros 2 diagramas específicos

### Si tienes más de 4 horas
Consulta el plan completo según tu rol (arriba)

---

## 🎯 BÚSQUEDA RÁPIDA

### "Necesito información sobre COMPRAS"
1. INDICE_CASOS_USO.md → Sección "3. COMPRAS (7 Casos)"
2. CasosDeUso_3_Compras.drawio
3. CASOS_DE_USO_DOCUMENTACION.md → CASO DE USO 3: COMPRAS
4. RELACIONES_DEPENDENCIAS_CU.md → Sección "PROCESO DE COMPRA"

### "Necesito información sobre AUTENTICACIÓN"
1. INDICE_CASOS_USO.md → Sección "1. AUTENTICACIÓN (3 Casos)"
2. CasosDeUso_1_Autenticacion.drawio
3. CASOS_DE_USO_DOCUMENTACION.md → CASO DE USO 1: AUTENTICACIÓN
4. RELACIONES_DEPENDENCIAS_CU.md → Sección "AUTENTICACIÓN"

### "Necesito ver FLUJOS"
1. RESUMEN_ACCIONES_VISUALES.md → Sección "FLUJOS DE PROCESOS CLAVE"
2. RELACIONES_DEPENDENCIAS_CU.md → Sección "SECUENCIA TÍPICA DE EVENTOS"

### "Necesito ver DEPENDENCIAS"
1. RELACIONES_DEPENDENCIAS_CU.md (documento completo)
2. INDICE_CASOS_USO.md → Sección "CHECKLIST"
3. RESUMEN_ACCIONES_VISUALES.md → Sección "ESTADÍSTICAS"

---

## 💡 CONSEJOS DE LECTURA

### Para entender el flujo completo
1. Empieza con **CasosDeUso_0_GENERAL.drawio**
2. Luego lee **RESUMEN_ACCIONES_VISUALES.md** sección flujos
3. Sigue con diagrama específico de tu interés
4. Consulta documentación detallada

### Para implementación
1. Lee precondiciones y postcondiciones primero
2. Identifica dependencias usando **RELACIONES_DEPENDENCIAS_CU.md**
3. Planifica el orden de desarrollo
4. Implementa según dependencias

### Para testing
1. Entiende el flujo normal
2. Identifica flujos alternativos en precondiciones/postcondiciones
3. Crea casos para comportamiento positivo y negativo
4. Verifica usando matriz de funcionalidades

### Para documentación
1. Consulta el documento principal correspondiente
2. Vuelca información en tu propio formato
3. Agrega contexto específico de tu empresa
4. Mantén sincronizado

---

## 🔗 ESTRUCTURA RECOMENDADA DE LECTURA

```
PRINCIPIANTE                    INTERMEDIO                  EXPERTO
────────────                    ───────────                  ──────
RESUMEN_EJECUTIVO.md     →      CASOS_DE_USO_DOCUMENTACION  → RELACIONES_DEPENDENCIAS_CU.md
         ↓                               ↓                           ↓
CasosDeUso_0_GENERAL     →      CasosDeUso_[1-7]_*.drawio   → Todos los diagramas
         ↓                               ↓                           ↓
RESUMEN_ACCIONES         →      INDICE_CASOS_USO.md         → Análisis integrado
                                        ↓
                                Casos específicos por feature
```

---

## 📞 DÓNDE ENCONTRAR INFORMACIÓN

| Necesito saber... | Archivo | Sección |
|------------------|---------|---------|
| Qué hace el sistema | RESUMEN_EJECUTIVO.md | Visión Rápida |
| Todos los CU | CasosDeUso_0_GENERAL.drawio | - |
| CU de una categoría | INDICE_CASOS_USO.md | Por Categoría |
| Detalles de un CU | CASOS_DE_USO_DOCUMENTACION.md | Sección específica |
| Flujos de proceso | RESUMEN_ACCIONES_VISUALES.md | Flujos Principales |
| Dependencias | RELACIONES_DEPENDENCIAS_CU.md | Relaciones Detalladas |
| Matriz completa | RESUMEN_EJECUTIVO.md | Matriz de Funcionalidades |
| Checklist | INDICE_CASOS_USO.md | Checklist |
| Archivos relacionados | RESUMEN_ACCIONES_VISUALES.md | Archivos del Proyecto |

---

## ✅ VERIFICACIÓN DE COMPRENSIÓN

### Después de leer, deberías poder responder:

**Nivel Básico:**
- ¿Cuáles son los 3 actores principales?
- ¿Cuántos casos de uso hay en total?
- ¿Cuáles son las 7 categorías?

**Nivel Intermedio:**
- ¿Qué hace un Vendedor en el sistema?
- ¿Cuál es el flujo de compra?
- ¿Qué validaciones se aplican?

**Nivel Avanzado:**
- ¿Qué dependencias tiene Confirmar Venta?
- ¿Qué sucede cuando se cancela una compra?
- ¿Cuál es el orden de implementación recomendado?

---

## 📋 RESUMEN FINAL

**Total de documentación**: 5 archivos Markdown + 8 diagramas DrawIO  
**Tiempo total de lectura**: 4-5 horas para lectura completa  
**Lectura mínima recomendada**: 1-2 horas  
**Por rol**: 30 minutos - 2 horas según necesidad  

Esta documentación está diseñada para ser:
- ✅ **Modular**: Puedes leer solo lo que necesites
- ✅ **Iterativa**: Vuelve cuando necesites más detalle
- ✅ **Práctica**: Enfocada en lo que necesitas hacer
- ✅ **Completa**: Cubre todos los aspectos del sistema

---

**¡Listo para empezar! Elige tu rol arriba y comienza tu lectura. 🚀**

Generado: 23 de Noviembre de 2025
