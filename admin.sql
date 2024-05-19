-- Create the database
CREATE DATABASE admin;

-- Use the created database
USE admin;

-- Create the employees table with additional columns for age, email, and password
CREATE TABLE employees(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    passwords VARCHAR(255) NOT NULL -- Add the password column
);

-- Select all records from the employees table
SELECT * FROM employees;
