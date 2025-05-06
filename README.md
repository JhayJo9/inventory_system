
# Inventory System

A simple inventory management system with role-based access for Admin and Staff users.

---

## Database Configuration

Make sure your MySQL server is running and listening on port `3307`. Use the following credentials to set up the database:

- **Host:** `localhost:3307`  
- **Username:** `Yohan`  
- **Password:** `Yohan`  
- **Database Name:** `inventory_system`

---

## User Accounts

### Admin
- **Username:** `Yohan`  
- **Password:** `Yohan`

### Staff
- **Username:** `Harson`  
- **Password:** `123`

---

## Setup Instructions

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/your-username/inventory_system.git
   cd inventory_system
   ```

2. **Import the Database:**
   - Use a tool like **phpMyAdmin** or the MySQL command line to import the SQL dump.
   - Connect to MySQL:
     ```bash
     mysql -u Yohan -p -P 3307
     ```
   - Create the `inventory_system` database and import the structure.

3. **Configure the Project:**
   - Open the database configuration file (e.g., `db.php`) and ensure it matches:
     ```php
     $host = 'localhost:3307';
     $username = 'Yohan';
     $password = 'Yohan';
     $database = 'inventory_system';
     ```

4. **Run the Application:**
   - Start a local server XAMPP:
     ```bash
     php -S localhost:8000
     ```
   - Open your browser and go to:  
     `http://localhost:8000`

---

## Features

- Role-based login for Admin and Staff
- Add, update, and manage inventory items
- Secure authentication system

---

