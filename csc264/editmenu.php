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
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Calibri, sans-serif;
      background-color: #dfd2b6;
      min-height: 100vh;
    }

    .main-container {
      width: 100%;
      max-width: 100vw;
      margin: 0 auto;
      background-color: #dfd2b6;
      min-height: 100vh;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #dfd2b6;
      padding: 15px 30px;
      width: 100%;
      position: relative;
      z-index: 1002;
    }

    .logo-area {
      display: flex;
      align-items: center;
    }

    .logo-area img {
      height: 60px;
      width: 60px;
      margin-right: 15px;
      border-radius: 50%;
      object-fit: cover;
    }

    .logo-area span {
      font-size: 36px;
      font-weight: 700;
      color: #2A211B;
      letter-spacing: -1px;
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
      font-size: 16px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .customer-link:hover {
      color: rgb(44, 28, 21);
    }

    /* Hamburger menu */
    .ham-menu {
      height: 50px;
      width: 50px;
      position: relative;
      cursor: pointer;
      z-index: 1003;
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
      z-index: 1001;
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

    .background {
      width: 100%;
      height: 300px;
      overflow: hidden;
    }

    .bg-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
      border: 2px solid #8f3c15; 
    }

    .content-area {
      padding: 30px;
      background-color: #dfd2b6;
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
      border: 1px solid #8f3c15;
      background-color:rgb(233, 229, 224);
      color: #2A211B;
    }

    .menu-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color:rgb(225, 225, 225);
    }

    .menu-table th, .menu-table td {
      border: 1px solid #8f3c15;
      padding: 12px;
      text-align: center;
      background-color:rgb(255, 255, 255);
    }

    .menu-table th {
      background-color:rgb(255, 255, 255);
      color: #2A211B;
      font-weight: bold;
    }

    .menu-table tr:nth-child(even) {
      background-color:rgb(255, 255, 255);
    }

    .menu-table tr:hover {
      background-color: #d0c3a7;
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
  <div class="main-container">
    <!-- Header/Navbar -->
    <div class="navbar">
      <div class="logo-area">
        <img src="asset/corm_logo_noword.png" alt="Corm Logo">
        <span>Corm</span>
      </div>
      
      <div class="nav-right">
        <a href="" class="customer-link">ADMIN</a>
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

    <div class="background"> 
      <img src="asset/bg-food 1.jpg" alt="bg food" class="bg-image">
    </div>

    <div class="content-area">
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
             <td><img src="<?= htmlspecialchars($row['MenuImage']) ?>" width="80" height="60"></td>
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
    </div>
  </div>

  <script>
  const hamMenu = document.querySelector('.ham-menu');
const offScreenMenu = document.querySelector('.off-screen-menu');

hamMenu.addEventListener('click', () => {
  hamMenu.classList.toggle('active');
  offScreenMenu.classList.toggle('active');
});
  </script>
</body>
</html>