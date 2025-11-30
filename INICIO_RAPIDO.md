# 🚀 INICIO RÁPIDO - CASOS DE USO

## ⏱️ 60 Segundos para entender todo

**¿Qué es esto?**  
La documentación completa de los 45 casos de uso del sistema de venta de ganado.

**¿Cuánto tiempo?**  
- Lectura mínima: 15 minutos
- Lectura útil: 1-2 horas
- Lectura completa: 4-5 horas

---

## 👉 AHORA MISMO (elige uno)

### 🏃 "Necesito 5 minutos"
1. Lee esta página
2. Abre `CasosDeUso_0_GENERAL.drawio`
3. Listo ✓

### 🚶 "Tengo 15 minutos"  
1. Lee `RESUMEN_EJECUTIVO.md` (solo títulos)
2. Abre `CasosDeUso_0_GENERAL.drawio`
3. Consulta matriz de funcionalidades

### 🎯 "Tengo 1 hora"
1. Lee `RESUMEN_EJECUTIVO.md` (completo)
2. Lee `RESUMEN_ACCIONES_VISUALES.md`
3. Abre diagramas relacionados

### 📚 "Lectura completa"
1. Abre `GUIA_LECTURA.md`
2. Elige tu rol
3. Sigue la ruta recomendada

---

## 📊 LO ESENCIAL EN 10 LÍNEAS

| Aspecto | Descripción |
|--------|------------|
| **¿Qué?** | Sistema web para comprar/vender ganado |
| **Actores** | Vendedor, Comprador, Administrador |
| **Casos de Uso** | 45 casos distribuidos en 7 categorías |
| **Categorías** | Autenticación, Ganado, Compras, Ventas, Usuarios, Reportes, Admin |
| **Diagramas** | 8 archivos DrawIO (incluye 1 general) |
| **Documentos** | 6 archivos Markdown |
| **Flujo Principal** | Vendedor publica → Comprador busca → Compra → Vendedor confirma |
| **Comisión** | 5% automático del precio de venta |
| **Validación** | Datos completos, email único, contraseña 6+ caracteres |
| **Seguridad** | No autocompra, propiedad validada, bcrypt password |

---

## 🎯 ACCESOS RÁPIDOS

### Para Developers
```
1. Lee: RESUMEN_EJECUTIVO.md
2. Abre: CasosDeUso_0_GENERAL.drawio
3. Consulta: CASOS_DE_USO_DOCUMENTACION.md
4. Verifica: RELACIONES_DEPENDENCIAS_CU.md
```

### Para Testers/QA
```
1. Lee: RESUMEN_ACCIONES_VISUALES.md
2. Abre: INDICE_CASOS_USO.md (checklist)
3. Usa: RELACIONES_DEPENDENCIAS_CU.md
4. Crea: Casos de prueba por CU
```

### Para Stakeholders
```
1. Lee: RESUMEN_EJECUTIVO.md
2. Abre: CasosDeUso_0_GENERAL.drawio
3. Revisa: Matriz de funcionalidades
```

### Para Administradores
```
1. Abre: CasosDeUso_7_Administracion.drawio
2. Lee: Sección 7 de CASOS_DE_USO_DOCUMENTACION.md
3. Consulta: CasosDeUso_6_ReportesAnalisis.drawio
```

---

## 📂 ARCHIVOS PRIORITARIOS

### IMPRESCINDIBLES ⭐⭐⭐
- `GUIA_LECTURA.md` - Elige ruta según tu rol
- `CasosDeUso_0_GENERAL.drawio` - Visión completa
- `RESUMEN_EJECUTIVO.md` - Resumen profesional

### MÁS ÚTILES ⭐⭐
- `RESUMEN_ACCIONES_VISUALES.md` - Flujos visuales
- `INDICE_CASOS_USO.md` - Referencia rápida
- `CASOS_DE_USO_DOCUMENTACION.md` - Detalles técnicos

### ESPECIALIZADOS ⭐
- `RELACIONES_DEPENDENCIAS_CU.md` - Para arquitectos
- `ARCHIVO_MANIFEST.md` - Información de archivos

### DIAGRAMAS (8)
- `CasosDeUso_[1-7]_*.drawio` - Por categoría

---

## 🎓 ENTENDER EN 3 PASOS

### Paso 1: Actores (2 min)
El sistema tiene **3 actores principales**:

```
┌─────────────┐    ┌──────────────┐    ┌──────────────┐
│  VENDEDOR   │    │  COMPRADOR   │    │ ADMINISTRADOR│
├─────────────┤    ├──────────────┤    ├──────────────┤
│ Publica     │    │ Busca        │    │ Gestiona     │
│ Vende       │    │ Compra       │    │ Reportes     │
│ Gana comisión│   │ Paga         │    │ Usuarios     │
└─────────────┘    └──────────────┘    └──────────────┘
```

### Paso 2: Categorías (2 min)
**7 categorías de casos de uso**:

```
1. Autenticación (3 CU)         4. Ventas (6 CU)
2. Gestión de Ganado (7 CU)     5. Usuarios (8 CU)
3. Compras (7 CU)               6. Reportes (8 CU)
                                7. Administración (6 CU)
```

