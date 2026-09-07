# ApexERP - Enterprise Resource Planning System

ApexERP is a modern, modular Enterprise Resource Planning (ERP) software built with **Laravel (v12)** and standard **Blade Templates** (no heavy frontend frameworks like Vue or React required).

It provides an end-to-end suite for managing Core Business Operations:
- 📦 **Inventory & Warehouse Management**: SKU tracking, stock adjustments, reorder alerts, product catalog.
- 🛒 **Sales & POS (CRM)**: Customer directory, invoice generation, line-item pricing, tax & discount management.
- 🚚 **Purchasing & Procurement**: Supplier profiles, Purchase Orders (PO), goods receiving.
- 💼 **Financial Accounting & Expenses**: Expense tracking, category logs, cash flow metrics.
- 👥 **Human Resources (HR) & Payroll**: Employee directory, department structuring, payroll overview.
- 🔐 **Role-Based Access Control**: Standard user authentication with role permissions.

---

## 🚀 Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Laravel Blade Views, Modern Custom CSS Design System, Vanilla JavaScript
- **Database**: SQLite / MySQL supported

---

## 🛠️ Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Ashik-PUST-ICE/ERP-Software.git
   cd ERP-Software
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment File**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Database Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

5. **Start Local Development Server**:
   ```bash
   php artisan serve
   ```
   Access the application at `http://127.0.0.1:8000`.

---

## 📄 License

This project is open-source software licensed under the [MIT license](LICENSE).
