# ShopSmart - Complete POS & Inventory Management System

A comprehensive Point of Sale (POS) and Inventory Management System built with Laravel 12, featuring a modern Hostinger-inspired dashboard design.

## Features

### ✅ Core Modules Implemented

1. **Inventory & Stock Management**
   - Add, edit, delete products/services
   - Track stock levels (in, out, returns)
   - Low-stock alerts
   - Barcode/SKU tracking
   - Multi-warehouse support
   - Product categories

2. **Sales & Billing (POS)**
   - Point of Sale system
   - Generate invoices/receipts
   - Multiple payment methods (cash, card, mobile money, bank transfer)
   - Apply discounts and taxes
   - Real-time cart management

3. **Purchase & Supplier Management**
   - Manage supplier information
   - Track purchase orders
   - Record incoming stock
   - Track payments to suppliers

4. **Customer Management (CRM)**
   - Store customer details
   - Track purchase history
   - Loyalty points system
   - Customer status management

5. **Financial Management**
   - Track income and expenses
   - Profit & loss overview
   - Transaction history
   - Financial reports

6. **Employee & Staff Management**
   - Staff profiles and roles
   - Role-based access control
   - Employee management

7. **Reporting & Analytics**
   - Dashboard with KPIs
   - Sales and revenue tracking
   - Inventory reports
   - Financial reports

## Technology Stack

- **Backend**: Laravel 12
- **Frontend**: Blade Templates, Tailwind CSS 4
- **Database**: MySQL
- **JavaScript**: Vanilla JS (Alpine.js ready)
- **Charts**: Chart.js (configured)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd shopsmart
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Update .env file with MySQL credentials**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=shopsmart
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

## Database Structure

The system includes the following main tables:

- `users` - User accounts with roles
- `categories` - Product categories
- `suppliers` - Supplier information
- `warehouses` - Warehouse/location management
- `products` - Product inventory
- `stock_movements` - Stock tracking
- `customers` - Customer database
- `sales` - Sales transactions
- `sale_items` - Sale line items
- `purchases` - Purchase orders
- `purchase_items` - Purchase line items
- `employees` - Employee records
- `transactions` - Financial transactions
- `expenses` - Expense tracking

## Design Features

- **Hostinger-inspired UI**: Purple color scheme, clean sidebar navigation
- **Responsive Design**: Mobile-friendly with collapsible sidebar
- **Modern Dashboard**: KPI cards, charts, and quick actions
- **Intuitive Navigation**: Easy access to all modules

## Routes

- `/` - Redirects to dashboard
- `/dashboard` - Main dashboard
- `/products` - Product/Inventory management
- `/pos` - Point of Sale
- `/sales` - Sales management
- `/purchases` - Purchase management
- `/customers` - Customer management
- `/suppliers` - Supplier management
- `/employees` - Employee management
- `/financial` - Financial overview
- `/reports` - Reports & Analytics

## API Endpoints

- `GET /api/products` - Get active products for POS

## Key Features

### Dashboard
- Real-time KPIs (Sales, Products, Customers, Orders)
- Recent sales list
- Quick action buttons
- Sales overview chart (placeholder)

### POS System
- Product search and selection
- Real-time cart management
- Multiple payment methods
- Automatic stock deduction
- Invoice generation

### Inventory Management
- Product CRUD operations
- Stock level tracking
- Low stock alerts
- Category and warehouse management

### Financial Management
- Income tracking
- Expense tracking
- Profit & loss calculation
- Transaction history

## Next Steps

To complete the system, you may want to:

1. Add authentication middleware
2. Implement role-based permissions
3. Add more detailed reports with charts
4. Implement barcode scanning
5. Add email/SMS notifications
6. Create print templates for invoices
7. Add data export functionality
8. Implement backup system
9. Add multi-currency support
10. Create mobile app integration

## License

This project is open-sourced software licensed under the MIT license.
