# Quick Start Guide - Customer Portal

## Step 1: Setup Database

### Option A: Using phpMyAdmin (Recommended)

1. **Start XAMPP**

   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL** services

2. **Open phpMyAdmin**

   - Go to: `http://localhost/phpmyadmin`

3. **Run SQL Scripts**
   - Click on **Import** tab
   - Choose file: `database_setup.sql`
   - Click **Go** to execute
   - Then import `customer_portal_setup.sql` the same way

### Option B: Using MySQL Command Line

Open Command Prompt/PowerShell and run:

```bash
cd C:\xampp\mysql\bin
mysql.exe -u root -e "source C:\xampp\htdocs\apartment-system\database_setup.sql"
mysql.exe -u root -e "source C:\xampp\htdocs\apartment-system\customer_portal_setup.sql"
```

## Step 2: Access the Customer Portal

1. **Open your web browser**

2. **Browse Available Units** (Public - No login required):

   ```
   http://localhost/apartment-system/customer.php
   ```

3. **Register a New Account**:

   ```
   http://localhost/apartment-system/register.php
   ```

   Or click "Sign Up" button on the customer.php page

4. **Login**:

   ```
   http://localhost/apartment-system/login.php
   ```

   Use your email OR phone number + password

5. **Customer Dashboard** (After login):
   ```
   http://localhost/apartment-system/customer_dashboard.php
   ```

## Step 3: Test the Customer Portal Flow

### As a Guest (Public Access):

1. ✅ Visit `customer.php` - Browse available units
2. ✅ Use search and filters
3. ✅ Click "View Details" on any unit
4. ✅ View unit information, amenities, and reviews
5. ✅ Click "Login to Reserve" (redirects to login)

### As a Registered Customer:

1. ✅ Register new account at `register.php`
2. ✅ Login at `login.php`
3. ✅ Browse units and click "Reserve Now"
4. ✅ Fill reservation form at `booking.php`
5. ✅ View dashboard at `customer_dashboard.php`
6. ✅ Check reservations, payments, and maintenance
7. ✅ Make payments at `customer_payment.php`
8. ✅ Submit maintenance requests at `customer_maintenance.php`

## Main Pages URL List

| Page            | URL                        | Description                 |
| --------------- | -------------------------- | --------------------------- |
| Browse Units    | `customer.php`             | Public listing page         |
| Register        | `register.php`             | Create customer account     |
| Login           | `login.php`                | Customer login              |
| Forgot Password | `forgot_password.php`      | Password recovery           |
| Unit Details    | `unit_details.php?id=1`    | View specific unit          |
| Booking         | `booking.php?unit_id=1`    | Reserve a unit              |
| Dashboard       | `customer_dashboard.php`   | Customer dashboard          |
| Payments        | `customer_payment.php`     | Make payments               |
| Maintenance     | `customer_maintenance.php` | Submit maintenance requests |

## Troubleshooting

### Database Connection Error

- ✅ Check if MySQL is running in XAMPP
- ✅ Verify database name is `kagayan_db` in `db.php`
- ✅ Check username/password (default: `root` with no password)

### Page Not Found (404)

- ✅ Make sure files are in `C:\xampp\htdocs\apartment-system\`
- ✅ Check file names match exactly (case-sensitive)

### No Units Showing

- ✅ Run `database_setup.sql` to create sample data
- ✅ Check units table has data with status = 'available'

### Login Not Working

- ✅ Make sure `customer_portal_setup.sql` was run
- ✅ Verify `customers` table exists
- ✅ Check session is enabled in PHP

### Can't Reserve Units

- ✅ Make sure you're logged in
- ✅ Check if unit status is 'available'
- ✅ Verify `reservations` table exists

## Quick Test Account

After running the SQL scripts, you can create a test account:

1. Go to `register.php`
2. Fill in:
   - Name: Test User
   - Email: test@example.com
   - Phone: +63 912 345 6789
   - ID Type: National ID
   - ID Number: 123456789
   - Password: (any password, min 8 characters)
3. Click "Create Account"
4. You'll be automatically logged in and redirected to dashboard

## Need Help?

- Check `CUSTOMER_PORTAL_README.md` for detailed feature documentation
- Check `database_setup.sql` and `customer_portal_setup.sql` for database structure
- Review code comments in PHP files
