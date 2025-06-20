<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Corm Cafe - Payment</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Montserrat", sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      width: 100%;
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
      background: linear-gradient(135deg,#dfd2b6 0%, #f0e6d2 100%);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      border-bottom: 3px solid #d4b896;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: relative;
      z-index: 1000;
    }

    .logo-area {
      display: flex;
      align-items: center;
    }

    .logo-image {
      height: 60px;
      width: 60px;
      margin-right: 15px;
      border-radius: 50%;
      object-fit: cover;
    }

    .logo-text {
      font-size: 36px;
      font-weight: 700;
      color: #2A211B;
      letter-spacing: -1px;
    }

    .center-nav {
      display: flex;
      gap: 40px;
      /* Removed absolute positioning and centering */
      margin-right:-480px; /* Space between nav links and hamburger */
    }

    .center-nav a {
      color: #8F3C15;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 20px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .center-nav a:hover {
      background-color: #8F3C15;
      color: white;
      transform: translateY(-2px);
    }

    /* Hide the original right-nav completely */
    .right-nav {
      display: none;
    }

    /* Hamburger menu - always visible */
    .hamburger {
      display: flex;
      flex-direction: column;
      cursor: pointer;
      padding: 10px;
      z-index: 1001;
    }

    .hamburger span {
      width: 25px;
      height: 3px;
      background-color: #8F3C15;
      margin: 3px 0;
      transition: 0.3s;
    }

    /* Hamburger menu content - hidden by default */
    .hamburger-menu {
      display: none;
      position: absolute;
      top: 100%;
      right: 0;
      background-color: #e8dcc0;
      min-width: 200px;
      border-radius: 0 0 15px 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      overflow: hidden;
      z-index: 999;
    }

    .hamburger-menu.active {
      display: block;
    }

    .hamburger-menu a {
      display: block;
      color: #8F3C15;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      padding: 15px 20px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 1px solid rgba(143, 60, 21, 0.1);
    }

    .hamburger-menu a:last-child {
      border-bottom: none;
    }

    .hamburger-menu a:hover {
      background-color: #8F3C15;
      color: white;
    }

    .hamburger-menu .login-btn {
      background-color: #8F3C15;
      color: white !important;
    }

    .hamburger-menu .login-btn:hover {
      background-color: #7a3418;
      color: white !important;
    }

    .hamburger-menu .logout-btn {
      background-color: transparent;
      color: #8F3C15 !important;
      border-top: 2px solid #8F3C15;
      margin-top: 1px;
    }

    .hamburger-menu .logout-btn:hover {
      background-color: #8F3C15;
      color: white !important;
    }

    .background {
      width: 100%;
      height: 300px;
      overflow: hidden;
      position: relative;
    }

    .hmp {
      width: 100%;
      height: 100%;
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
      padding: 40px;
      background: #dfd2b6;
      min-height: calc(100vh - 300px);
    }
    
    .payment-header {
      background: white;
      color: #8f3c15;
      padding: 20px 30px;
      border-radius: 15px 15px 0 0;
      border: 2px solid #dfdbdb;
      font-size: 24px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 4px 15px rgba(143, 60, 21, 0.3);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .search-container {
      background: white;
      padding: 20px 30px;
      border-left: 3px solid #dfdbdb;
      border-right: 3px solid#dfdbdb;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }

    .search-box {
      position: relative;
      width: 300px;
    }

    .search-box input {
      width: 100%;
      padding: 12px 45px 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 25px;
      font-size: 14px;
      transition: all 0.3s ease;
      background-color: #f9f9f9;
    }

    .search-box input:focus {
      outline: none;
      border-color: #8F3C15;
      background-color: white;
      box-shadow: 0 0 10px rgba(143, 60, 21, 0.2);
    }

    .search-icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      font-size: 16px;
    }

    .table-container {
      background: #f7f7f7;
      border-radius: 0 0 15px 15px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      border-left: 3px solid #dfdbdb;
      border-right: 3px solid #dfdbdb;
      border-bottom: 3px solid #dfdbdb;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%);
      color: #333;
      padding: 18px 15px;
      text-align: center;
      font-weight: 600;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #ddd;
    }

    td {
      padding: 18px 15px;
      text-align: center;
      border-bottom: 1px solid #f0f0f0;
      font-size: 14px;
      color: #333;
    }

    tr:nth-child(even) {
      background-color: #fafafa;
    }

    tr:hover {
      background-color: #f5f5f5;
      transform: scale(1.001);
      transition: all 0.2s ease;
    }

    .customer-id {
      font-weight: 600;
      color: #8F3C15;
      font-size: 15px;
    }

    .payment-id {
      font-weight: 500;
      color: #666;
    }

    .amount {
      font-weight: 700;
      color: #6a6a6a;
      font-size: 15px;
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-successful {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .status-pending {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .status-unsuccessful {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .payment-type {
      font-weight: 500;
      padding: 4px 8px;
      border-radius: 8px;
      background-color: #f8f9fa;
      color: #495057;
      font-size: 12px;
    }

    .no-records {
      text-align: center;
      padding: 60px 20px;
      color: #666;
      font-size: 18px;
      background: white;
    }

    /* Responsive Design for Large Screens */
    @media (min-width: 1920px) {
      .main-container {
        max-width: 1800px;
        margin: 0 auto;
      }
      
      .content-area {
        padding: 60px;
      }
      
      .background {
        height: 400px;
      }
      
      .logo-text {
        font-size: 42px;
      }
      
      .payment-header {
        font-size: 28px;
        padding: 25px 40px;
      }
    }

    /* Responsive Design for Medium Laptops */
    @media (max-width: 1366px) {
      .content-area {
        padding: 30px;
      }
      
      .background {
        height: 250px;
      }
      
      .logo-text {
        font-size: 32px;
      }
      
      .center-nav {
        gap: 20px;
      }
      
      .center-nav a {
        font-size: 13px;
        padding: 6px 14px;
      }
    }

    /* Responsive Design for Small Laptops */
    @media (max-width: 1024px) {
      .navbar {
        padding: 12px 20px;
      }
      
      .logo-text {
        font-size: 28px;
      }
      
      .logo-image {
        height: 50px;
        width: 50px;
      }
      
      .center-nav {
        gap: 15px;
      }
      
      .center-nav a {
        font-size: 12px;
        padding: 6px 12px;
      }
      
      .content-area {
        padding: 25px;
      }
      
      .background {
        height: 200px;
      }
      
      .search-box {
        width: 250px;
      }
    }

    /* Responsive Design for Tablets and Mobile */
    @media (max-width: 768px) {
      .center-nav {
        position: static;
        transform: none;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: center;
      }
      
      .center-nav a {
        margin: 2px 0;
        font-size: 11px;
        padding: 5px 10px;
      }
      
      .logo-text {
        font-size: 24px;
      }
      
      .logo-image {
        height: 40px;
        width: 40px;
        margin-right: 10px;
      }
      
      .content-area {
        padding: 20px;
      }
      
      .background {
        height: 180px;
      }
      
      .search-container {
        justify-content: center;
        padding: 15px;
      }
      
      .search-box {
        width: 100%;
        max-width: 300px;
      }
      
      .table-container {
        overflow-x: auto;
      }
      
      table {
        min-width: 600px;
      }
      
      th, td {
        padding: 12px 8px;
        font-size: 12px;
      }
      
      .payment-header {
        font-size: 20px;
        padding: 15px 20px;
        flex-direction: column;
        gap: 15px;
      }
    }

    /* Mobile Portrait */
    @media (max-width: 480px) {
      .navbar {
        padding: 10px 15px;
        flex-wrap: wrap;
      }
      
      .logo-text {
        font-size: 20px;
      }
      
      .logo-image {
        height: 35px;
        width: 35px;
        margin-right: 8px;
      }
      
      .center-nav {
        order: 3;
        width: 100%;
        margin-top: 10px;
        gap: 10px;
      }
      
      .center-nav a {
        font-size: 10px;
        padding: 4px 8px;
      }
      
      .content-area {
        padding: 15px;
      }
      
      .background {
        height: 150px;
      }
      
      .payment-header {
        font-size: 18px;
        padding: 12px 15px;
      }
      
      .search-container {
        padding: 10px;
      }
      
      th, td {
        padding: 8px 4px;
        font-size: 11px;
      }
      
      .search-box input {
        padding: 10px 35px 10px 12px;
        font-size: 13px;
      }
    }

    /* Very Large Screens */
    @media (min-width: 2560px) {
      .main-container {
        max-width: 2200px;
      }
      
      .background {
        height: 500px;
      }
      
      .content-area {
        padding: 80px;
      }
      
      .logo-text {
        font-size: 48px;
      }
      
      .payment-header {
        font-size: 32px;
        padding: 30px 50px;
      }
      
      .center-nav a {
        font-size: 16px;
        padding: 10px 20px;
      }
    }
  </style>
</head>
<body>

<div class="main-container">
  <div class="navbar">
    <div class="logo-area">
      <img src="asset/corm_logo_noword.png" alt="Corm Logo" class="logo-image">
      <span class="logo-text">Corm</span>
    </div>
    
    <div class="center-nav">
      <a href="#admin">ADMIN</a>
      <a href="#home">HOME</a>
      <a href="#contact">CONTACT US</a>
      <a href="#about">ABOUT US</a>
    </div>
    
    <div class="hamburger" onclick="toggleMenu()">
      <span></span>
      <span></span>
      <span></span>
    </div>

    <!-- Hamburger menu content -->
    <div class="hamburger-menu" id="hamburgerMenu">
      <a href="login.php" class="login-btn">LOGIN</a>
      <a href="logout.php" class="logout-btn">LOGOUT</a>
    </div>
    
    <!-- Hidden original right-nav (for PHP compatibility) -->
    <div class="right-nav" style="display: none;">
      <a href="login.php" class="login-btn">LOGIN</a>
      <a href="logout.php" class="logout-btn">LOGOUT</a>
    </div>
  </div>

  <div class="background"> 
    <div class="hmp">
      <img src="asset/bg-food 1.jpg" alt="bg food" class="bg-image">
    </div>
  </div>

  <div class="content-area">
    <div class="payment-header">
      PAYMENT
      <div class="search-box">
        <form method="GET" action="">
          <input type="text" 
                 name="search" 
                 placeholder="customer ID" 
                 value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
          <span class="search-icon">
            <img src="asset/search-icon.png" alt="Search" width="17" height="17">
          </span>
        </form>
      </div>
    </div>

    <?php
    // Database connection
    $host = "localhost";
    $user = "root";
    $password = ""; // adjust if needed
    $dbname = "corm";

    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $search = "";
    $sql = "SELECT * FROM payment";

    if (isset($_GET['search']) && $_GET['search'] !== '') {
      $search = $conn->real_escape_string($_GET['search']);
      $sql .= " WHERE CustID LIKE '%$search%'";
    }

    $result = $conn->query($sql);
    ?>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Payment ID</th>
            <th>Customer ID</th>
            <th>Payment Date</th>
            <th>Payment Type</th>
            <th>Payment Amount (RM)</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td class="payment-id"><?php echo htmlspecialchars($row['PaymentID']); ?></td>
                <td class="customer-id"><?php echo htmlspecialchars($row['CustID']); ?></td>
                <td><?php echo htmlspecialchars($row['PaymentDate']); ?></td>
                <td class="payment-type"><?php echo htmlspecialchars($row['PaymentType']); ?></td>
                <td class="amount"><?php echo number_format($row['PaymentAmount'], 2); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="no-records">No payment records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php $conn->close(); ?>
  </div>
</div>

<script>
function toggleMenu() {
  const hamburgerMenu = document.getElementById('hamburgerMenu');
  hamburgerMenu.classList.toggle('active');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
  const hamburger = document.querySelector('.hamburger');
  const hamburgerMenu = document.getElementById('hamburgerMenu');
  
  if (!hamburger.contains(event.target) && !hamburgerMenu.contains(event.target)) {
    hamburgerMenu.classList.remove('active');
  }
});

// Enhanced search functionality
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.querySelector('.search-box input');
  const searchForm = document.querySelector('.search-box form');
  
  // Submit form on Enter key
  searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      searchForm.submit();
    }
  });
  
  // Optional: Auto-submit after typing stops (debounced)
  let searchTimeout;
  searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const searchValue = this.value.trim();
    
    // Only auto-submit if there's a meaningful search term or if clearing the search
    if (searchValue.length >= 2 || searchValue.length === 0) {
      searchTimeout = setTimeout(() => {
        searchForm.submit();
      }, 500); // Wait 500ms after user stops typing
    }
  });
});
</script>

</body>
</html>