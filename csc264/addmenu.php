<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['MenuName'];
  $price = $_POST['MenuPrice'];
  $desc = $_POST['MenuDescription'];
  $availability = $_POST['MenuAvailability'];

  $image = $_FILES['MenuImage'];
  $imageName = basename($image['name']);
  $targetDir = "uploads/";
  $uniqueFileName = time() . "_" . $imageName;
  $targetFile = $targetDir . $uniqueFileName;

  $adminID = 1;

  if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
  }

  if (move_uploaded_file($image['tmp_name'], $targetFile)) {
    $dbc = mysqli_connect("localhost", "root", "", "corm");
    if (!$dbc) {
      echo "<script>alert('Database connection failed.');</script>";
    } else {
      $stmt = mysqli_prepare($dbc, "INSERT INTO menu (AdminID, MenuName, MenuPrice, MenuDescription, MenuAvailability, MenuImage) VALUES (?, ?, ?, ?, ?, ?)");
      mysqli_stmt_bind_param($stmt, "isdsss", $adminID, $name, $price, $desc, $availability, $uniqueFileName);
      $result = mysqli_stmt_execute($stmt);

      if ($result) {
        echo "<script>alert('The menu has been added.'); window.location.href='editmenu.php';</script>";
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
    body {
      font-family: Calibri, sans-serif;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }
    
   /* Header/Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #dfd2b6;
      color: white;
      padding: 15px 20px;
      position: relative;
    }

    .navbar .logo-area {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .navbar .logo-area img {
      height: 45px;
      width: auto;
    }

    .navbar .logo-area span {
      margin: 0;
      font-size: 1.8rem;
      font-weight: bold;
      color:rgb(17, 16, 16);
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .customer-link {
      color: #8f3c15;
      text-decoration: none;
      font-weight: bold;
      font-size: 1rem;
    }

    .customer-link:hover {
      text-decoration: underline;
    }
  
    /* Hamburger menu */
    .ham-menu {
      height: 50px;
      width: 50px;
      position: relative;
      cursor: pointer;
      z-index: 1001;
    }

    .ham-menu span {
      height: 4px;
      width: 100%;
      background-color: #8f3c15;
      border-radius: 25px;
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      transition: 0.3s ease;
    }

    .ham-menu span:nth-child(1) {
      top: 25%;
    }

    .ham-menu span:nth-child(2) {
      top: 50%;
      transform: translate(-50%, -50%);
    }

    .ham-menu span:nth-child(3) {
      top: 75%;
    }

    .ham-menu.active span:nth-child(1) {
      top: 50%;
      transform: translate(-50%, -50%) rotate(45deg);
    }

    .ham-menu.active span:nth-child(2) {
      opacity: 0;
    }

    .ham-menu.active span:nth-child(3) {
      top: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
    }

    /* Off screen menu */
    .off-screen-menu {
      background-color: rgb(169, 135, 96);
      height: 100vh;
      width: 100%;
      max-width: 300px;
      position: fixed;
      top: 0;
      right: -300px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      font-size: 1.2rem;
      transition: 0.3s ease;
      z-index: 1000;
    }

    .off-screen-menu.active {
      right: 0;
    }

    .off-screen-menu ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .off-screen-menu li {
      margin: 20px 0;
    }

    .off-screen-menu a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 15px 30px;
      display: block;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .off-screen-menu a:hover {
      background-color: #8F3C15;
    }

    .banner {
      width: 100%;
      height: 300px;
      background: url('interior.jpg') no-repeat center center;
      background-size: cover;
    }
    .main-content {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 50px 20px;
    }
    .form-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 20px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-container h2 {
      color: #6f2c10;
      margin-bottom: 30px;
      font-size: 32px;
      text-align: center;
    }
    .form-input, .form-select {
      width: 100%;
      padding: 15px 20px;
      margin-bottom: 20px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    .form-input:focus, .form-select:focus {
      border-color: #8F3C15;
      outline: none;
    }
    .button-container {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }
    .button-container button {
      width: 48%;
      padding: 12px 30px;
      font-size: 16px;
      border-radius: 30px;
      cursor: pointer;
      border: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
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
    @media (max-width: 600px) {
      .nav-links { display: none; }
      .form-container { padding: 25px; }
    }
  </style>
</head>

<!-- Header/Navbar -->
<div class="navbar">
  <div class="logo-area">
    <img src="asset/corm_logo_noword.png" alt="Corm Logo">
    <span>Corm</span>
  </div>
  
  <div class="nav-right">
    <a href="" class="customer-link">STAFF</a>
    <!-- Hamburger Menu -->
    <div class="ham-menu">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</div>

<!-- Off-screen Menu -->
<div class="off-screen-menu">
  <ul>
    <li><a href="slide1.html">HOME</a></li>
    <li><a href="slide3contactus.html">CONTACT US</a></li>
    <li><a href="slide4aboutus.html">ABOUT US</a></li>
    <li><a href="homepageaftersignin.php">LOGOUT</a></li>
  </ul>
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
          <button class="btn-cancel" type="button" onclick="window.location.href='editmenu.php'">CANCEL</button>
          <button class="btn-create" type="submit">ADD MENU</button>
        </div>
      </form>
    </div>
  </div>

  <script>

    const hamMenu = document.querySelector('.ham-menu');
    const offScreenMenu = document.querySelector('.off-screen-menu');

  hamMenu.addEventListener('click', () => {
  hamMenu.classList.toggle('active');
  offScreenMenu.classList.toggle('active');
});
    function openSidebar() {
      document.getElementById("mySidebar").style.width = "250px";
    }
    function closeSidebar() {
      document.getElementById("mySidebar").style.width = "0";
    }
  </script>
</body>
</html>
