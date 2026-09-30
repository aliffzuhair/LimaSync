# LimaSync

**Sustainable Event Management System for Lima Deria Sdn Bhd**

Final Year Project - Bachelor of Information Technology (Hons) in Computer Application Development
Universiti Poly-Tech Malaysia (UPTM)

## Student

- **Name:** Aliff Zuhair Bin Mohamad Puaad
- **Student ID:** AM2412017751
- **Supervisor:** Harlinawati Binti Abdul Kadir

## About

LimaSync is a web-based event management system designed for Lima Deria Sdn Bhd. It integrates:

- Event scheduling and management
- Client records
- Inventory tracking
- Financial recording
- Sustainability metrics (carbon, electricity, water, waste)
- ISO 20121 compliance reporting
- Client dashboard with real-time data
- Role-based access control

## Tech Stack

- **Framework:** Laravel 12
- **Database:** MariaDB / MySQL
- **Frontend:** Bootstrap 5, Chart.js
- **Font:** Montserrat
- **Authentication:** Laravel Breeze
- **Tunneling:** ngrok

## Setup

```bash
# 1. Clone the repository
git clone https://github.com/YOUR-USERNAME/limasync.git
cd limasync

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy env file and configure
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env
# DB_DATABASE=limasync
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Seed the database
php artisan db:seed

# 8. Build assets
npm run build

# 9. Start the server
php artisan serve