# Laravel To-Do List Application

To-Do List application built with Laravel, featuring CRUD operations, filtering, sorting, and AJAX functionality.

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap 5, jQuery, Font Awesome
- **Containerization**: Docker & Docker Compose
- **Web Server**: Nginx
- **Database Management**: phpMyAdmin

## 📋 Requirements

- Docker & Docker Compose
- Git

## 🚀 Installation

### (Option A) Using Docker (Recommended)

1. Clone the repository
```bash
git clone <repository-url>
cd indoplat
```

2. Start all containers
```bash
docker-compose up -d
```

3. Install PHP dependencies inside the container
```bash
docker-compose exec app composer install --no-interaction --prefer-dist
```

4. Create environment file and app key
```bash
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
```

5. Run migrations and seeders
```bash
docker-compose exec app php artisan migrate --seed
```

6. (Optional) Create storage symlink for file uploads
```bash
docker-compose exec app php artisan storage:link
```

7. Open the app
- Application: http://localhost:8000
- phpMyAdmin: http://localhost:8080 (user: `root`, pass: `root`)

If you update dependencies or env vars later, you can restart with:
```bash
docker-compose down && docker-compose up -d --build
```

---

### (Option B) Without Docker (Local PHP environment)

Requirements: PHP 8.2+, Composer, MySQL 8+, and a web server (or use `php artisan serve`).

1. Clone the repository
```bash
git clone <repository-url>
cd indoplat
```

2. Install PHP dependencies
```bash
composer install --no-interaction --prefer-dist
```

3. Create environment file and set database config
```bash
cp .env.example .env
```
Edit `.env` and set your local DB credentials, for example:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=indoplat
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate app key
```bash
php artisan key:generate
```

5. Create database schema and seed sample data
```bash
php artisan migrate --seed
```

6. (Optional) Create storage symlink for file uploads
```bash
php artisan storage:link
```

7. Run the development server
```bash
php artisan serve
```
Visit the app at http://127.0.0.1:8000


## 🚀 Development Commands

### Docker Commands
```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# Access application container
docker-compose exec app bash

# Access database
docker-compose exec db mysql -u indoplat -p indoplat
```

### Laravel Commands
```bash
# Run migrations
docker-compose exec app php artisan migrate

# Seed database
docker-compose exec app php artisan db:seed

# Clear cache
docker-compose exec app php artisan cache:clear

# View routes
docker-compose exec app php artisan route:list
```
