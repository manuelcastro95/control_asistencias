<div align="center" id="top"> 
  <img src="public/admin/img/android-chrome-192x192.png" alt="Control_asistencias" />

  &#xa0;

  <!-- <a href="https://control_asistencias.netlify.app">Demo</a> -->
</div>

<h1 align="center">Control Asistencias</h1>

<p align="center">
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
  <img alt="Github top language" src="https://img.shields.io/github/languages/top/manuelcastro95/control_asistencias?color=56BEB8">
  <img alt="Github stars" src="https://img.shields.io/github/stars/manuelcastro95/control_asistencias?color=56BEB8" />
</p>

<p align="center">
  <a href="#about-project">About</a> &#xa0; | &#xa0; 
  <a href="#features">Features</a> &#xa0; | &#xa0;
  <a href="#technologies">Technologies</a> &#xa0; | &#xa0;
  <a href="#requirements">Requirements</a> &#xa0; | &#xa0;
  <a href="#starting">Starting</a> &#xa0; | &#xa0;
  <a href="#api">API</a> &#xa0; | &#xa0;
  <a href="#license">License</a> &#xa0; | &#xa0;
  <a href="https://github.com/manuelcastro95" target="_blank">Author</a>
</p>

<br>

## 📋 About Project

Sistema completo de control de asistencias estudiantiles mediante códigos QR. Permite gestionar instituciones educativas, sedes, grados, alumnos y sus asistencias con un sistema robusto y moderno.

### Características Principales

- ✅ **Gestión Completa**: Instituciones, sedes, grados y alumnos
- ✅ **Registro de Asistencias**: Mediante escáner QR o manual
- ✅ **Dashboard Interactivo**: Estadísticas y gráficos en tiempo real
- ✅ **API REST Completa**: Para integración con aplicaciones móviles
- ✅ **Autenticación**: Sistema de login para estudiantes y administradores
- ✅ **Importación Masiva**: Carga de alumnos desde archivos Excel
- ✅ **Interfaz Moderna**: Diseño responsive con Bootstrap 5
- ✅ **Optimización**: Paginación server-side y consultas optimizadas

## 🚀 Features

### Para Administradores
- Dashboard con estadísticas generales
- Gestión de instituciones y sedes
- Administración de grados académicos
- CRUD completo de alumnos
- Registro y consulta de asistencias
- Importación masiva desde Excel
- Generación de códigos QR de alta calidad
- Filtros avanzados y búsqueda

### Para Estudiantes (App Móvil)
- Login con código y contraseña
- Visualización de perfil completo
- Código QR personal para asistencia
- Consulta de asistencias históricas
- Estadísticas personales

## 🛠️ Technologies

The following tools were used in this project:

* [![Laravel][Laravel.com]][Laravel-url] - Framework PHP
* [![Bootstrap][Bootstrap.com]][Bootstrap-url] - Framework CSS
* [![JQuery][JQuery.com]][JQuery-url] - JavaScript Library
* **Chart.js** - Gráficos y visualizaciones
* **DataTables** - Tablas interactivas con paginación server-side
* **Instascan** - Escáner de códigos QR
* **SweetAlert2** - Alertas modernas
* **Laravel Sanctum** - Autenticación API
* **Maatwebsite Excel** - Importación/Exportación Excel
* **Simple QR Code** - Generación de códigos QR

## 📋 Requirements

Before starting, you need to have [Git](https://git-scm.com) and [Node](https://nodejs.org/en/) installed.

* PHP 8.1.10 o superior
* MySQL 5.7 o superior
* Composer
* Node.js y NPM

## 🚀 Starting 

```bash
# Clone this project
$ git clone https://github.com/manuelcastro95/control_asistencias

# Access
$ cd control_asistencias

# Install dependencies
$ composer install
$ npm install
$ npm run dev

# Configure environment
$ cp .env.example .env
$ php artisan key:generate

# Configure database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=control_asistencias
DB_USERNAME=root
DB_PASSWORD=

# Run migrations and seeders
$ php artisan migrate --seed

# Start server
$ php artisan serve --host=0.0.0.0 --port=8000
# The server will initialize in the <http://localhost:8000>

# Default credentials
Email: admin@admin.com
Password: control1234
```

## 📱 API REST

El sistema incluye una API REST completa para integración con aplicaciones móviles.

### Autenticación

**Login Administrador:**
```http
POST /api/admin/login
Content-Type: application/json

{
  "email": "admin@admin.com",
  "password": "control1234"
}
```

**Login Estudiante:**
```http
POST /api/alumno/login
Content-Type: application/json

{
  "codigo": "COLSP01001",
  "password": "COLSP01001"
}
```

### Endpoints Principales

- `GET /api/admin/instituciones` - Listar instituciones
- `GET /api/admin/grados` - Listar grados
- `GET /api/admin/alumnos` - Listar alumnos
- `GET /api/admin/asistencias` - Listar asistencias
- `GET /api/admin/dashboard/estadisticas` - Estadísticas del dashboard
- `GET /api/alumno/perfil` - Perfil del estudiante
- `GET /api/alumno/qr` - Código QR del estudiante
- `GET /api/alumno/asistencias` - Asistencias del estudiante
- `GET /api/alumno/estadisticas` - Estadísticas del estudiante

### Documentación Completa

Ver archivos de documentación en el proyecto:
- `API_AUTENTICACION.md` - Documentación completa de autenticación
- `Control_Asistencias_API.postman_collection.json` - Colección de Postman

## 📊 Estructura de Base de Datos

- **instituciones** - Instituciones educativas
- **sedes** - Sedes de las instituciones
- **grados** - Grados académicos
- **alumnos** - Estudiantes registrados
- **asistencias** - Registro de asistencias (con fecha y hora)
- **users** - Usuarios administradores

## 🔐 Seguridad

- Autenticación con Laravel Sanctum
- Validación de datos con Form Requests
- Protección CSRF
- Rate limiting en APIs
- Soft deletes para recuperación de datos
- Logs y auditoría de acciones importantes

## 📈 Optimizaciones

- Paginación server-side en tablas grandes
- Índices en base de datos para consultas rápidas
- Caché de consultas frecuentes
- Lazy loading de relaciones
- Consultas optimizadas con joins

## 🎨 Interfaz

- Diseño moderno y responsive
- Gradientes y animaciones suaves
- Tablas interactivas con DataTables
- Gráficos con Chart.js
- Alertas con SweetAlert2
- Tema consistente en toda la aplicación

## 📝 Notas Importantes

1. **Códigos QR**: Se generan automáticamente al crear un alumno
2. **Contraseñas**: Los estudiantes tienen como contraseña inicial su código
3. **Asistencias**: Se registran con fecha y hora automáticamente
4. **Importación**: El formato Excel debe seguir la plantilla proporcionada

## 🤝 Contribuir

Las contribuciones son bienvenidas! Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 License

This project is under license from MIT. For more details, see the [LICENSE](LICENSE.md) file.

Made with :heart: by <a href="https://github.com/manuelcastro95" target="_blank">Manuel Castro</a>

&#xa0;

<a href="#top">Back to top</a>

[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com
