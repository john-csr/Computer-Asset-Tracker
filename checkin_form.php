<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "your_username", "your_password", "your_db_name");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tag = isset($_POST["property_tag"]) ? trim($_POST["property_tag"]) : "";
  $status = isset($_POST["status"]) ? trim($_POST["status"]) : "";
  $classroom = isset($_POST["classroom"]) ? trim($_POST["classroom"]) : "";
  $tech_issue = isset($_POST["tech_issue"]) ? trim($_POST["tech_issue"]) : "";

  if ($tag !== "") {
    $check = $conn->prepare("SELECT id FROM checkins WHERE property_tag = ?");
    $check->bind_param("s", $tag);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $update = $conn->prepare("UPDATE checkins SET status = ?, classroom = ?, tech_issue = ?, timestamp = NOW() WHERE property_tag = ?");
      $update->bind_param("ssss", $status, $classroom, $tech_issue, $tag);
      $update->execute();
    } else {
      $insert = $conn->prepare("INSERT INTO checkins (property_tag, status, classroom, tech_issue) VALUES (?, ?, ?, ?)");
      $insert->bind_param("ssss", $tag, $status, $classroom, $tech_issue);
      $insert->execute();
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Computer Check-In</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background-color: #f9f9f9;
      margin: 40px;
      color: #000;
    }

    .header {
      text-align: center;
      padding: 20px;
      background-color: #000;
      color: #fff;
      border-radius: 8px;
    }

    .nav {
      text-align: center;
      margin: 20px 0;
      background-color: #000;
      padding: 10px;
      border-radius: 8px;
    }

    .nav a {
      margin: 0 15px;
      text-decoration: none;
      color: #fff;
      font-weight: bold;
    }

    .nav a:hover {
      text-decoration: underline;
    }

    form {
      max-width: 400px;
      margin: auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input[type="text"], select, textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    input[type="submit"] {
      margin-top: 20px;
      background-color: #000;
      color: #fff;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #333;
    }

    small {
      display: block;
      margin-top: 5px;
      color: #555;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Computer Asset Tracker</h1>
  </div>

  <div class="nav">
    <a href="checkin_form.php">Check-In</a>
    <a href="checkin_overview.php">Overview</a>
    <a href="logout.php">Logout</a>

  </div>

  <form method="POST">
  <label for="status">Change Location Status:</label>
  <select name="status" required>
    <option value="In Tech Office">In Tech Office</option>
    <option value="Returned to Student">Returned to Student</option>
    <option value="Returned to Circulation Desk Fixed">Returned to Circulation Desk Fixed</option>
    <option value="Checked In Broken">Checked In Broken</option>
    <option value="Delivered by TA">Delivered by TA</option>
  </select>

  <label for="classroom">Classroom:</label>
  <input type="text" name="classroom">

  <label for="tech_issue">Tech Issue:</label>
  <textarea name="tech_issue" rows="3" placeholder="Describe the issue..."></textarea>

  <label for="property_tag">Property Tag:</label>
  <input type="text" name="property_tag" id="property_tag" required autofocus onkeydown="if(event.key === 'Enter') event.preventDefault();">
  <small>Scan or type the property tag, then press the Submit button when ready</small>

  <input type="submit" value="Submit">
</form>


</body>
</html>
