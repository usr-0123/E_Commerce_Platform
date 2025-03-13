# E_Commerce Platform
## Project Overview:
This is a group-based web development project where each group is tasked with
building a fully functional web application using PHP, MySQL, JavaScript,
HTML, and CSS. You will collaborate in teams, work through the project stages,
and present your final product. All projects will be pushed to a shared GitHub
repository for version control, and final submissions will include both the
working system and documentation.

## Project Objective:
The primary goal is to demonstrate your ability to develop a dynamic web
application, manage database interactions, implement user authentication, and
create a user-friendly interface. Each group will have to select and implement a
specific application (as detailed below).

## Introduction
- User Accounts: Register/login for admins
and customers, with password hashing.
- Product Management: Admin
adds/edit/delete products, including images,
descriptions, and prices.
- Cart System: Customers can add/remove
items and view the cart total.
- Checkout: Simulate payments (use dummy
payment gateway).
- Order Management: Admin manages orders
and customers track order status.
- Search & Filters: Search products and filter
by category, price, or rating.

## Project Requirements
1. **Technical Requirements:**
   - **PHP** for server-side scripting.
   - **MySQL** for the database system.
   - **HTML/CSS** for the front-end UI design.
   - **JavaScript** for interactivity (e.g., form validation, dynamic elements).
   - Version control using **GitHub**.
2. **Documentation:**
   - **README** file with setup instructions, installation guides, and a description of
   the project.
   - Code comments to explain logic and functionality.
3. **Deployment:**
   - Deploy the system locally for testing and demonstration.
   - Present your project with a working demo to the class.
4. **Collaboration:**
   - Work as a team and ensure that all members are actively involved in the project
   development.
   - Use `GitHub` for version control and push your work regularly.
   
## Folder structure
```bash
ecommerce_project/
|-admin
|   |-dashboard.php     # Admin panel homepage
|   |-products.php      # Manage products
|   |-orders.php        # Manage orders
|   |-users.php         # Manage users
|
|-assets
|   |-css/              # Stylesheets (style.css, etc.)
|   |-js/               # JavaScript files (scripts.js, validation.js, etc.)
|   |-images/           # Product images, logos, etc.
|   |-fonts/            # Custom fonts (if any)
|
|-auth
|   |-login.php         # User login
|   |-register.php      # User registration
|   |-logout.php        # Logout script
|
|-config
|   |-config.php        # Database connection settings
|   |-constants.php     # Define constants like site name, paths, etc.
|
|-database
|   |-database_tables.sql       # SQL script showing database tables schema
|
|-includes
|   |-header.php        # Common header (navigation, meta tags, etc.)
|   |-footer.php        # Common footer 
|   |-functions.php     # Utility functions (e.g., authentication, validation)
|
|-pages
|   |-dashboard.php          # Homepage
|   |-shop.php          # Product listing page
|   |-product.php       # Single product details page
|   |-cart.php          # Shopping cart page
|   |-orders.php      # Checkout page
|   |-orders.php        # Order history for users
|
|-uploads/              # Uploaded product images
| 
|-index.php             # Entry point (homepage)
|-README.MD             # Project documentation
```

/ecommerce-project
│── /assets
│   ├── /css
│   │   ├── style.css
│   ├── /js
│   │   ├── script.js
│   │   ├── validation.js
│   ├── /images
│── /config
│   ├── config.php
│   ├── database.php
│── /auth
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│── /admin
│   ├── dashboard.php
│   ├── products.php
│   ├── orders.php
│   ├── users.php
│── /pages
│   ├── home.php
│   ├── shop.php
│   ├── product.php
│   ├── cart.php
│   ├── checkout.php
│── /includes
│   ├── header.php
│   ├── footer.php
│── /controllers
│   ├── ProductController.php
│   ├── CartController.php
│   ├── OrderController.php
│── /models
│   ├── User.php
│   ├── Product.php
│   ├── Order.php
│── /database
│   ├── migrate.sql
│── .env
│── index.php
│── .htaccess
│── README.md
