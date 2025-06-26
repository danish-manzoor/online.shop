# Online Shopping Website

This is a Laravel-based web application for an online shopping platform. It allows users to browse products, add them to the cart, place orders, and manage their profiles. The project includes both user-facing features and an admin panel for managing products, categories, orders, and users.

## Features

-   Product listing with categories
-   Product detail pages
-   Shopping cart and checkout
-   User authentication (register/login)
-   Order management
-   Admin dashboard
-   Database seeding with sample data

## Requirements

-   PHP >= 8.1
-   Composer
-   MySQL or another supported database
-   Node.js and NPM (optional, for frontend tools)

## Installation

Follow these steps to set up the project locally:

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/danish-manzoor/online.shop.git
cd your-repo
```

### Install PHP Dependencies

Install the required PHP packages using Composer:

```bash
composer install
```

### Configure Environment

Copy the .env.example file and set your environment variables:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Edit the .env file and provide the correct database credentials:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Run Migrations and Seeders

This will create the database tables and seed initial test data:

```bash
php artisan migrate --seed
```

### Serve the Application

Start the Laravel development server:

```bash
php artisan serve
```

Access the app at (http://127.0.0.1:8000)
