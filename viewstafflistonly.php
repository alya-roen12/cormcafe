<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Corm Cafe - View Staff</title>
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
      padding: 10px 20px;
      border-bottom: 2px solid #8F3C15;
    }

    .logo-area {
      display: flex;
      align-items: center;
    }

    .logo-area img {
      height: 60px;
      margin-right: 10px;
    }

    .logo-area span {
      font-size: 40px;
      font-weight: bold;
      color: #2A211B;
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

    .container {
      padding: 30px;
    }

    h2 {
      text-align: center;
      color: #6f2c10;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 15px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #8F3C15;
      color: white;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    .back-btn {
      display: block;
      width: fit-content;
      margin: 30px auto 0;
      padding: 10px 25px;
      background-color: #6f2c10;
      color: white;
      font-weight: bold;
      text-align: center;
      border-radius: 30px;
      text-decoration: none;
      font-size: 16px;
    }

    .back-btn:hover {
      background-color: #a14d25;
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
<div class="container">
  <h2>Staff List</h2>

  <?php
    $dbc = mysqli_connect("localhost", "root", "", "corm");

    if (mysqli_connect_errno()) {
      die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM staff";
    $result = mysqli_query($dbc, $query);

    if (mysqli_num_rows($result) > 0) {
      echo "<table>
              <tr>
                <th>Staff ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>House No.</th>
                <th>City</th>
                <th>Postcode</th>
                <th>State</th>
                <th>Position</th>
                <th>DOB</th>
              </tr>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['StaffID']}</td>
                <td>{$row['StaffName']}</td>
                <td>{$row['StaffPhoneNum']}</td>
                <td>{$row['StaffHouseNum']}</td>
                <td>{$row['StaffCity']}</td>
                <td>{$row['StaffPostcode']}</td>
                <td>{$row['StaffState']}</td>
                <td>{$row['StaffPosition']}</td>
                <td>{$row['StaffDOB']}</td>
              </tr>";
      }
      echo "</table>";
    } else {
      echo "<p style='text-align:center;'>No staff records found.</p>";
    }

    mysqli_close($dbc);
  ?>

  <a href="slide9editstaffhomepage.php" class="back-btn">Back</a>
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
