# LemonHaus Management System

## Overview

LemonHaus Management System is a web-based system built with Laravel for managing LemonHaus operations. It supports role-based access for admin, cashier, and kitchen staff.

The system helps manage product ordering, kitchen order status, stock monitoring, sales records, reports, and user activity logs. Inventory management is handled inside the admin side.

## Main Roles

### Admin

The admin manages the whole system.

Main functions:

- View dashboard
- Manage users
- View audit logs
- Manage cashier orders
- View kitchen queue
- Manage inventory
- Add, edit, and delete LemonHaus products
- Add, edit, and delete stocks
- View sales reports
- Export reports

### Cashier

The cashier handles customer orders.

Main functions:

- View dashboard
- Create customer orders
- Select available products from dropdown
- View order list
- Delete orders if needed
- View kitchen queue status

### Kitchen Staff

The kitchen staff handles food preparation.

Main functions:

- View dashboard
- View kitchen queue
- Accept pending orders
- Mark orders as ready
- Archive completed orders to records
- View inventory status

## System Modules

## 1. Dashboard

The dashboard shows a quick overview of system activity.

Displayed data:

- Sales based on recorded completed orders
- Active orders
- Low stock items
- Average preparation time
- Weekly sales chart
- Low stock alerts
- Recent activity

The dashboard is shared across admin, cashier, and kitchen roles. It shows the correct sidebar depending on the logged-in user role.

## 2. User Management

The admin can create and delete users.

User fields:

- Full name
- Username
- Email
- Role
- Password
- Confirm password

Supported roles:

- Admin
- Cashier
- Kitchen

## 3. Audit Logs

Audit logs record important system activities.

Examples:

- User created
- User deleted
- Inventory added
- Product updated
- Stock updated

Audit log fields:

- Timestamp
- User
- Action
- Details

## 4. Order Management

The order module handles customer orders.

Order fields:

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

Orders are created by the cashier or admin. When an order is completed and archived, it is moved to order records.

## 5. Kitchen Queue

The kitchen queue shows orders based on status.

Sections:

- Pending
- In Progress
- Ready for Pickup

Kitchen actions:

- Accept Order
- Mark as Ready
- Archive to Records

When an order is archived, the system stores the order in the records table and removes it from the active orders list.

## 6. Inventory Management

Inventory management is inside the admin side.

The inventory module tracks two main groups:

- Stocks
- LemonHaus Products

### Stocks

Stocks are ingredients or supplies used to make LemonHaus products.

Examples:

- Lemon
- Sugar
- Syrup
- Cups
- Straws

Stock fields:

- Stock name
- Category
- Stock level
- Unit
- Minimum stock
- Date received
- Expiration date

Expiration date is nullable. This allows non-expirable items like cups, lids, and other packaging materials.

Stock status:

- Good
- Low Stock
- Out of Stock
- Expired

### LemonHaus Products

Products are items sold by the cashier.

Examples:

- Classic Lemonade
- Pink Lemonade
- Lemon Float

Product fields:

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

A stock item can be used by multiple products.

Example:

Lemon can be used by:

- Classic Lemonade
- Pink Lemonade
- Lemon Float

Each product can define how much stock it uses per order.

Example:

Classic Lemonade uses 1 lemon per order.

This helps support automatic stock deduction when orders are created.

## 8. Sales Reports

The reports module shows completed sales records.

Displayed data:

- Total revenue
- Total orders
- Average order value
- Weekly sales
- Top selling items

Reports can be filtered by:

- Last 7 days
- Last 14 days
- Last 30 days

Reports can also be exported as CSV.

## 9. Order Records

Completed orders are saved in order records.

Record fields:

- Record ID
- Original order ID
- Customer name
- Product name
- Quantity
- Price
- Total
- Status
- Completed at

The dashboard and reports use order records to compute sales.

## 10. Route Naming Guide

Use consistent route names to avoid route errors.

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

Fix:

Make sure the route name in Blade matches the route name in `routes/web.php`.

Correct route:

```php
Route::get('/admin/stocks/create', [AdminStockController::class, 'create'])
    ->name('admin.stocks.create');
```

Correct Blade:

```blade
<a href="{{ route('admin.stocks.create') }}">
```

### 403 Unauthorized

Cause:

The logged-in user is trying to access a route for another role.

Example:

Kitchen user opening:

```txt
/admin/kitchen
```

Correct kitchen URL:

```txt
/kitchen/kitchen
```

### PATCH method not supported

Cause:

A form is submitting to the wrong URL or using GET instead of PATCH.

Fix:

Use:

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

1. Clone or open the project folder.

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

6. Configure database in `.env`.

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

8. Run the server.

```bash
php artisan serve
```

9. Open the system.

```txt
http://127.0.0.1:8000
```

## 14. Cache Clearing Commands

Run these when routes or views are changed:

```bash
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

To check routes:

```bash
php artisan route:list
```

## 15. Recommended Improvements

Future improvements may include:

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

Development version.
