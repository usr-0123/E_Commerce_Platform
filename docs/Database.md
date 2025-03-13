# Database setup and configurations.
If you're setting up the database:

## Creating database
Go to http://localhost/phpmyadmin/ and manually create a database named <database_name>,
OR simply run:
🔗 http://localhost/E_Commerce_Platform/database/create_database.php
C:\xampp\htdocs\E_Commerce_Platform

## Creating database tables
http://localhost/E_Commerce_Platform/database/create_database_tables.php

## Explanation:
- Creates the database `simple_db` if it does not exist.
- Selects the database after creation.
- Creates the `users` table with:
    - `id` → Auto-incrementing primary key
    - `first_name`, `last_name`, `email`, `password`
    - `created_at` → Automatically set when a user is created
    - `updated_at` → Updates on record modification
    - `is_deleted` → Soft delete flag (0 = active, 1 = deleted)