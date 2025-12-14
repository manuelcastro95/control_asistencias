# 📸 Capturas de Pantalla

Esta carpeta contiene las capturas de pantalla del sistema de Control de Asistencias.

## 📁 Estructura Recomendada

```
docs/screenshots/
├── web/              # Capturas de la aplicación web
│   ├── dashboard/
│   ├── alumnos/
│   ├── asistencias/
│   └── configuracion/
├── mobile/           # Capturas de la app móvil
│   ├── login/
│   ├── estudiante/
│   └── admin/
└── api/              # Capturas de Postman o documentación API
```

## 📝 Convenciones de Nombres

Usa nombres descriptivos y consistentes:

- `dashboard-principal.png`
- `alumnos-lista.png`
- `alumnos-formulario.png`
- `asistencias-tabla.png`
- `login-estudiante.png`
- `qr-estudiante.png`

## 🎨 Recomendaciones

1. **Formato**: PNG o JPG (PNG para mejor calidad)
2. **Tamaño**: Máximo 1920x1080px (Full HD)
3. **Peso**: Optimiza las imágenes (< 500KB cada una)
4. **Contenido**: Muestra las funcionalidades principales
5. **Privacidad**: Oculta datos sensibles si es necesario

## 🔗 Uso en README

Para incluir capturas en el README:

```markdown
![Descripción](docs/screenshots/nombre-imagen.png)
```

O con tamaño específico:

```markdown
<img src="docs/screenshots/nombre-imagen.png" alt="Descripción" width="800"/>
```

## ✅ Checklist antes de commitear

- [ ] Imágenes optimizadas (< 500KB)
- [ ] Nombres descriptivos
- [ ] Sin datos sensibles visibles
- [ ] Referencias actualizadas en README
- [ ] Organizadas en carpetas si es necesario

