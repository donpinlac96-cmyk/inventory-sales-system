# Inventory and Sales Management System

A web-based Inventory and Sales Management System developed using **Laravel 12** for a small retail store or school supply shop. The system helps staff manage products, categories, stock levels, and sales in one place.

## Project Overview

The purpose of this project is to make daily store operations easier and more organized. It provides a central system for monitoring available stocks, recording sales, and managing store information.

## Main Features

- **User authentication** for authorized system users
- **Role-based access** for Admin and Cashier accounts
- **Dashboard** for viewing important inventory and sales information
- **Category management** – add, view, edit, and delete product categories
- **Product management** – manage product details, product codes, prices, and stock quantities
- **Low-stock monitoring** using each product's low-stock threshold
- **Sales management** – record customer purchases and their sale items
- **Receipt printing** for recorded transactions
- **Employee management** for administrator accounts
- **Email OTP verification** as an additional authentication step
- **Google reCAPTCHA** protection on the configured form(s)

> Available pages and permissions depend on the signed-in user's role.

## Technologies Used

- **Backend:** PHP 8.2 and Laravel 12
- **Database:** MySQL / MariaDB
- **Frontend:** Laravel Blade, HTML, CSS, and JavaScript
- **Dependency management:** Composer and npm
- **Local development environment:** XAMPP

## System Requirements

- PHP 8.2 or later
- Composer
- MySQL or MariaDB
- Node.js and npm
- A web browser

## Installation and Setup

1. Clone or download this repository:

   ```bash
   git clone https://github.com/donpinlac96-cmyk/inventory-sales-system.git
   cd inventory-sales-system
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Create your local environment file:

   **Windows Command Prompt:**
   ```bat
   copy .env.example .env
   ```

   If you already have a local `.env` file, keep it and do not overwrite it.

4. Generate the application key:

   ```bash
   php artisan key:generate
   ```

5. Create a MySQL database (for example, `inventory_sales_system`) and update the database settings in your local `.env` file:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_sales_system
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Change the username and password if your MySQL setup uses different credentials.

6. Run the database migrations and seeders:

   ```bash
   php artisan migrate --seed
   ```

7. Install frontend dependencies and build the assets:

   ```bash
   npm install
   npm run build
   ```

8. Configure your mail/SMTP settings and Google reCAPTCHA keys in your local `.env` file as required by the application's authentication and form configuration.

9. Start the Laravel development server:

   ```bash
   php artisan serve
   ```

10. Open the local address shown in the terminal (usually http://127.0.0.1:8000) in your browser.

## Security and Configuration Notes

- Keep your `.env` file private. It contains application keys, database credentials, and mail/reCAPTCHA secrets.
- Do not publish real passwords, OTP codes, API keys, or SMTP credentials.
- Configure your own database, mail account, and reCAPTCHA keys before running the application.
- Use HTTPS and production-safe settings when deploying the system.

## Database

The application uses MySQL/MariaDB. Its main business tables include:

- `users`
- `categories`
- `products`
- `sales`
- `sale_items`

Laravel also uses supporting tables for sessions, cache, queues, and migrations.

## Developer

**Don Christopher Pinlac**  
BSIT Student  
University of Eastern Philippines

## Project Purpose

This system was developed as an academic project to apply web development concepts using the Laravel framework, database management, authentication, and CRUD operations.
