# LimaSync

**Sustainable Event Management System for Lima Deria Sdn Bhd**

Final Year Project — Bachelor of Information Technology (Hons) in Cyber Security  
Universiti Poly-Tech Malaysia (UPTM)

---

## 👤 Student Information

| Field | Details |
| :--- | :--- |
| **Name** | Aliff Zuhair Bin Mohamad Puaad |
| **Student ID** | AM2412017751 |
| **Email** | kl2412017751@student.uptm.edu.my |
| **Supervisor** | Harlinawati Binti Abdul Kadir |
| **Programme** | Bachelor of Information Technology (Hons) in Cyber Security |

---

## 📖 About LimaSync

LimaSync is a web-based integrated event management system designed specifically for **Lima Deria Sdn Bhd**, an ISO 20121 certified sustainable event agency based in Kuala Lumpur, Malaysia.

The system consolidates all core business operations into a single platform:

- Event scheduling and lifecycle management (Pre-event, During-event, Post-event)
- Client records and relationship management
- Inventory tracking with event allocation
- Financial recording (income, expenses, budget tracking)
- Sustainability metrics (carbon, electricity, water, waste)
- ISO 20121 compliance reporting
- Client dashboard with real-time event progress
- Role-Based Access Control (RBAC)
- Audit logging for security

---

## 🛠️ Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Backend Framework** | Laravel 12 |
| **Database** | MySQL |
| **Frontend** | Bootstrap 5, Chart.js |
| **Font** | Montserrat |
| **Authentication** | Laravel Breeze |
| **Icons** | Font Awesome 6 |
| **PDF Generation** | DomPDF |
| **Tunneling (Testing)** | ngrok |
| **Version Control** | Git + GitHub |

---

## 🚀 Setup Instructions

## 🔑 Login Credentials

The system uses **Role-Based Access Control (RBAC)**. Below are the test accounts for each role:

| Role | Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| Admin | admin@limasync.com | password123 | Full system access |
| Operations | ops@limasync.com | password123 | Checklists, Sustainability, View Events & Clients |
| Sales | sales@limasync.com | password123 | View Clients only |
| Finance | finance@limasync.com | password123 | Manage Finances, View Events |
| Logistics | logistics@limasync.com | password123 | Manage Inventory, View Events |
| Client View | client@limasync.com | password123 | View own Client Dashboard only |

> ⚠️ **Note:** These are test accounts for development and FYP demonstration purposes only.
> In production, all passwords must be changed and public registration must remain disabled.

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- XAMPP (or MySQL server)
- Git

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/YOUR-USERNAME/limasync.git
cd limasync

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure your database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=limasync
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Run migrations
php artisan migrate

# 8. Seed the database with roles, users, and sample data
php artisan db:seed

# 9. Create the storage symlink
php artisan storage:link

# 10. Build frontend assets
npm run build

# 11. Start the development server
php artisan serve