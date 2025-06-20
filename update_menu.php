<?php
$dbc = mysqli_connect("localhost", "root", "", "corm");

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

if (isset($_GET['MenuID'])) {
    $id = intval($_GET['MenuID']);
    $query = "SELECT * FROM menu WHERE MenuID = $id";
    $result = mysqli_query($dbc, $query);
    $menu = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['MenuName'];
    $price = $_POST['MenuPrice'];
    $desc = $_POST['MenuDescription'];
    $avail = $_POST['MenuAvailability'];

    $updateQuery = "UPDATE menu SET 
                    MenuName='$name', 
                    MenuPrice=$price, 
                    MenuDescription='$desc', 
                    MenuAvailability='$avail' 
                    WHERE MenuID=$id";
    mysqli_query($dbc, $updateQuery);
    header("Location: menu_overview.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Menu Item</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #f3e7d3;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 50px auto;
      background-color: #fff;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #6f2c10;
      margin-bottom: 30px;
      font-size: 32px;
    }

    label {
      display: block;
      margin-top: 20px;
      font-weight: bold;
      color: #2A211B;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    button {
      margin-top: 30px;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      background-color: #8F3C15;
      color: white;
      border: none;
      border-radius: 12px;
      cursor: pointer;
    }

    button:hover {
      background-color: #6f2c10;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Update Menu Item</h2>
    <form method="POST">
      <label for="MenuName">Menu Name:</label>
      <input type="text" id="MenuName" name="MenuName" value="<?= htmlspecialchars($menu['MenuName']) ?>" required>

      <label for="MenuPrice">Menu Price (RM):</label>
      <input type="number" step="0.01" id="MenuPrice" name="MenuPrice" value="<?= htmlspecialchars($menu['MenuPrice']) ?>" required>

      <label for="MenuDescription">Description:</label>
      <textarea id="MenuDescription" name="MenuDescription" rows="4" required><?= htmlspecialchars($menu['MenuDescription']) ?></textarea>

      <label for="MenuAvailability">Availability:</label>
      <select id="MenuAvailability" name="MenuAvailability" required>
        <option value="Available" <?= $menu['MenuAvailability'] == 'Available' ? 'selected' : '' ?>>Available</option>
        <option value="Not Available" <?= $menu['MenuAvailability'] == 'Not Available' ? 'selected' : '' ?>>Not Available</option>
      </select>

      <button type="submit">Update Menu</button>
    </form>
  </div>

</body>
</html>
