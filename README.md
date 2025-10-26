# Kagay an View Apartment Management System - Installation Guide

## Prerequisites

- XAMPP (Apache, MySQL, PHP) installed and running
- Web browser

## Installation Steps

### 1. Database Setup

1. Open XAMPP Control Panel
2. Start Apache and MySQL services
3. Open phpMyAdmin (http://localhost/phpmyadmin)
4. Create a new database named `kagayan_db`
5. Import the database schema by running the SQL commands from `database_setup.sql`

### 2. File Setup

1. Copy all project files to `C:\xampp\htdocs\apartment-system\`
2. Ensure all files are in the correct directory structure

### 3. Database Configuration

The database connection is configured in `db.php`:

- Host: localhost
- Username: root
- Password: (empty for default XAMPP)
- Database: kagayan_db

### 4. Access the System

1. Open your web browser
2. Navigate to `http://localhost/apartment-system/main.php`
3. The system should load with sample data

## Features Implemented

### ✅ Completed Features

- **Database Integration**: Full MySQL database with proper schema
- **Dynamic Dashboard**: Real-time statistics from database
- **Tenant Management**: View, add, edit, delete tenants
- **Unit Management**: View all units with status and tenant information
- **Staff Management**: View and manage staff members
- **Payment Tracking**: View payment records
- **Reports System**: Generate various reports
- **Responsive Design**: Works on desktop and mobile devices
- **Navigation**: Fixed all navigation links to use .php extensions

### 🔧 Database Tables Created

- `staff` - Staff member information
- `units` - Apartment unit details
- `tenants` - Tenant information and unit assignments
- `payments` - Payment records and tracking
- `maintenance_requests` - Maintenance request management
- `reports` - Generated reports storage

### 📊 Sample Data Included

The system comes with sample data including:

- 4 staff members
- 10 apartment units
- 5 tenants
- 4 payment records
- 3 maintenance requests

## File Structure

```
apartment-system/
├── db.php                 # Database connection and helper functions
├── main.php              # Main dashboard
├── tenants.php           # Tenant management
├── units.php             # Unit management
├── staff.php             # Staff management
├── payments.php          # Payment management
├── reports.php           # Reports system
├── renters.php           # Renter portal
├── delete_tenant.php     # Tenant deletion handler
├── delete_staff.php      # Staff deletion handler
├── logout.php            # Logout functionality
├── database_setup.sql    # Database schema and sample data
├── payments.css          # Main stylesheet
├── tenants.css           # Tenant page styles
├── units.css             # Unit page styles
├── staff.css             # Staff page styles
├── renters.css           # Renter portal styles
└── reports.css           # Reports page styles
```

## Usage Instructions

### Dashboard (main.php)

- View real-time statistics
- Quick access to recent tenants
- Quick action buttons for common tasks

### Tenant Management (tenants.php)

- View all tenants with filtering options
- Search tenants by name
- Add, edit, or delete tenant records
- View tenant status and unit assignments

### Unit Management (units.php)

- View all units in grid layout
- Filter by status (available, occupied, maintenance)
- Search units by number
- View unit details and current tenant

### Staff Management (staff.php)

- View all staff members
- Add, edit, or delete staff records
- Track staff status and assignments

### Payment Management (payments.php)

- View payment records
- Track payment status
- Generate payment reports

### Reports (reports.php)

- Generate various types of reports
- Export reports in different formats
- View report history

## Troubleshooting

### Common Issues

1. **Database Connection Error**: Ensure MySQL is running in XAMPP
2. **Page Not Found**: Check file paths and ensure files are in correct directory
3. **Empty Data**: Run the database_setup.sql script to populate sample data

### Database Connection Issues

If you encounter database connection issues:

1. Check XAMPP MySQL service is running
2. Verify database name is `kagayan_db`
3. Check username/password in `db.php`

## Next Steps

The system is now fully functional with database integration. You can:

1. Add more sample data
2. Customize the styling
3. Add more features like email notifications
4. Implement user authentication
5. Add more detailed reporting features

## Support

For any issues or questions, refer to the code comments or database schema for understanding the system structure.
