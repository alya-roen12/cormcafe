<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['MenuName'];
  $price = $_POST['MenuPrice'];
  $type = $_POST['MenuDescription'];
  $availability = $_POST['MenuAvailability'];

  $image = $_FILES['MenuImage'];
  $imageName = basename($image['name']);
  $targetDir = "uploads/";
  $uniqueFileName = time() . "_" . $imageName;
  $targetFile = $targetDir . $uniqueFileName;

  if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
  }

  if (move_uploaded_file($image['tmp_name'], $targetFile)) {
    $dbc = mysqli_connect("localhost", "root", "", "corm");
    if (!$dbc) {
      echo "<script>alert('Database connection failed.');</script>";
    } else {
      $stmt = mysqli_prepare($dbc, "INSERT INTO menu (MenuName, MenuPrice, MenuDescription, MenuAvailability, MenuImage) VALUES (?, ?, ?, ?, ?)");
      mysqli_stmt_bind_param($stmt, "sdsss", $name, $price, $type, $availability, $targetFile);
      $result = mysqli_stmt_execute($stmt);

      if ($result) {
        echo "<script>
          alert('The menu has been added.');
          window.location.href='addmenu.php';
        </script>";
      } else {
        echo "<script>alert('Failed to add menu to the database.');</script>";
      }
    }
  } else {
    echo "<script>alert('Failed to upload image.');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Corm Cafe - Add Menu</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; }
    body { font-family: Calibri, sans-serif; background-color: #f3e7d3; min-height: 100vh; }
    .navbar { background-color: #DFD2B6; display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 2px solid #8F3C15; }
    .logo-area { display: flex; align-items: center; }
    .logo-area img { height: 60px; margin-right: 10px; }
    .logo-area span { font-size: 40px; font-weight: bold; color: #2A211B; }
    .nav-links { display: flex; margin-left: auto; margin-right: 20px; }
    .nav-links a { color: #8F3C15; padding: 0 35px; font-weight: bold; font-size: 16px; }
    .menu-icon { font-size: 26px; cursor: pointer; color: #8F3C15; display: block; }
    .sidebar { height: 100%; width: 0; position: fixed; top: 0; right: 0; background-color: #A0815D; overflow-x: hidden; transition: 0.3s; padding-top: 60px; z-index: 1000; }
    .sidebar a { padding: 12px 25px; text-decoration: none; font-size: 18px; color: #8F3C15; display: block; transition: 0.2s; font-weight: bold; }
    .sidebar .closebtn { position: absolute; top: 15px; right: 20px; font-size: 30px; color: #2A211B; cursor: pointer; }
    .sidebar button { background-color: black; color: white; border: none; border-radius: 10px; padding: 10px; margin: 15px 25px; font-size: 16px; cursor: pointer; width: 100px; }
    .banner { width: 100%; height: 300px; background: url('aespa.jpeg') no-repeat center center; background-size: cover; }
    .main-content { display: flex; justify-content: center; align-items: center; padding: 50px 20px; }
    .form-container { background-color: #fff; padding: 40px; border-radius: 20px; max-width: 600px; width: 100%; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .form-container h2 { color: #6f2c10; margin-bottom: 30px; font-size: 32px; text-align: center; }
    .form-input, .form-select { width: 100%; padding: 15px 20px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; font-size: 16px; }
    .form-input:focus, .form-select:focus { border-color: #8F3C15; outline: none; }
    .button-container { display: flex; justify-content: space-between; margin-top: 30px; }
    .button-container button { padding: 12px 30px; font-size: 16px; border-radius: 30px; cursor: pointer; border: none; font-weight: bold; }
    .btn-cancel, .btn-create { background-color: black; color: white; }
    @media (max-width: 600px) {
      .nav-links { display: none; }
      .form-container { padding: 25px; }
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="logo-area">
      <img src="corm_logo.png" alt="Logo">
      <span>Corm</span>
    </div>
    <div class="nav-links">
      <a href="slide1.html">HOME</a>
      <a href="slide3contactus.html">CONTACT US</a>
      <a href="slide4aboutus.html">ABOUT US</a>
    </div>
    <div class="menu-icon" onclick="openSidebar()">&#9776;</div>
    <div id="mySidebar" class="sidebar">
      <span class="closebtn" onclick="closeSidebar()">×</span>
      <button onclick="alert('Login clicked')">Login</button>
      <button onclick="alert('Logout clicked')">Logout</button>
    </div>
  </div>

  <div class="banner"></div>

  <div class="main-content">
    <div class="form-container">
      <h2>ADD MENU</h2>
      <form method="POST" action="addmenu.php" enctype="multipart/form-data">
        <input class="form-input" name="MenuName" type="text" placeholder="Menu Name" required />
        <input class="form-input" name="MenuPrice" type="number" step="0.01" placeholder="Price (RM)" required />
        
        <select class="form-select" name="MenuDescription" required>
          <option value="" disabled selected>Select Menu Category</option>
          <option value="Pasta">Pasta</option>
          <option value="Tortilla">Tortilla</option>
          <option value="Snacks">Snacks</option>
          <option value="Dessert">Dessert</option>
          <option value="Drinks">Drinks</option>
        </select>

        <select class="form-select" name="MenuAvailability" required>
          <option value="" disabled selected>Select Availability</option>
          <option value="Available">Available</option>
          <option value="Unavailable">Unavailable</option>
        </select>

        <input class="form-input" type="file" name="MenuImage" accept="image/*" required />

        <div class="button-container">
          <button class="btn-cancel" type="button" onclick="window.location.href='slide1.html'">CANCEL</button>
          <button class="btn-create" type="submit">ADD MENU</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openSidebar() {
      document.getElementById("mySidebar").style.width = "250px";
    }
    function closeSidebar() {
      document.getElementById("mySidebar").style.width = "0";
    }
  </script>
</body>
</html>
