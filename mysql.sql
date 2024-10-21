-- phpMyAdmin SQL Dump
-- version 1.0.0
-- https://www.phpmyadmin.net/

-- Database: `attendancemsystem`

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL, -- To store the hashed password
    role ENUM('admin', 'teacher', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert an admin user with a hashed password
INSERT INTO users (username, password, role)
VALUES ('admin', '$2y$10$aq5/XZkcU.w4qvwz7n4LMO3BGr/sj2J5OCt.Kvw4gb76TMxaLVlcy', 'admin'); --Password is-- admin@123
