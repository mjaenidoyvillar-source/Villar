# Stuff'd Inventory Management System

## Project Title
**Stuff'd Inventory Management System**  
*A comprehensive Laravel-based inventory tracking and transaction management system*

---

## Description / Overview

Stuff'd is a full-featured inventory management system built with Laravel PHP framework. This system allows businesses to efficiently manage their product catalog, track inventory levels, process sales transactions, and monitor business performance through an intuitive web-based dashboard.

The system implements a two-phase transaction process (create → complete) to ensure data integrity and prevent stock discrepancies. It features real-time stock tracking, customer relationship management, and comprehensive business analytics.

**Key Highlights:**
- **CRUD Operations** for Products, Categories, Transactions, and Customers
- **Smart Stock Management** with automatic status indicators (In Stock, Low Stock, Out of Stock)
- **Transaction Workflow** with pending → completed status tracking
- **Data Integrity Protection** prevents deletion of customers with transaction history
- **Business Analytics** with financial calculations and inventory metrics
- **Responsive UI** using Bootstrap 5 and Font Awesome icons

---

## Objectives

The main learning objectives achieved in this midterm project:

1. **Master Laravel Framework Fundamentals**
   - Implement MVC architecture (Models, Views, Controllers)
   - Utilize Eloquent ORM for database interactions
   - Create and manage database migrations and seeders
   - Implement Laravel routing and middleware

2. **Develop Database Design Skills**
   - Design relational database schema with proper relationships
   - Implement foreign key constraints and cascading deletes
   - Create database tables with appropriate data types
   - Handle data denormalization for transaction history

3. **Implement Business Logic**
   - Create a two-phase transaction workflow
   - Implement stock availability validation
   - Apply business rules and data integrity constraints
   - Develop automatic financial calculations

4. **Build User-Friendly Interfaces**
   - Create responsive web interfaces using Bootstrap 5
   - Implement forms with validation and error handling
   - Develop interactive dashboards with statistics
   - Add filtering and search functionality

5. **Ensure Data Security and Integrity**
   - Implement input validation and sanitization
   - Protect against SQL injection and XSS attacks
   - Use mass assignment protection
   - Implement CSRF protection

---

## Features / Functionality

### 1. **Product Management**
- ✅ Create, read, update, and delete products
- ✅ Organize products by categories
- ✅ Track stock levels with automatic status indicators
  - 🟢 In Stock: More than 10 items
  - 🟡 Low Stock: 1-10 items
  - 🔴 Out of Stock: 0 items
- ✅ Set product prices and calculate inventory values
- ✅ Search and filter products by category, stock level, and name

### 2. **Category Management**
- ✅ Create and manage product categories
- ✅ View all products within each category
- ✅ Prevent deletion of categories with associated products

### 3. **Transaction Processing**
- ✅ Create pending transactions
- ✅ Track transaction status (pending, completed, cancelled)
- ✅ Two-phase commit process:
  1. Create transaction (stock reserved but not reduced)
  2. Complete transaction (stock actually reduced)
- ✅ Automatic price and total amount calculations
- ✅ View transaction history with buyer details
- ✅ Filter transactions by status, product, and date range

### 4. **Customer Management**
- ✅ Store customer information (name, email, phone, address, company)
- ✅ Track customer purchase history
- ✅ Calculate total spending per customer
- ✅ View customer transaction statistics
- ✅ Prevent deletion of customers with transaction history

### 5. **Dashboard Analytics**
- ✅ Overview of total products, categories, and transactions
- ✅ Stock level breakdown (in stock, low stock, out of stock)
- ✅ Financial metrics:
  - Total inventory value
  - Total sales value
- ✅ Transaction status tracking
- ✅ Visual indicators for stock levels

### 6. **Advanced Features**
- ✅ Real-time search functionality across all modules
- ✅ Pagination for large datasets
- ✅ Responsive design for desktop and mobile devices
- ✅ Data validation with user-friendly error messages
- ✅ Eager loading for optimized database queries

---

## Installation Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer (PHP dependency manager)
- SQLite database (or MySQL/PostgreSQL)
- Web server (Apache/Nginx or PHP built-in server)
- Git (optional, for cloning the repository)

### Step-by-Step Installation

#### 1. Clone or Download the Project
```bash
# If using Git
git clone <repository-url>
cd inventory-system

# Or simply download and extract the project folder
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies using Composer
composer install
```

#### 3. Configure Environment
```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Set Up Database
```bash
# Run database migrations
php artisan migrate

