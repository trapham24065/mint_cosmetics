<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# project

mint cosmetics - sales website

# project description

It is a project carried out for the purpose of learning while still a student.

# tester

- Truong Thuy Linh
- Tran Thi Mai

# developer

- Pham Van Tra

# automatic product deletion process for status pending over 15 minutes

Setting up Cron Job (On Real Server)
For Scheduler to work, you need to add a Cron line to your server (Linux).

1. Open terminal server.
2. Type `crontab -e`.
3. Add the following line at the end of the file:
   cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   *(Replace `/path-to-your-project` with the actual path to your Laravel project directory)*.

Running Test (On Local/Docker)
On local machine or Docker, you do not need to install a real Cron Job. You can run the following command in the
terminal to simulate
running the Scheduler:

Run in the container workspace
php artisan schedule:work
This command will keep the terminal open and automatically trigger scheduled tasks every minute, allowing you to test
whether the order deletion
function is working as expected (orders older than 15 minutes will be deleted every minute).
