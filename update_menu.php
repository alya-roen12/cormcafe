<?php
$dbc = mysqli_connect("localhost", "root", "", "corm");

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$menu = null;
$id = null;

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['MenuID'])) {
    $id = intval($_GET['MenuID']);
    $query = "SELECT * FROM menu WHERE MenuID = $id";
    $result = mysqli_query($dbc, $query);
    $menu = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['MenuID']);
    $name = $_POST['MenuName'];
    $price = $_POST['MenuPrice'];
    $desc = $_POST['MenuDescription'];
    $avail = $_POST['MenuAvailability'];

    $imageUpdateSQL = "";
    if (isset($_FILES['MenuImage']) && $_FILES['MenuImage']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['MenuImage'];
        $imageName = time() . "_" . basename($image['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $imageName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            $imageUpdateSQL = ", MenuImage = '$imageName'";
        }
    }

    $updateQuery = "UPDATE menu SET 
                    MenuName = '$name', 
                    MenuPrice = $price, 
                    MenuDescription = '$desc', 
                    MenuAvailability = '$avail'
                    $imageUpdateSQL
                    WHERE MenuID = $id";

    if (mysqli_query($dbc, $updateQuery)) {
        echo "<script>alert('Menu updated successfully'); window.location.href='editmenu.php';</script>";
    } else {
        echo "<script>alert('Failed to update menu');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Menu</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      margin: 0;
      padding: 0;
      position: relative;
      min-height: 100vh;
      overflow-x: hidden;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: url('background.jpg') no-repeat center center fixed;
      background-size: cover;
      opacity: 0.5;
      z-index: -1;
    }

    .main-content {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 50px 20px;
      min-height: 100vh;
    }

    .form-container {
      background-color: #fff;
      padding: 55px;
      border-radius: 20px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
      color: #6f2c10;
      margin-bottom: 30px;
      font-size: 32px;
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
      width: 100%;
    }

    label {
      font-weight: bold;
      margin-bottom: 6px;
      color: #2A211B;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    select,
    textarea {
      width: 100%;
      padding: 14px 18px;
      margin-bottom: 20px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
      font-family: inherit;
    }

    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #8F3C15;
    }

    .preview-img {
      display: block;
      margin: 0 auto 15px auto;
      width: 120px;
      height: 90px;
      object-fit: cover;
      border-radius: 10px;
      border: 1px solid #ccc;
    }

    .button-container {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    .button-container button {
      padding: 12px 30px;
      font-size: 16px;
      border-radius: 30px;
      cursor: pointer;
      border: none;
      font-weight: bold;
      width: 48%;
    }

    .btn-cancel {
      background-color: #999;
      color: white;
    }

    .btn-create {
      background-color: #8F3C15;
      color: white;
    }

    .btn-cancel:hover {
      background-color: #777;
    }

    .btn-create:hover {
      background-color: #6a1f0d;
    }
  </style>
</head>
<body>
  <div class="main-content">
    <div class="form-container">
      <h2>UPDATE MENU</h2>
      <?php if ($menu): ?>
      <form method="POST" action="update_menu.php" enctype="multipart/form-data">
        <input type="hidden" name="MenuID" value="<?= $menu['MenuID'] ?>">

        <label for="MenuName">Menu Name</label>
        <input type="text" id="MenuName" name="MenuName" value="<?= htmlspecialchars($menu['MenuName']) ?>" required>

        <label for="MenuPrice">Menu Price (RM)</label>
        <input type="number" step="0.01" id="MenuPrice" name="MenuPrice" value="<?= htmlspecialchars($menu['MenuPrice']) ?>" required>

        <label for="MenuDescription">Description</label>
        <textarea id="MenuDescription" name="MenuDescription" rows="4" required><?= htmlspecialchars($menu['MenuDescription']) ?></textarea>

        <label for="MenuAvailability">Availability</label>
        <select id="MenuAvailability" name="MenuAvailability" required>
          <option value="Available" <?= $menu['MenuAvailability'] === 'Available' ? 'selected' : '' ?>>Available</option>
          <option value="Unavailable" <?= $menu['MenuAvailability'] === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
        </select>

        <label>Current Image</label>
        <img src="uploads/<?= htmlspecialchars($menu['MenuImage']) ?>" class="preview-img" alt="Menu Image">

        <label for="MenuImage">Upload New Image</label>
        <input type="file" id="MenuImage" name="MenuImage" accept="image/*">

        <div class="button-container">
          <button type="button" class="btn-cancel" onclick="window.location.href='editmenu.php'">CANCEL</button>
          <button type="submit" class="btn-create">UPDATE</button>
        </div>
      </form>
      <?php else: ?>
        <p style="text-align:center; color:red;">No MenuID specified or item not found.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
