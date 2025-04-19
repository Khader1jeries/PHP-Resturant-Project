
# 🍽️ PHP Restaurant Project

A simple PHP-based web application for managing a restaurant with distinct portals for guests, clients, and admins. This system includes user-facing functionality as well as administrative management features.

## 📁 Project Structure

```
PHP-Resturant-Project/
│
├── Admin/           # Admin dashboard for managing the restaurant
├── Client/          # Client area (likely for logged-in users or staff)
├── guest/           # Public-facing guest interface
├── config/          # Configuration files (e.g., database settings)
├── css_files/       # Stylesheets
├── photos/          # Uploaded or static images used in the UI
├── scripts/         # JavaScript or PHP utility scripts
├── php_db.sql       # SQL script to set up the database schema
├── index.php        # Redirects to guest/index.php
└── .git/            # Git repository folder (if present)
```

## 🚀 Features

- 👤 Guest Portal: View menu, reserve a table, and explore the restaurant.
- 👨‍🍳 Client Panel: Interface for registered users or employees.
- 🛠️ Admin Dashboard: Manage food items, reservations, and user accounts.
- 📸 Image Gallery: Upload and manage restaurant-related photos.
- 🛡️ Authentication: Secure login system for admins and clients.
- 🗃️ MySQL Integration: Includes SQL file for easy database setup.

## 🛠️ Installation Guide

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/PHP-Resturant-Project.git
   ```

2. **Import the SQL database**
   - Open your MySQL manager (like phpMyAdmin).
   - Import `php_db.sql` into a new database (e.g., `restaurant_db`).

3. **Configure database connection**
   - Update database credentials in the `config/` directory.

4. **Run the project**
   - Place the project in your local server's root directory (e.g., `htdocs` if using XAMPP).
   - Visit `http://localhost/PHP-Resturant-Project` in your browser.

## 🔐 Credentials (Default)

> You might need to manually check `Admin/` or `Client/` folders for hardcoded credentials or register a new user via the UI if allowed.

## 📷 Screenshots

You can add screenshots of:
- Guest homepage
- Admin dashboard
- Menu and reservation forms
