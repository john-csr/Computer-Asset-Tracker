

# Computer Asset Tracker System  
**Created by John C. — 8-23-25**

License

This project is licensed under the MIT License

## Overview  
This web-based application streamlines the check-in and tracking of computer assets in educational or institutional environments. Built using PHP, MySQL, and hosted on IIS, it provides a secure and efficient interface for technicians to log devices, update statuses, and review asset history.

## Features  
- Secure login system with session-based access control  
- Device check-in form with:
  - Property Tag (scanner-compatible)
  - Repair Status
  - Classroom Location
  - Technical Issue Description  
- Overview dashboard:
  - Searchable and sortable check-in records
  - Auto-scroll and highlight on barcode scan  
- Scanner-friendly design for fast data entry  
- Automatic record update if a device is already checked in  
- Simple logout functionality to end user sessions  

## Technology Stack

| Component     | Description         |
|---------------|---------------------|
| IIS           | Web server          |
| PHP           | Backend scripting   |
| MySQL         | Relational database |
| HTML/CSS/JS   | Frontend interface  |

## Deployment Guide

### Requirements  
- Windows Server or PC with IIS  
- PHP installed and configured  
- MySQL Server running locally or remotely  

### Database Setup  
Create a MySQL database with a name of your choosing (e.g., `asset_tracker_db`) and run the following SQL:

```sql
CREATE TABLE checkins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  property_tag VARCHAR(255) NOT NULL,
  status VARCHAR(255),
  classroom VARCHAR(255),
  tech_issue TEXT,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
);
```

Be sure to update your PHP connection code to reflect your chosen database name.

## File Structure

```
computer_asset_tracker/
├── login.php
├── logout.php
├── checkin_form.php
├── checkin_overview.php
├── process_checkin.php
├── create_user.php
└── db_config.php (optional)
```

## IIS Configuration  
- Create a site or virtual directory pointing to your project folder  
- Ensure PHP is mapped correctly via FastCGI  
- Set default document to `login.php` or `checkin_form.php`  

## Scanner Setup  
- Configure scanner to input as keyboard  
- Check-In Page: Disable auto-enter if manual submission is preferred  
- Overview Page: Enable auto-enter for instant search  

## User Management

To create an initial user account:

1. Save the following script as `create_user.php` and update the database name accordingly:

```php
<?php
$conn = new mysqli("localhost", "root", "your_password", "your_database_name");
if ($conn->connect_error) die("Connection failed");

$username = "admin"; // change as needed
$password = "Password#01"; // change as needed
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hash);
$stmt->execute();

echo "User created successfully.";
?>
```

2. Open your browser and go to `http://localhost/create_user.php`  
3. You should see: "User created successfully."  
4. Delete or restrict access to this file after use  

## Logout Functionality

To allow users to log out securely, create a file named `logout.php` with the following code:

```php
<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>
```

Then, add a logout link to your navigation bar:

```html
<a href="logout.php">Logout</a>
```

This will end the session and redirect the user to the login page.

## Security Notes  
- Use HTTPS in production  
- Sanitize all inputs using `htmlspecialchars()` and prepared statements  
- Restrict access to admin pages via session checks  
- Prevent browser caching of protected pages using headers:

```php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
```

## Testing Checklist  
- Test login/logout flow  
- Submit a check-in with all fields  
- Scan a property tag on the overview page and verify auto-scroll  
- Try updating an existing record to confirm overwrite logic  

## Summary: Getting Everything Working  
1. Install IIS, PHP, and MySQL  
2. Create your database and tables  
3. Place all PHP files in your IIS project folder  
4. Configure IIS and set default document  
5. Run `create_user.php` in browser to create admin account  
6. Test login and check-in functionality  
7. Add and test `logout.php`  
8. Set up barcode scanner preferences  
9. Secure the site with HTTPS and input validation  


