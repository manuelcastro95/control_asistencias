# 📸 Guía para Agregar Capturas de Pantalla

## ✅ Sí, puedes incluir capturas en tu repositorio

Es una **excelente práctica** incluir capturas de pantalla en tu repositorio porque:
- Muestran visualmente las funcionalidades del sistema
- Ayudan a otros desarrolladores a entender el proyecto
- Mejoran la presentación del README
- Facilitan la documentación visual

## 📁 Ubicación Recomendada

### Para el Proyecto Laravel (Web)
```
docs/screenshots/
├── dashboard.png
├── alumnos-lista.png
├── alumnos-formulario.png
├── asistencias-tabla.png
└── configuracion/
    ├── instituciones.png
    ├── sedes.png
    └── grados.png
```

### Para el Proyecto Flutter (App Móvil)
```
screenshots/
├── login.png
├── dashboard-estudiante.png
├── perfil-estudiante.png
├── qr-estudiante.png
├── asistencias-estudiante.png
└── dashboard-admin.png
```

## 🎨 Recomendaciones

### Formato y Tamaño
- **Formato**: PNG (mejor calidad) o JPG (menor tamaño)
- **Tamaño**: Máximo 1920x1080px (Full HD)
- **Peso**: Optimiza las imágenes (< 500KB cada una)
- **Resolución**: 72-96 DPI es suficiente para web

### Contenido
- Muestra las funcionalidades principales
- Usa datos de ejemplo (no datos reales)
- Oculta información sensible si es necesario
- Captura estados importantes (vacío, con datos, errores)

### Nombres de Archivos
Usa nombres descriptivos y consistentes:
- ✅ `dashboard-principal.png`
- ✅ `alumnos-lista-filtrada.png`
- ✅ `login-estudiante.png`
- ❌ `captura1.png`
- ❌ `screenshot_2024.png`

## 📝 Cómo Agregar al README

### Opción 1: Imagen Simple
```markdown
![Dashboard Principal](docs/screenshots/dashboard.png)
```

### Opción 2: Con Tamaño Específico
```markdown
<img src="docs/screenshots/dashboard.png" alt="Dashboard Principal" width="800"/>
```

### Opción 3: Galería Organizada
```markdown
<div align="center">
  <h3>Dashboard Principal</h3>
  <img src="docs/screenshots/dashboard.png" alt="Dashboard" width="800"/>
  
  <h3>Gestión de Alumnos</h3>
  <img src="docs/screenshots/alumnos-lista.png" alt="Alumnos" width="800"/>
</div>
```

## 🔧 Optimización de Imágenes

### Herramientas Recomendadas
- **TinyPNG**: https://tinypng.com/ (online)
- **ImageOptim**: https://imageoptim.com/ (Mac)
- **Squoosh**: https://squoosh.app/ (online)
- **GIMP**: https://www.gimp.org/ (gratis, multiplataforma)

### Comando para Redimensionar (ImageMagick)
```bash
# Redimensionar a 1920px de ancho
convert imagen.png -resize 1920x imagen-optimizada.png

# Comprimir PNG
pngquant --quality=65-80 imagen.png
```

## ✅ Checklist Antes de Commitear

- [ ] Imágenes optimizadas (< 500KB cada una)
- [ ] Nombres descriptivos y consistentes
- [ ] Sin datos sensibles visibles
- [ ] Referencias actualizadas en README
- [ ] Organizadas en carpetas si es necesario
- [ ] Verificado que no estén en `.gitignore`

## 🚫 Qué NO Incluir

- ❌ Capturas con datos personales reales
- ❌ Imágenes muy pesadas (> 1MB)
- ❌ Capturas de errores de desarrollo
- ❌ Información sensible (passwords, tokens, etc.)
- ❌ Imágenes sin optimizar

## 📦 Ejemplo de Estructura Completa

```
control_asistencias/
├── docs/
│   └── screenshots/
│       ├── web/
│       │   ├── dashboard.png
│       │   ├── alumnos/
│       │   │   ├── lista.png
│       │   │   └── formulario.png
│       │   └── asistencias/
│       │       └── tabla.png
│       └── mobile/
│           ├── login.png
│           └── dashboard.png
└── README.md
```

## 🔗 Referencias en README

Ya he actualizado el README principal para incluir una sección de capturas. Solo necesitas:

1. Agregar tus capturas en `docs/screenshots/`
2. Actualizar las referencias en el README si cambias los nombres
3. Hacer commit de las imágenes

## 💡 Tips Adicionales

1. **Usa herramientas de captura**:
   - Windows: `Win + Shift + S` (Snipping Tool)
   - Mac: `Cmd + Shift + 4`
   - Linux: `Print Screen` o herramientas como Flameshot

2. **Mantén consistencia**:
   - Mismo tamaño de ventana
   - Mismo tema/navegador
   - Mismo estilo de datos de ejemplo

3. **Actualiza regularmente**:
   - Cuando agregues nuevas funcionalidades
   - Cuando cambies el diseño
   - Cuando actualices la versión

---

**✅ Las capturas ya están configuradas y listas para usar!**

