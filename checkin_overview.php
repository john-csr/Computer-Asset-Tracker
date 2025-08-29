<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "your_username", "your_password", "your_db_name");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM checkins ORDER BY timestamp DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Check-In Overview</title>
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

    h2 {
      text-align: center;
      color: #000;
      margin-bottom: 20px;
    }

    .search-bar {
      text-align: center;
      margin-bottom: 30px;
    }

    .search-bar input {
      padding: 8px;
      width: 250px;
      font-size: 16px;
    }

    .search-bar button {
      padding: 8px 12px;
      font-size: 16px;
      margin-left: 10px;
      cursor: pointer;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
      vertical-align: top;
    }

    th {
      background-color: #eee;
    }

    .empty {
      text-align: center;
      font-style: italic;
      color: #666;
      margin-top: 20px;
    }

    .highlight {
      background-color: #ffff99 !important;
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

  <h2>Recent Check-Ins</h2>

  <div class="search-bar">
    <input type="text" id="searchTag" placeholder="Scan or type Property Tag" onkeydown="if(event.key === 'Enter') searchTag();">
<button onclick="searchTag()">Search</button>

  </div>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>Timestamp</th>
        <th>Property Tag</th>
        <th>Status</th>
        <th>Classroom</th>
        <th>Tech Issue</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr id="tag-<?= htmlspecialchars($row['property_tag']) ?>">
          <td><?= htmlspecialchars($row['timestamp']) ?></td>
          <td><?= htmlspecialchars($row['property_tag']) ?></td>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= htmlspecialchars($row['classroom']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['tech_issue'] ?? '')) ?></td>

        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p class="empty">No check-ins recorded yet.</p>
  <?php endif; ?>

  <script>
    function searchTag() {
      const tag = document.getElementById("searchTag").value.trim();
      if (!tag) return;

      document.querySelectorAll(".highlight").forEach(el => el.classList.remove("highlight"));

      const row = document.getElementById("tag-" + tag);
      if (row) {
        row.classList.add("highlight");
        row.scrollIntoView({ behavior: "smooth", block: "center" });
      } else {
        alert("Property tag not found.");
      }
    }
  </script>

</body>
</html>
