-- Database setup for Kagay an View Apartment Management System
-- Run this script to create the database and all necessary tables

CREATE DATABASE IF NOT EXISTS kagayan_db;
USE kagayan_db;

-- Staff table
CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Units table
CREATE TABLE IF NOT EXISTS units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unit_number VARCHAR(20) UNIQUE NOT NULL,
    floor INT NOT NULL,
    bedrooms INT DEFAULT 1,
    bathrooms INT DEFAULT 1,
    area_sqm DECIMAL(8,2),
    monthly_rent DECIMAL(10,2) NOT NULL,
    status ENUM('available', 'occupied', 'maintenance', 'reserved') DEFAULT 'available',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tenants table
CREATE TABLE IF NOT EXISTS tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    unit_id INT,
    move_in_date DATE,
    move_out_date DATE NULL,
    monthly_rent DECIMAL(10,2),
    status ENUM('active', 'pending', 'inactive', 'moved_out') DEFAULT 'pending',
    emergency_contact VARCHAR(100),
    emergency_phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE SET NULL
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT NOT NULL,
    unit_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    due_date DATE NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'check', 'gcash', 'credit_card') NOT NULL,
    status ENUM('paid', 'pending', 'overdue', 'cancelled') DEFAULT 'pending',
    reference_number VARCHAR(50),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE
);

-- Maintenance requests table
CREATE TABLE IF NOT EXISTS maintenance_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT NOT NULL,
    unit_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    requested_date DATE NOT NULL,
    completed_date DATE NULL,
    assigned_staff_id INT NULL,
    cost DECIMAL(10,2) DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_staff_id) REFERENCES staff(id) ON DELETE SET NULL
);

-- Reports table
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_type ENUM('financial', 'occupancy', 'tenant', 'payment', 'maintenance', 'lease') NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    generated_by VARCHAR(100) NOT NULL,
    generated_date DATE NOT NULL,
    file_path VARCHAR(500),
    parameters JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO staff (name, position, email, phone, status) VALUES
('John Smith', 'Property Manager', 'john.smith2@kagayanview.com', '+63 912 345 6789', 'active'),
('Maria Garcia', 'Maintenance Supervisor', 'maria.garcia@kagayanview.com', '+63 923 456 7890', 'active'),
('Pedro Santos', 'Security Guard', 'pedro.santos@kagayanview.com', '+63 934 567 8901', 'active'),
('Ana Lopez', 'Administrative Assistant', 'ana.lopez@kagayanview.com', '+63 945 678 9012', 'active');

INSERT INTO units (unit_number, floor, bedrooms, bathrooms, area_sqm, monthly_rent, status, description) VALUES
('101', 1, 1, 1, 28.5, 6000.00, 'available', 'Studio unit with basic amenities'),
('102', 1, 1, 1, 30.0, 6500.00, 'available', 'Studio unit with balcony'),
('103', 1, 2, 1, 45.0, 8500.00, 'occupied', 'Two-bedroom unit'),
('201', 2, 1, 1, 32.0, 7000.00, 'available', 'One-bedroom unit'),
('202', 2, 2, 1, 48.0, 9000.00, 'occupied', 'Two-bedroom unit with view'),
('203', 2, 1, 1, 30.0, 6800.00, 'occupied', 'One-bedroom unit'),
('301', 3, 2, 1, 45.0, 8500.00, 'occupied', 'Two-bedroom unit'),
('302', 3, 1, 1, 35.0, 7500.00, 'available', 'One-bedroom unit'),
('401', 4, 3, 2, 65.0, 12000.00, 'available', 'Three-bedroom unit'),
('402', 4, 2, 1, 50.0, 9500.00, 'occupied', 'Two-bedroom unit');

INSERT INTO tenants (name, email, phone, unit_id, move_in_date, monthly_rent, status, emergency_contact, emergency_phone) VALUES
('Maria Santos', 'maria.santos@email.com', '+63 912 345 6789', 3, '2024-01-15', 8500.00, 'active', 'Juan Santos', '+63 923 456 7890'),
('Juan Dela Cruz', 'juan.delacruz@email.com', '+63 923 456 7890', 5, '2024-03-10', 9000.00, 'active', 'Maria Dela Cruz', '+63 934 567 8901'),
('Ana Reyes', 'ana.reyes@email.com', '+63 934 567 8901', 6, '2025-09-20', 6800.00, 'pending', 'Pedro Reyes', '+63 945 678 9012'),
('Pedro Garcia', 'pedro.garcia@email.com', '+63 945 678 9012', 7, '2024-06-05', 8500.00, 'active', 'Rosa Garcia', '+63 956 789 0123'),
('Rosa Mendoza', 'rosa.mendoza@email.com', '+63 956 789 0123', 10, '2024-02-20', 9500.00, 'active', 'Carlos Mendoza', '+63 967 890 1234');

INSERT INTO payments (tenant_id, unit_id, amount, payment_date, due_date, payment_method, status, reference_number) VALUES
(1, 3, 8500.00, '2025-09-05', '2025-09-05', 'bank_transfer', 'paid', 'TXN001'),
(2, 5, 9000.00, '2025-09-03', '2025-09-03', 'gcash', 'paid', 'TXN002'),
(4, 7, 8500.00, '2025-09-08', '2025-09-05', 'cash', 'paid', 'TXN003'),
(5, 10, 9500.00, '2025-09-10', '2025-09-05', 'bank_transfer', 'paid', 'TXN004');

INSERT INTO maintenance_requests (tenant_id, unit_id, title, description, priority, status, requested_date, assigned_staff_id) VALUES
(1, 3, 'Kitchen Sink Repair', 'Kitchen sink is leaking and needs repair', 'medium', 'completed', '2025-09-10', 2),
(2, 5, 'Air Conditioning Issue', 'AC unit not cooling properly', 'high', 'in_progress', '2025-09-15', 2),
(4, 7, 'Door Lock Replacement', 'Front door lock is jammed', 'urgent', 'pending', '2025-09-20', NULL);

-- Create indexes for better performance
CREATE INDEX idx_tenants_unit_id ON tenants(unit_id);
CREATE INDEX idx_tenants_status ON tenants(status);
CREATE INDEX idx_payments_tenant_id ON payments(tenant_id);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_payments_due_date ON payments(due_date);
CREATE INDEX idx_units_status ON units(status);
CREATE INDEX idx_maintenance_requests_status ON maintenance_requests(status);
CREATE INDEX idx_maintenance_requests_priority ON maintenance_requests(priority);
