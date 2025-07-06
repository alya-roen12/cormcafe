<?php
session_start();
$toast = $_SESSION['toast'] ?? '';
unset($_SESSION['toast']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Corm Cafe | Made with Love</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      position: relative;
      background-color: #fffaf4;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('homepagebackground.jpg') no-repeat center center/cover;
      opacity: 0.7;
      z-index: -1;
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

    .logo-area {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-area img {
      height: 40px;
    }

    .logo-area span {
      font-size: 24px;
      font-weight: bold;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      font-weight: 600;
    }

    .nav-links a:hover {
      text-decoration: underline;
    }

    .menu-icon {
      display: none;
      cursor: pointer;
      font-size: 24px;
    }

    .sidebar {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 2;
      top: 0;
      right: 0;
      background-color: #333;
      overflow-x: hidden;
      transition: 0.3s;
      padding-top: 60px;
    }

    .sidebar .closebtn {
      position: absolute;
      top: 20px;
      right: 25px;
      font-size: 36px;
      color: white;
      cursor: pointer;
    }

    .sidebar button {
      display: block;
      background: none;
      border: none;
      color: white;
      font-size: 18px;
      margin: 20px;
      cursor: pointer;
    }

    .hero {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 60px 80px;
      background-color: rgba(255, 243, 232, 0.9);
      flex-wrap: wrap;
      border-radius: 15px;
      margin: 30px;
    }

    .hero-text {
      max-width: 500px;
    }

    .hero-text h1 {
      font-size: 2.8em;
      color: #8F3C15;
      margin-bottom: 25px;
    }

    .btn.primary {
      background-color: #8F3C15;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 25px;
      text-decoration: none;
      font-weight: bold;
      font-size: 16px;
    }

    .hero-img {
      max-width: 500px;
      height: auto;
      border-radius: 15px;
    }

    .menu {
      padding: 40px 60px;
      background-color: rgba(253, 240, 230, 0.95);
      margin: 30px;
      border-radius: 15px;
    }

    .menu h2 {
      text-align: center;
      color: #8F3C15;
      font-size: 28px;
      margin-bottom: 35px;
    }

    .menu-items {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 25px;
    }

    .menu-items .item {
      text-align: center;
    }

    .menu-items .item img {
      width: 200px;
      height: 150px;
      border-radius: 10px;
    }

    .menu-items .item p {
      margin-top: 10px;
      font-weight: bold;
      font-size: 16px;
      color: #5c2d13;
    }

    /* TOAST STYLES */
    .toast-popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #8F3C15;
      color: white;
      padding: 20px 30px;
      border-radius: 12px;
      font-weight: bold;
      font-size: 16px;
      opacity: 0;
      animation: fadeInOut 3s ease-in-out forwards;
      z-index: 1000;
      text-align: center;
    }

    @keyframes fadeInOut {
      0% {
        opacity: 0;
        transform: translate(-50%, -60%);
      }

      10% {
        opacity: 1;
        transform: translate(-50%, -50%);
      }

      90% {
        opacity: 1;
        transform: translate(-50%, -50%);
      }

      100% {
        opacity: 0;
        transform: translate(-50%, -40%);
      }
    }

    @media (max-width: 768px) {
      .hero {
        flex-direction: column;
        text-align: center;
      }

      .hero-img {
        margin-top: 30px;
        max-width: 80%;
      }

      .menu-icon {
        display: block;
      }

      .nav-links {
        display: none;
      }
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
    <a href="" class="customer-link">CUSTOMER</a>
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

  <main>
    <section class="hero">
      <div class="hero-text">
        <h1>Made with love,<br>served with a smile.</h1>
        <a href="menu_list.php" class="btn primary">RESERVE NOW</a>
      </div>
      <img src="asset/homepagee.png" alt="Main Dish" class="hero-img">
    </section>

    <section class="menu">
      <h2>OUR SPECIALTIES YOU MUST TRY</h2>
      <div class="menu-items">
        <div class="item">
          <img src="uploads/matcha.jpg" alt="Matcha Latte">
          <p>Matcha Latte</p>
        </div>
        <div class="item">
          <img src="uploads/cheesypasta.jpg" alt="Cheesy Pasta">
          <p>Cheesy Pasta</p>
        </div>
        <div class="item">
          <img src="uploads/brownies.jpeg" alt="Brownies">
          <p>Brownies</p>
        </div>
        <div class="item">
          <img src="uploads/tortilla.jpg" alt="Tortilla">
          <p>Tortilla</p>
        </div>
      </div>
    </section>
  </main>

  <?php if ($toast): ?>
    <div class="toast-popup"><?= htmlspecialchars($toast) ?></div>
  <?php endif; ?>

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
