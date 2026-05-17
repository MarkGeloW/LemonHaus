# LemonHaus Management System

## Overview

LemonHaus Management System is a Laravel-based system made for managing the daily operations of LemonHaus. It covers order taking, kitchen queue monitoring, product and stock tracking, sales records, reports, and user activity logs.

The system has three user roles: admin, cashier, and kitchen staff. Inventory is managed by the admin, so there is no separate inventory staff role.

## Main Roles

### Admin

The admin controls and monitors the main parts of the system.

The admin can:

- View the dashboard
- Manage users
- View audit logs
- Manage cashier orders
- View the kitchen queue
- Manage inventory
- Add, edit, and delete LemonHaus products
- Add, edit, and delete stocks
- View sales reports
- Export reports

### Cashier

The cashier handles customer orders.

The cashier can:

- View the dashboard
- Create customer orders
- Choose available products from a dropdown list
- View the order list
- Delete orders when needed
- View the kitchen queue status

### Kitchen Staff

The kitchen staff handles order preparation.

The kitchen staff can:

- View the dashboard
- View the kitchen queue
- Accept pending orders
- Mark orders as ready
- Archive completed orders to records
- View inventory status

## System Modules

## 1. Dashboard

The dashboard gives a quick view of what is happening in the system.

It shows:

- Sales from completed orders
- Active orders
- Low stock items
- Average preparation time
- Weekly sales chart
- Low stock alerts
- Recent activity

The same dashboard is used by the admin, cashier, and kitchen staff. The sidebar changes depending on the role of the logged-in user.

## 2. User Management

The admin can add and delete user accounts.

User details include:

- Full name
- Username
- Email
- Role
- Password
- Confirm password

Available roles:

- Admin
- Cashier
- Kitchen

## 3. Audit Logs

Audit logs help track important actions in the system.

Examples of logged actions:

- User created
- User deleted
- Stock added
- Product updated
- Stock updated

Audit log details include:

- Timestamp
- User
- Action
- Details

## 4. Order Management

The order module handles customer orders.

Order details include:

- Customer name
- Product name
- Price
- Quantity
- Status

Order statuses:

- Pending
- In progress
- Ready
- Completed

The cashier or admin creates orders. After the kitchen finishes an order, it can be archived into order records.

## 5. Kitchen Queue

The kitchen queue helps kitchen staff organize orders.

It has three sections:

- Pending
- In Progress
- Ready for Pickup

Kitchen actions:

- Accept Order
- Mark as Ready
- Archive to Records

After an order is archived, it is saved in the order records table and removed from the active order list.

## 6. Inventory Management

Inventory management is part of the admin side.

The inventory module has two main parts:

- Stocks
- LemonHaus Products

### Stocks

Stocks are ingredients or supplies used in LemonHaus products.

Examples:

- Lemon
- Sugar
- Syrup
- Cups
- Straws

Stock details include:

- Stock name
- Category
- Stock level
- Unit
- Minimum stock
- Date received
- Expiration date

The expiration date is optional. This is useful for packaging items such as cups, lids, and straws because they do not expire.

Stock status:

- Good
- Low Stock
- Out of Stock
- Expired

### LemonHaus Products

LemonHaus products are the items sold by the cashier.

Examples:

- Classic Lemonade
- Pink Lemonade
- Lemon Float

Product details include:

- Product name
- Category
- Description
- Price
- Stock
- Status

Product status:

- Available
- Unavailable
- Low Stock
- Out of Stock

## 7. Product and Stock Relationship

One stock item can be used by several products.

Example:

Lemon can be used in:

- Classic Lemonade
- Pink Lemonade
- Lemon Float

Each product can also have a set amount of stock used per order.

Example:

Classic Lemonade uses 1 lemon per order.

This setup helps the system deduct stocks automatically when orders are created.

## 8. Sales Reports

The reports module shows completed sales records.

It shows:

- Total revenue
- Total orders
- Average order value
- Weekly sales
- Top selling items

Reports can be filtered by:

- Last 7 days
- Last 14 days
- Last 30 days

