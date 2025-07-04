<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Corm Cafe - Admin Panel</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Calibri, sans-serif;
      background-color: #f3e7d3;
    }

    .navbar {
      background-color: #DFD2B6;
      display: flex;
      justify-content: space-around;
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


    .banner {
      width: 100%;
      height: 300px;
      background: url('homepage.png') no-repeat center center;
      background-size: cover;
    }

    .main-section {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px;
    }

    .main-text {
      font-size: 50px;
      font-weight: bold;
      color: #8F3C15;
      max-width: 400px;
      margin-right: 30px;
    }

    .admin-panel {
      text-align: center;
    }

    .admin-logo img {
      width: 100px;
      margin-bottom: 10px;
    }

    .admin-panel h2 {
      font-size: 28px;
      margin-bottom: 30px;
      color: #2A211B;
    }

    .admin-panel button {
      display: block;
      margin: 10px auto;
      padding: 15px 30px;
      border: none;
      border-radius: 30px;
      background-color: #2A211B;
      color: white;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      width: 200px;
    }

    .admin-panel button:hover {
      background-color: #8F3C15;
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


  <div class="banner"></div>

  <div class="main-section">
    <div class="main-text">
      Made with love,<br />
      served with a smile.
    </div>
    <div class="admin-panel">
      <div class="admin-logo">
        <img src="corm_logo.png" alt="Corm Logo">
      </div>
      <h2>Corm</h2>
      <button onclick="window.location.href='addstaffform.php'">ADD STAFF</button>
      <button onclick="window.location.href='viewstaff.php'">UPDATE AND DELETE STAFF</button>
      <button onclick="window.location.href='viewstafflistonly.php'">VIEW STAFF</button>
      <button onclick="window.location.href='slide8adminhomepage.php'">BACK</button>
    </div>
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
