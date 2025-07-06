<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Corm Cafe - Menu Overview</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #f3e7d3;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background-color: #DFD2B6;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      border-bottom: 2px solid #8F3C15;
    }

    .logo-area {
      display: flex;
      align-items: center;
    }

    .logo-area img {
      height: 60px;
      margin-right: 12px;
    }

    .logo-area span {
      font-size: 36px;
      font-weight: bold;
      color: #2A211B;
    }

    .nav-links a {
      color: #8F3C15;
      padding: 0 20px;
      font-weight: bold;
      font-size: 16px;
      text-decoration: none;
    }

    .nav-links a:hover {
      color: #2A211B;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      background-color: #fff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #6f2c10;
      margin-bottom: 30px;
      font-size: 32px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      overflow: hidden;
      border-radius: 12px;
    }

    th, td {
      padding: 15px 12px;
      border-bottom: 1px solid #ddd;
      text-align: center;
      font-size: 16px;
    }

    th {
      background-color: #8F3C15;
      color: white;
    }

    tr:hover {
      background-color: #fdf4ea;
    }

    .no-data {
      text-align: center;
      font-size: 18px;
      color: #333;
      padding: 20px;
    }

    img.menu-pic {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 10px;
    }

    .back-btn {
      display: block;
      width: 200px;
      margin: 30px auto 0;
      padding: 12px;
      background-color: #8F3C15;
      color: white;
      text-align: center;
      text-decoration: none;
      border-radius: 10px;
      font-weight: bold;
      font-size: 16px;
    }

    .back-btn:hover {
      background-color: #6f2c10;
    }

    
    
    .main-container {
      width: 100%;
      max-width: 100vw;
      margin: 0 auto;
      background-color: #dfd2b6;
      min-height: 100vh;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
 
    .navbar .logo-area span {
      margin: 0;
      font-size: 1.8rem;
      font-weight: bold;
      color:rgb(17, 16, 16);
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #dfd2b6;
      padding: 15px 30px;
      color: white;
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
      position: relative;
      left:-50px;
    }
    
    .customer-link:hover {
      color:rgb(44, 28, 21);
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



  </style>
</head>
<body>

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

    <div class="background"> 
      <img src="asset/bg-food 1.jpg" alt="bg food" class="bg-image">
    </div>
  <div class="container">
    <h2>Our Menu</h2>

    <?php
      $dbc = mysqli_connect("localhost", "root", "", "corm");
      if (mysqli_connect_errno()) {
        die("<p class='no-data'>Failed to connect to MySQL: " . mysqli_connect_error() . "</p>");
      }

      $query = "SELECT * FROM menu";
      $result = mysqli_query($dbc, $query);

      if (mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                  <th>Picture</th>
                  <th>Name</th>
                  <th>Price (RM)</th>
                  <th>Description</th>
                  <th>Availability</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
          $imgSrc = htmlspecialchars($row['MenuImage'] ?? 'placeholder.png');
          echo "<tr>
                  <td><img src='" . $imgSrc . "' alt='Menu Image' class='menu-pic'></td>
                  <td>" . htmlspecialchars($row['MenuName']) . "</td>
                  <td>" . htmlspecialchars($row['MenuPrice']) . "</td>
                  <td>" . htmlspecialchars($row['MenuDescription']) . "</td>
                  <td>" . htmlspecialchars($row['MenuAvailability']) . "</td>
                </tr>";
        }
        echo "</table>";
      } else {
        echo "<p class='no-data'>No menu records found.</p>";
      }

      mysqli_close($dbc);
    ?>

    <a href="slide36staffhomepage.php" class="back-btn">&larr; Back to Staff Homepage</a>
  </div>

</body>
</html>


<script>
   const hamMenu = document.querySelector('.ham-menu');
const offScreenMenu = document.querySelector('.off-screen-menu');

hamMenu.addEventListener('click', () => {
  hamMenu.classList.toggle('active');
  offScreenMenu.classList.toggle('active');
});
  </script>