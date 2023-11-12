# Attendance System

This project is a simple web application designed to help users track their work schedules. The application is built using HTML, JavaScript, and CSS, with the option to use PHP for server-side functionalities. The recommended website appearance is kept minimalistic and clean, with a focus on ease of use.

## Getting Started

### Installing

1. Install all dependencies
```
composer install
```
2. Copy .env.example to .env for configuration database
```
cp .env.example .env
```

3. Generate a key for connect to MYSQL make sure create a database first
```
php artisan key:generate
```

4. Migrate all the table and seed
```
php artisan migrate:fresh --seed
```

4. Run the program
```
php artisan serve
```

### Executing program

* Login use the seed user or can add new one at the register section
```
email: osman@gmail.com
password: password
```

## Authors

Contributors names and contact info
[@syhrulhkim](https://www.linkedin.com/in/muhammad-syahrul-hakim-322100192/)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
