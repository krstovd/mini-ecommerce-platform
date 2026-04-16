# Mini E-Commerce Platform

This is a Laravel-based mini e-commerce platform.

## Features

- User roles: Buyer and Vendor
- Authentication (login/register)
- Marketplace with product listing and filtering
- Add to cart functionality
- Cart management (update quantity, remove items)
- Stock validation
- Checkout process with simulated payment
- Buyer order history
- Vendor order management (update item status)
- Feature tests for core functionality

## Tech Stack

- Laravel
- Blade / Livewire
- SQLite
- PHPUnit

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run dev
php artisan serve
