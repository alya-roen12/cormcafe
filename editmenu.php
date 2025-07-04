<?php
$conn = new mysqli("localhost", "root", "", "corm");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct categories for dropdown
$categoryResult = $conn->query("SELECT DISTINCT MenuDescription FROM menu");

// Check if filter is applied
$filter = "";
if (isset($_GET['category']) && $_GET['category'] !== '') {
    $selectedCategory = $conn->real_escape_string($_GET['category']);
    $filter = "WHERE MenuDescription = '$selectedCategory'";
} else {
    $selectedCategory = '';
}

$result = $conn->query("SELECT * FROM menu $filter");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin - Manage Menu</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #fffaf0;
      padding: 30px;
    }

    h1 {
      text-align: center;
      color: #8F3C15;
      margin-bottom: 20px;
    }

    .filter-container {
      text-align: center;
      margin-bottom: 20px;
    }

    select {
      padding: 10px 15px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .menu-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .menu-table th, .menu-table td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: center;
    }

    .menu-table th {
      background-color: #f0d8c4;
    }

    .menu-table img {
      border-radius: 8px;
      object-fit: cover;
    }

    .action-buttons a {
      margin: 0 5px;
      padding: 6px 12px;
      background-color: #8F3C15;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-size: 14px;
    }

    .action-buttons a:hover {
      background-color: #5e1e0a;
    }

    .top-btn {
      display: block;
      width: 200px;
      margin: 20px auto;
      padding: 10px;
      background-color: #2A211B;
      color: white;
      text-align: center;
      border-radius: 8px;
      font-weight: bold;
      text-decoration: none;
    }

    .top-btn:hover {
      background-color: #4a372d;
    }
  </style>
</head>
<body>

  <h1>MENU MANAGEMENT</h1>

  <!-- Top add button -->
  <a class="top-btn" href="addmenu.php">➕ Add New Menu</a>

  <!-- Filter dropdown -->
  <div class="filter-container">
    <form method="GET" action="">
      <label for="category">Filter by Category:</label>
      <select name="category" id="category" onchange="this.form.submit()">
        <option value="">-- All Categories --</option>
        <?php while ($cat = $categoryResult->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars($cat['MenuDescription']) ?>" <?= $selectedCategory == $cat['MenuDescription'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['MenuDescription']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </form>
  </div>

  <!-- Menu Table -->
  <table class="menu-table">
    <tr>
      <th>Image</th>
      <th>Name</th>
      <th>Price</th>
      <th>Description</th>
      <th>Availability</th>
      <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><img src="uploads/<?= htmlspecialchars($row['MenuImage']) ?>" width="80" height="60"></td>
        <td><?= htmlspecialchars($row['MenuName']) ?></td>
        <td>RM <?= number_format($row['MenuPrice'], 2) ?></td>
        <td><?= htmlspecialchars($row['MenuDescription']) ?></td>
        <td><?= htmlspecialchars($row['MenuAvailability']) ?></td>
        <td class="action-buttons">
          <a href="update_menu.php?MenuID=<?= $row['MenuID'] ?>">✏️ Update</a>
          <a href="delete_menu.php?id=<?= $row['MenuID'] ?>" onclick="return confirm('Do you really want to delete this menu?')">🗑️ Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <!-- Back Button -->
  <a class="top-btn" href="slide8adminhomepage.php">⬅️ Back</a>

</body>
</html>
