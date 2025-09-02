


<?php
$conn = new mysqli("localhost", "your_username", "your_password", "your_db_name");
if ($conn->connect_error) die("Connection failed");

$username = "admin"; // change as needed
$password = "Your_password"; // change as needed
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hash);
$stmt->execute();

echo "User created successfully.";
?>