### Paso 3: Flujo (1 min)
**Flujo de compra-venta**:

```
Vendedor Registra       Comprador Busca     Venta Completada
    ↓                       ↓                      ↓
Animal en Catálogo  →   Realiza Compra   →  Confirmada/Cancelada
```

---

## ✅ CHECKLIST RÁPIDO

- [ ] Abierto `GUIA_LECTURA.md`
- [ ] Identificado mi rol
- [ ] Leído el resumen ejecutivo
- [ ] Abierto diagrama general
- [ ] Consultado diagrama de mi módulo
- [ ] Listo para trabajar

---

## 🔍 ENCUENTRO RÁPIDO

**Busco:** Casos de uso de...

| Busco | Archivo | Tiempo |
|------|---------|--------|
| Todo | CasosDeUso_0_GENERAL.drawio | 10 min |
| Autenticación | CasosDeUso_1_Autenticacion.drawio | 5 min |
| Publicar ganado | CasosDeUso_2_GestionGanado.drawio | 5 min |
| Comprar ganado | CasosDeUso_3_Compras.drawio | 5 min |
| Confirmar venta | CasosDeUso_4_Ventas.drawio | 5 min |
| Perfiles | CasosDeUso_5_GestionUsuarios.drawio | 5 min |
| Reportes | CasosDeUso_6_ReportesAnalisis.drawio | 5 min |
| Admin | CasosDeUso_7_Administracion.drawio | 5 min |

---

## 💡 TIPS ÚTILES

### Para Developers
→ Empieza con dependencias en `RELACIONES_DEPENDENCIAS_CU.md`  
→ Eso te dice el orden de implementación

### Para Testers
→ Usa checklist en `INDICE_CASOS_USO.md`  
→ Verifica matriz de funcionalidades en `RESUMEN_EJECUTIVO.md`

### Para Stakeholders
→ Comparte `RESUMEN_EJECUTIVO.md`  
→ Usa `CasosDeUso_0_GENERAL.drawio` en presentaciones

### Para Todos
→ Abre diagramas en: https://draw.io/app  
→ Consulta `GUIA_LECTURA.md` si no sabes por dónde empezar

---

## 📞 PROBLEMAS RÁPIDOS

**P: ¿Por dónde empiezo?**  
→ `GUIA_LECTURA.md` → elige tu rol

**P: ¿Cuántos casos de uso hay?**  
→ 45 distribuidos en 7 categorías

**P: ¿Quiénes son los actores?**  
→ Vendedor, Comprador, Administrador (+ Sistema)

**P: ¿Cómo abro los diagramas?**  
→ https://draw.io/app → Cargar archivo

**P: ¿Qué leer primero?**  
→ `RESUMEN_EJECUTIVO.md` (20 minutos)

**P: ¿Y luego?**  
→ Abre diagrama relacionado a lo que necesites

**P: ¿Dónde están los archivos?**  
→ `c:\xampp\htdocs\sistema_ganado_septiembre\`

---

## 🎯 ACCIÓN INMEDIATA

### Opción A (2 minutos)
```
1. Abre: CasosDeUso_0_GENERAL.drawio
2. Entiendes: Todos los 45 CU
3. Listo: Tienes visión general
```

### Opción B (15 minutos)
```
1. Lee: RESUMEN_EJECUTIVO.md
2. Abre: CasosDeUso_0_GENERAL.drawio
3. Consulta: Matriz de funcionalidades
4. Resultado: Entiendes sistema completo
```

### Opción C (1 hora)
```
1. Lee: RESUMEN_EJECUTIVO.md
2. Lee: RESUMEN_ACCIONES_VISUALES.md
3. Abre: Diagramas relevantes
4. Resultado: Conocimiento profundo
```

---

## 🚀 COMIENZA AQUÍ

```
┌─────────────────────────────────────────────────┐
│                                                 │
│  1. Lee esta página (5 min)                    │
│                                                 │
│  2. Abre: GUIA_LECTURA.md                      │
│                                                 │
│  3. Elige tu rol (1 min)                       │
│                                                 │
│  4. Sigue la ruta (variable)                   │
│                                                 │
│  5. Profundiza según necesidad                 │
│                                                 │
│  ¡Listo! 🎉                                    │
│                                                 │
└─────────────────────────────────────────────────┘
```

---

## 📊 RESUMEN FINAL

| Métrica | Valor |
|---------|-------|
| Casos de Uso | 45 |
| Categorías | 7 |
| Actores | 3 principales |
| Diagramas | 8 |
| Documentos | 6 |
| Archivos Totales | 14 |
| Cobertura | 100% |
| Tiempo lectura mín. | 15 minutos |
| Tiempo lectura máx. | 5 horas |

---

## ✨ BENEFICIOS

✅ Documentación completa  
✅ Múltiples perspectivas  
✅ Personalizado por rol  
✅ Diagramas profesionales  
✅ Fácil de navegar  
✅ Listo para usar  
✅ Sin ambigüedades  
✅ Mantenible  

---

**¡Ahora abre `GUIA_LECTURA.md` y elige tu camino! 🚀**

Generado: 23 de Noviembre de 2025
