# E-commerce Shop Application

An e-commerce application built with Laravel, featuring a RESTful API and an admin panel powered by Filament, Jetstream, and Livewire.

## Features

- RESTful API for products, cart, and orders
- Admin panel for managing products, categories, orders, and users
- Session-based shopping cart
- User authentication and authorization
- Order management system
- Stock tracking

## Setup Instructions

1. Clone the repository
```bash
git clone <repository-url>
cd shop
```

2. Install dependencies
```bash
valet use
composer install
nvm use
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations and seed the database
```bash
php artisan migrate:fresh --seed
```

6. Start the development server
```bash
php artisan serve
```

## API Documentation

### Authentication Endpoints

#### Register a new user
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password"
}
```

#### Logout (requires authentication)
```http
POST /api/logout
Authorization: Bearer {token}
```

### Product Endpoints

#### List all products
```http
GET /api/products
```

#### Get specific product
```http
GET /api/products/{product}
```

### Cart Endpoints (requires authentication)

#### Add to cart
```http
POST /api/cart/products/{product}
Content-Type: application/json

{
    "quantity": 1
}
```

#### View cart
```http
GET /api/cart
```

### Order Endpoints (requires authentication)

#### List orders
```http
GET /api/orders
Authorization: Bearer {token}
```

#### Place order
```http
POST /api/orders
Content-Type: application/json
Authorization: Bearer {token}

{
    "shipping_address": "123 Ship St",
    "billing_address": "123 Bill St",
    "payment_method": "credit_card"
}
```

## Frontend Features

### Orders List
The frontend includes a Livewire-powered orders list that provides real-time updates:

1. **View Location**
   - Access the orders list at `/dashboard`
   - Automatically updates when orders change

2. **Features**
   - Real-time order status updates
   - Sort by order date, status, or total
   - Filter orders by status
   - View order details including items and pricing

3. **Implementation**
   - Built with Livewire for dynamic updates
   - Uses Filament tables for sorting and filtering
   - Responsive design for all screen sizes

## Admin Panel Usage

### Accessing the Admin Panel

1. Visit `/admin` in your browser
2. Login with admin credentials:
   - Email: admin@example.com
   - Password: password

### Available Sections

1. **Products Management**
   - Create, edit, and delete products
   - Manage stock levels
   - Set prices and categories
   - Toggle product visibility

2. **Categories Management**
   - Create and organize product categories
   - Enable/disable categories
   - View products in each category

3. **Orders Management**
   - View all orders
   - Update order status
   - Manage payment status
   - View order details and items

4. **User Management**
   - Create and manage users
   - Set admin privileges
   - Reset passwords

## Design Decisions and Assumptions

1. **Authentication**
   - Using Laravel Sanctum for API authentication
   - Token-based authentication for API endpoints
   - Session-based authentication for admin panel
   - Only admin users can access the admin panel

2. **Cart Implementation**
   - Database-backed cart storage for persistence
   - Cart items linked to users via foreign keys
   - Supports both web and API access
   - Real-time stock validation during checkout

3. **Order System**
   - Orders are immutable after creation
   - Only admins can update order status
   - Stock is automatically adjusted upon order placement
   - Basic order statuses: pending, processing, completed, cancelled

4. **Product Management**
   - Products must belong to a category
   - SKU must be unique
   - Stock levels are tracked
   - Products can be active/inactive

5. **Security Considerations**
   - Input validation on all endpoints
   - CSRF protection for web routes
   - Rate limiting on authentication endpoints
   - Role-based access control for admin features

## Testing

Run the test suite:
```bash
php artisan test