# Seed the database with sample data (optional)
php artisan db:seed
```

#### 5. Start the Development Server
```bash
# Start Laravel development server
php artisan serve
```

#### 6. Access the Application
Open your web browser and navigate to:
```
http://localhost:8000
```

The application will automatically redirect to the products page.

---

## Usage

### Getting Started

1. **Dashboard Overview**
   - Navigate to the dashboard to see overall business statistics
   - View total products, categories, transactions, and financial metrics
   - Monitor stock levels and transaction statuses

2. **Adding Products**
   - Click on "Products" in the navigation menu
   - Click "Add Product" button
   - Fill in the product details:
     - Product name
     - Quantity in stock
     - Price per unit
     - Select a category
   - Click "Save" to add the product

3. **Creating Categories**
   - Go to "Categories" in the navigation
   - Click "Add Category"
   - Enter category name and description
   - Save to create the category

4. **Processing Transactions**
   - Navigate to "Transactions"
   - Click "New Transaction"
   - Select a customer and product
   - Enter the quantity to purchase
   - Click "Create Transaction" (status: pending)
   - Click "Complete" to finalize and reduce stock

5. **Managing Customers**
   - Go to "Buyers" section
   - Click "Add Buyer" to create new customers
   - View customer details and transaction history
   - See total spending per customer

### Features Usage

#### Search and Filter
- Use the search box to find specific products, transactions, or customers
- Apply filters by category, stock level, or transaction status
- Results are displayed with pagination

#### Stock Management
- Products automatically update stock status based on quantity:
  - Green badge: In Stock (11+ items)
  - Yellow badge: Low Stock (1-10 items)
  - Red badge: Out of Stock (0 items)

#### Transaction Status
- **Pending**: Transaction created but not completed
- **Completed**: Transaction finalized, stock reduced
- **Cancelled**: Transaction abandoned

---

## Screenshots or Code Snippets

### Database Structure

```php
// Products Table Migration
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->integer('quantity');
    $table->decimal('price', 8, 2);
    $table->foreignId('category_id')
           ->constrained()
           ->onDelete('cascade');
    $table->timestamps();
});

// Transactions Table Migration
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->string('buyer_name');
    $table->string('buyer_email');
    $table->string('buyer_phone')->nullable();
    $table->foreignId('product_id')
           ->constrained()
           ->onDelete('cascade');
    $table->integer('quantity_purchased');
    $table->decimal('unit_price', 8, 2);
    $table->decimal('total_amount', 10, 2);
    $table->enum('status', ['pending', 'completed', 'cancelled'])
           ->default('pending');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### Product Model

```php
class Product extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'price',
        'category_id',
    ];

    public function hasStock($quantity)
    {
        return $this->quantity >= $quantity;
    }

    public function reduceStock($quantity)
    {
        if ($this->hasStock($quantity)) {
            $this->quantity -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }
}
```

### Transaction Controller

```php
public function store(Request $request)
{
    $request->validate([
        'buyer_id' => 'required|exists:buyers,id',
        'product_id' => 'required|exists:products,id',
        'quantity_purchased' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($request->product_id);
    
    if (!$product->hasStock($request->quantity_purchased)) {
        return back()->withErrors([
            'quantity_purchased' => 'Insufficient stock. Available: ' . $product->quantity
        ])->withInput();
    }

    $transaction = Transaction::create([
        'buyer_id' => $request->buyer_id,
        'product_id' => $request->product_id,
        'quantity_purchased' => $request->quantity_purchased,
        'unit_price' => $product->price,
        'total_amount' => $product->price * $request->quantity_purchased,
        'status' => 'pending',
    ]);

    return redirect()->route('transactions.show', $transaction)
        ->with('success', 'Transaction created successfully.');
}
```

### Database Relationships

```
Categories (1) ──< Products (n)
Products (1) ──< Transactions (n)
Buyers (1) ──< Transactions (n)

Key Relationships:
- One Category has many Products
- One Product belongs to one Category
- One Product has many Transactions
- One Buyer has many Transactions
- One Transaction belongs to one Product and one Buyer
```

---

## Contributors

- **Student Name**: [Your Name]
- **Course**: Web Development / Information Systems
- **Academic Year**: 2024-2025

**Project Information:**
- Framework: Laravel 10.x
- Language: PHP 8.x
- Database: SQLite / MySQL
- Frontend: Bootstrap 5, Blade Templates
- Development Time: Midterm Examination Period

---

## License

This project is developed as part of an academic midterm examination. All rights reserved.

**Usage Rights:**
- Educational purposes only
- Not for commercial distribution
- Academic integrity maintained

**Notes:**
- This project demonstrates proficiency in Laravel framework
- All code is original work developed for academic assessment
- Best practices and security measures implemented throughout

---

## Technical Stack

- **Backend**: Laravel 10.x (PHP 8.x)
- **Database**: SQLite (default), MySQL/PostgreSQL (optional)
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Icons**: Font Awesome 6.x
- **ORM**: Eloquent ORM
- **Development Tools**: Composer, PHP Artisan CLI
- **Version Control**: Git (optional)

---

## Additional Documentation

For detailed technical documentation including:
- Complete code explanations
- Database schema details
- Business logic documentation
- Security implementations

Please refer to: `INVENTORY_SYSTEM_SIMPLE_DOCUMENTATION.html`

---

**Last Updated**: January 2025  
**Version**: 1.0.0  
**Status**: Complete and Ready for Assessment
