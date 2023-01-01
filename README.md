<div align="center" id="top"> 
  <img src="public/admin/img/android-chrome-192x192.png" alt="Control_asistencias" />

  &#xa0;

  <!-- <a href="https://control_asistencias.netlify.app">Demo</a> -->
</div>

<h1 align="center">Control asistencias</h1>

<p align="center">
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
  <img alt="Github top language" src="https://img.shields.io/github/languages/top/manuelcastro95/control_asistencias?color=56BEB8">
  <img alt="Github stars" src="https://img.shields.io/github/stars/manuelcastro95/control_asistencias?color=56BEB8" />
</p>

<!-- Status -->

<!-- <h4 align="center"> 
	🚧  Control_asistencias 🚀 Under construction...  🚧
</h4> 

<hr> -->

<p align="center">
  <a href="#about-project">About</a> &#xa0; | &#xa0; 
  <a href="#technologies">Technologies</a> &#xa0; | &#xa0;
  <a href="#requirements">Requirements</a> &#xa0; | &#xa0;
  <a href="#starting">Starting</a> &#xa0; | &#xa0;
  <a href="#license">License</a> &#xa0; | &#xa0;
  <a href="https://github.com/manuelcastro95" target="_blank">Author</a>
</p>

<br>

## About Project
<p class="text-justify">
System for student attendance control by means of QR code, assigned to each student who is registered on the platform by the administrator, using the webcam to read the QR code.
</p>


## Technologies

The following tools were used in this project:


* [![Laravel][Laravel.com]][Laravel-url]
* [![Bootstrap][Bootstrap.com]][Bootstrap-url]
* [![JQuery][JQuery.com]][JQuery-url]

## Requirements

Before starting, you need to have [Git](https://git-scm.com) and [Node](https://nodejs.org/en/) installed.

* PHP 8.1.10
* MYSQL

## Starting 

```bash
# Clone this project
$ git clone https://github.com/manuelcastro95/control_asistencias

# Access
$ cd control_asistencias

# Install dependencies
$ composer install
$ npm install
$ npm run dev

#create migrations and initial user
$ php artisan migrate --seed

$ php artisan serve
# The server will initialize in the <http://localhost:8000>

#user email and password
admin@admin.com
control1234
```

##  License

This project is under license from MIT. For more details, see the [LICENSE](LICENSE.md) file.


Made with :heart: by <a href="https://github.com/manuelcastro95" target="_blank">Manuel Castro</a>

&#xa0;

<a href="#top">Back to top</a>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com 