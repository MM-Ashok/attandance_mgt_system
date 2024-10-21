-- phpMyAdmin SQL Dump
-- version 1.0.1
-- https://www.phpmyadmin.net/

-- Database: `attendancemsystem`

-- Create the users table
CREATE TABLE miraiadmin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL, -- To store the hashed password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert an admin user with a hashed password
INSERT INTO users (username, password, role)
VALUES ('admin', '$2y$10$aq5/XZkcU.w4qvwz7n4LMO3BGr/sj2J5OCt.Kvw4gb76TMxaLVlcy'); --Password is-- admin@123


CREATE TABLE miraiteachers (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    emailAddress VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phoneNo VARCHAR(15),
    classId VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE miraiclass (
  Id int(10) NOT NULL,
  className varchar(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `miraiclass` (`Id`, `className`) VALUES
(1, 'One'),
(2, 'Two');