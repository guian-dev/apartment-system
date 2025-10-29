-- Customer Portal Database Extensions
-- Run this after database_setup.sql to add customer portal features

USE kagayan_db;

-- Customers table (for customer portal users)
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    id_type ENUM('passport', 'driver_license', 'national_id', 'student_id', 'other') DEFAULT 'national_id',
    id_number VARCHAR(100),
    id_verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    reset_token VARCHAR(255) NULL,
    reset_token_expiry DATETIME NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Unit amenities table (to store amenities like WiFi, private bathroom, etc.)
CREATE TABLE IF NOT EXISTS unit_amenities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unit_id INT NOT NULL,
    amenity VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE,
    UNIQUE KEY unique_unit_amenity (unit_id, amenity)
);

-- Unit images table
CREATE TABLE IF NOT EXISTS unit_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unit_id INT NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE
);

-- Reservations/Bookings table
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    unit_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    move_in_date DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'expired', 'completed') DEFAULT 'pending',
    confirmation_method ENUM('automatic', 'manual') DEFAULT 'manual',
    deposit_amount DECIMAL(10,2) DEFAULT 0,
    deposit_paid BOOLEAN DEFAULT FALSE,
    special_requests TEXT,
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE
);

-- Reviews and Ratings table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    unit_id INT NOT NULL,
    tenant_id INT NULL, -- If the customer is/was a tenant
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200),
    review_text TEXT,
    review_date DATE NOT NULL,
    helpful_count INT DEFAULT 0,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE SET NULL
);

-- Customer Notifications table
CREATE TABLE IF NOT EXISTS customer_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    type ENUM('rent_due', 'reservation_approved', 'reservation_rejected', 'maintenance_update', 'payment_received', 'general') NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    link VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- Customer Payments table (separate from tenant payments)
CREATE TABLE IF NOT EXISTS customer_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    reservation_id INT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('gcash', 'paymaya', 'bank_transfer', 'credit_card', 'debit_card', 'cash') NOT NULL,
    payment_date DATETIME NOT NULL,
    transaction_id VARCHAR(100),
    receipt_path VARCHAR(500),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE SET NULL
);

-- Link customers to tenants (if customer becomes a tenant)
ALTER TABLE tenants ADD COLUMN IF NOT EXISTS customer_id INT NULL;
ALTER TABLE tenants ADD FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL;

-- Add location and deposit to units if not exists
ALTER TABLE units ADD COLUMN IF NOT EXISTS location VARCHAR(200);
ALTER TABLE units ADD COLUMN IF NOT EXISTS deposit_amount DECIMAL(10,2) DEFAULT 0;
ALTER TABLE units ADD COLUMN IF NOT EXISTS house_rules TEXT;
ALTER TABLE units ADD COLUMN IF NOT EXISTS capacity INT DEFAULT 1;

-- Extend payment_method enum to include paymaya and debit_card
ALTER TABLE payments MODIFY payment_method ENUM('cash', 'bank_transfer', 'check', 'gcash', 'paymaya', 'credit_card', 'debit_card') NOT NULL;

-- Insert sample amenities for existing units
INSERT INTO unit_amenities (unit_id, amenity) VALUES
(1, 'WiFi'), (1, 'Air Conditioning'), (1, 'Private Bathroom'),
(2, 'WiFi'), (2, 'Air Conditioning'), (2, 'Private Bathroom'), (2, 'Balcony'),
(4, 'WiFi'), (4, 'Air Conditioning'), (4, 'Private Bathroom'), (4, 'Parking'),
(8, 'WiFi'), (8, 'Air Conditioning'), (8, 'Private Bathroom'),
(9, 'WiFi'), (9, 'Air Conditioning'), (9, 'Private Bathroom'), (9, 'Parking'), (9, 'Balcony');

-- Insert sample unit images
INSERT INTO unit_images (unit_id, image_path, is_primary) VALUES
(1, 'uploads/units/unit-101-1.jpg', TRUE),
(2, 'uploads/units/unit-102-1.jpg', TRUE),
(4, 'uploads/units/unit-201-1.jpg', TRUE),
(8, 'uploads/units/unit-302-1.jpg', TRUE),
(9, 'uploads/units/unit-401-1.jpg', TRUE);

-- Update units with location and deposit info
UPDATE units SET location = 'Kagay an View, Cagayan de Oro' WHERE location IS NULL;
UPDATE units SET deposit_amount = monthly_rent WHERE deposit_amount = 0;

-- Create indexes for better performance
CREATE INDEX idx_customers_email ON customers(email);
CREATE INDEX idx_customers_phone ON customers(phone);
CREATE INDEX idx_reservations_customer_id ON reservations(customer_id);
CREATE INDEX idx_reservations_unit_id ON reservations(unit_id);
CREATE INDEX idx_reservations_status ON reservations(status);
CREATE INDEX idx_reviews_unit_id ON reviews(unit_id);
CREATE INDEX idx_reviews_customer_id ON reviews(customer_id);
CREATE INDEX idx_customer_notifications_customer_id ON customer_notifications(customer_id);
CREATE INDEX idx_customer_notifications_is_read ON customer_notifications(is_read);