Reports can also be exported as a CSV file.

## 9. Order Records

Completed orders are saved as order records.

Record details include:

- Record ID
- Original order ID
- Customer name
- Product name
- Quantity
- Price
- Total
- Status
- Completed at

The dashboard and reports use the order records table to calculate sales.

## 10. Route Naming Guide

Consistent route names help avoid route errors.

### Shared Dashboard

```php
Route::get('/dashboard', [AdminDashboardController::class, 'index'])
    ->name('dashboard');
```

### Admin Routes

```php
admin.index
admin.orders.index
admin.kitchen
admin.inventory
admin.products.create
admin.products.store
admin.products.edit
admin.products.update
admin.products.destroy
admin.stocks.create
admin.stocks.store
admin.stocks.edit
admin.stocks.update
admin.stocks.destroy
reports.index
reports.export
```

### Cashier Routes

```php
cashier.dashboard
cashier.orders.index
cashier.orders.store
cashier.orders.destroy
cashier.kitchen
```

### Kitchen Routes

```php
kitchen.dashboard
kitchen.index
kitchen.orders.accept
kitchen.orders.ready
kitchen.orders.complete
kitchen.inventory
```

## 11. Common Errors and Fixes

### Route not defined

Example:

```txt
Route [admin.stocks.create] not defined
```

This happens when the route name in the Blade file does not match the route name in `routes/web.php`.

Correct route:

```php
Route::get('/admin/stocks/create', [AdminStockController::class, 'create'])
    ->name('admin.stocks.create');
```

Correct Blade code:

```blade
<a href="{{ route('admin.stocks.create') }}">
```

### 403 Unauthorized

This happens when a user opens a page for another role.

Example:

A kitchen user should not open:

```txt
/admin/kitchen
```

Correct kitchen queue URL:

```txt
/kitchen/kitchen
```

### PATCH method not supported

This usually happens when a form submits to the wrong route or uses the wrong method.

Use this inside PATCH forms:

```blade
@csrf
@method('PATCH')
```

## 12. Suggested Database Tables

### users

- id
- name
- username
- email
- password
- role
- created_at
- updated_at

### products

- id
- name
- category
- description
- price
- stock
- status
- created_at
- updated_at

### inventories

- id
- name
- category
- stock_level
- unit
- min_stock
- date_added
- expiration_date
- created_at
- updated_at

### orders

- id
- customer_name
- product_name
- price
- quantity
- status
- created_at
- updated_at

### order_records

- id
- order_id
- customer_name
- product_name
- quantity
- price
- total
- status
- completed_at
- created_at
- updated_at

### audit_logs

- id
- user_id
- user_name
- action
- details
- created_at
- updated_at

## 13. Installation Guide

1. Open the project folder.

```bash
cd C:\laragon\www\LemonHaus\LemonHaus
```

2. Install PHP dependencies.

```bash
composer install
```

3. Install frontend dependencies.

```bash
npm install
```

4. Copy the environment file.

```bash
copy .env.example .env
```

5. Generate the app key.

```bash
php artisan key:generate
```

6. Set the database in `.env`.

Example:

```env
DB_DATABASE=lemonhaus
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations.

```bash
php artisan migrate
```

8. Start the Laravel server.

```bash
php artisan serve
```

9. Open the system in the browser.

```txt
http://127.0.0.1:8000
```

## 14. Cache Clearing Commands

Run these commands after changing routes or views:

```bash
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

To check available routes:

```bash
php artisan route:list
```

## 15. Recommended Improvements

Possible improvements:

- Automatic stock deduction based on product recipe
- Sales report charts
- Product image upload
- Printable receipts
- Low stock notifications
- Expired stock alerts
- Export inventory report
- Role-based dashboard customization
- Activity log filters
- Search and pagination for tables

## Project Title

LemonHaus Management System

## Technology Stack

- Laravel
- PHP
- MySQL
- Blade
- Tailwind CSS
- JavaScript
- SweetAlert2
- Laragon

## Status

Development version
