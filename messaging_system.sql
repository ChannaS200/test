
-- Create the database
CREATE DATABASE IF NOT EXISTS messaging_system;

-- Use the database
USE messaging_system;

-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    emp_no VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    password VARCHAR(255) NOT NULL
);

-- Insert default admin user
INSERT INTO users (name, emp_no, address, email, mobile, role, status, password)
VALUES ('Admin', 'EMP0001', 'Admin Address', 'admin@example.com', '1234567890', 'admin', 'approved', '$2a$12$Ol7Bdv6sGW.jNmxhPphAAO8lyfx9JTskDHvWohBJ.dp/bKsFsZijq'); -- Use hashed password for 'password'

-- Create the messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    leave_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    response_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
