# Customer Portal System - Kagay an View Apartment Management

## Overview

A comprehensive customer portal system that allows customers to browse, reserve, and manage apartment rentals.

## Features Implemented

### ✅ 1. Registration and Login System

- **register.php** - Customer registration with ID verification
- **login.php** - Login using email or phone number
- **forgot_password.php** - Password recovery system
- **auth.php** - Authentication and session management functions

### ✅ 2. Browse Available Units

- **customer.php** - Public-facing unit listings page
  - Search functionality
  - Filter by bedroom type (Studio, 1BR, 2BR, 3+BR)
  - Display amenities for each unit
  - Price display and basic information

### ✅ 3. Unit Details Page

- **unit_details.php** - Comprehensive unit information page
  - Full unit specifications
  - Amenities list
  - Reviews and ratings display
  - Booking/reservation button
  - House rules (if available)

### ✅ 4. Booking/Reservation System

- **booking.php** - Reserve a unit
  - Select move-in date
  - Special requests
  - Deposit information
  - Automatic or manual approval workflow

### ✅ 5. Customer Dashboard

- **customer_dashboard.php** - Main customer hub
  - View current rental (if tenant)
  - Upcoming payments
  - Maintenance requests
  - Reservations list
  - Recent notifications
  - Quick stats

### ✅ 6. Payment System

- **customer_payment.php** - Make payments
  - Multiple payment methods:
    - GCash
    - PayMaya
    - Bank Transfer
    - Credit Card
  - View pending payments
  - Transaction ID tracking

### ✅ 7. Maintenance Requests

- **customer_maintenance.php** - Submit and track maintenance
  - Submit new requests
  - View request status
  - Priority levels (Low, Medium, High, Urgent)
  - Track progress (Pending, In Progress, Completed)

### ✅ 8. Notification System

- Built into the system
- Email/SMS notifications (infrastructure ready)
- In-app notifications
- Notification types:
  - Rent due reminders
  - Reservation approvals/rejections
  - Maintenance updates
  - Payment confirmations

### ✅ 9. Reviews and Ratings

- Database structure ready
- Display reviews on unit details page
- Rating system (1-5 stars)
- Review approval workflow

## Database Setup

Run the SQL files in this order:

1. **database_setup.sql** - Base database and tables
2. **customer_portal_setup.sql** - Customer portal extensions

This will create:

- `customers` table
- `reservations` table
- `reviews` table
- `customer_notifications` table
- `customer_payments` table
- `unit_amenities` table
- `unit_images` table

## File Structure

```
apartment-system/
├── auth.php                      # Authentication functions
├── register.php                  # Customer registration
├── login.php                     # Customer login
├── forgot_password.php           # Password recovery
├── customer.php                  # Browse units (public)
├── unit_details.php              # Unit details page
├── booking.php                   # Reservation system
├── customer_dashboard.php        # Customer dashboard
├── customer_payment.php          # Payment system
├── customer_maintenance.php      # Maintenance requests
├── customer_logout.php           # Logout handler
└── customer_portal_setup.sql     # Database extensions
```

## Usage Instructions

### For Customers

1. **Browse Units** - Visit `customer.php` to see available units
2. **Create Account** - Click "Sign Up" to register
3. **Login** - Use email or phone number + password
4. **View Details** - Click on any unit to see full details
5. **Reserve Unit** - Logged-in users can reserve units
6. **Make Payments** - Access payment page from dashboard
7. **Submit Maintenance** - Report issues from maintenance page

### For Admins

The admin can manage:

- Approve/reject reservations
- Update reservation status
- View customer payments
- Manage maintenance requests
- Approve reviews

## Important Notes

1. **Password Recovery** - Currently generates reset tokens. You'll need to implement email/SMS sending to complete the flow.

2. **Payment Processing** - Payment submissions are recorded but need admin verification. Integrate with actual payment gateways (GCash API, PayMaya API, etc.) for real processing.

3. **Reviews** - Customers can view reviews, but submitting reviews requires additional pages (can be added easily using the existing database structure).

4. **Notifications** - Notification infrastructure is ready. Add email/SMS sending services (PHPMailer, Twilio, etc.) for full functionality.

5. **Calendar View** - Reservation calendar can be added using libraries like FullCalendar.js (database structure supports it).

## Security Features

- Password hashing using PHP `password_hash()`
- SQL injection protection with prepared statements
- Session-based authentication
- Input sanitization
- CSRF protection recommended (can be added)

## Next Steps (Optional Enhancements)

1. Add review submission page for customers
2. Implement email/SMS notifications
3. Add calendar view for unit availability
4. Implement payment gateway integrations
5. Add unit image upload functionality
6. Create admin panel for managing reservations
7. Add search filters by price range
8. Add location-based filtering
9. Implement real-time chat support
10. Add mobile app API endpoints

## Support

For issues or questions about the customer portal, refer to the code comments and database schema.
