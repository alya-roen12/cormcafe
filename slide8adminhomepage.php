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

    .nav-links {
      display: flex;
      margin-left: auto;
      margin-right: 20px;
    }

    .nav-links a {
      text-decoration: none;
      color: #8F3C15;
      padding: 0 30px;
      font-weight: bold;
      font-size: 16px;
    }

    .menu-icon {
      font-size: 26px;
      cursor: pointer;
      color: #8F3C15;
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
  </style>
</head>
<body>
  <div class="navbar">
    <div class="logo-area">
      <img src="corm_logo.png" alt="Logo">
      <span>Corm</span>
    </div>
    <div class="nav-links">
      <a href="#">ADMIN</a>
      <a href="slide1.html">HOME</a>
      <a href="slide3contactus.html">CONTACT US</a>
      <a href="slide4aboutus.html">ABOUT US</a>
    </div>
    <div class="menu-icon">&#9776;</div>
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
      <button onclick="window.location.href='slide9editstaffhomepage.php'">EDIT STAFF</button>
      <button onclick="window.location.href='viewmenuonly.php'">VIEW MENU</button>
      <button onclick="window.location.href='reservation_list.php'">VIEW RESERVATION</button>
      <button onclick="window.location.href='totalsales.php'">SALES</button>
    </div>
  </div>
</body>
</html>
