# byte5 Laravel x Next.JS Makeathon

Welcome to the byte5 Makeathon backend API built with Laravel. This API powers the Next.JS frontend and provides all basic endpoints for the makeathon application.
Feel free to extend this project to suit your feature ideas.

## About

This is a Laravel-based API that serves as the backend for the byte5 Makeathon. It provides RESTful endpoints, authentication, and database management for the makeathon platform.

## Local Installation

You can run this application locally using either Laravel Sail (Docker) or Laravel Herd. Choose the method that best suits your development environment.

### Option 1: Using Laravel Sail (Docker)

Laravel Sail provides a Docker-based development environment. Make sure you have Docker installed on your system.

we are now feature/santas

```bash
# Clone the repository
git clone <repository-url>
cd byte5-makeathon-backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Start Sail
./vendor/bin/sail up -d

# Generate application key
./vendor/bin/sail artisan key:generate

# Run migrations and seeders
./vendor/bin/sail artisan migrate --seed

# The application will be available at http://localhost
```

### Option 2: Using Laravel Herd

Laravel Herd provides a native PHP development environment for macOS.

```bash
# Clone the repository
git clone <repository-url>
cd byte5-makeathon-backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# Then run migrations and seeders
php artisan migrate --seed

# Herd will automatically serve your application
# Access it via the Herd-configured domain
```

## Preview Environments

This project uses Laravel Cloud for automated preview environments. When you create a pull request against the `main` branch:

- A new preview environment is automatically created
- The environment is automatically migrated using `php artisan migrate`
- Database seeders are run to populate test data
- You'll receive a unique URL to access your preview environment which is shown within the PR
- The preview environment is automatically updated with each push to the PR branch
- The environment is automatically destroyed when the PR is closed

This allows you to test your changes in an isolated environment.

## Development

### Seeded Users
There are default users created during the seeder process. You can access them via the following credentials:
```
admin@example.com - password
santa@example.com - password
elf@example.com - password
```

### Running Tests

```bash
# With Sail
./vendor/bin/sail artisan test

# With Herd
php artisan test
```

### Regnerating API Documentation
```bash
php artisan l5-swagger:generate
```

### Code Style

This project follows Laravel and PHP best practices. Please ensure your code adheres to PSR-12 standards.
You can use pint to adhere to the Laravel Standards by executing
```bash
./vendor/bin/pint
```

## API Documentation

API documentation is available at `/docs` when running the application locally or in the preview environment.

## Filament

There is a Filament admin panel available at `/admin`.

Only the admin User can access the admin panel.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
