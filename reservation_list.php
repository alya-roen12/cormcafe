<?php
$dbc = mysqli_connect("localhost", "root", "", "corm");
if (mysqli_connect_errno()) {
  die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Default sorting
$sort_order = $_GET['sort'] ?? 'desc';
$sort_sql = ($sort_order === 'asc') ? 'ASC' : 'DESC';

$query = "SELECT * FROM reservation ORDER BY RDate $sort_sql, RTime $sort_sql";
$result = mysqli_query($dbc, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Reservations - Corm Cafe</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #fdf1e6;
      color: #3b2f2f;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #dfd2b6;
      padding: 15px 30px;
      color: white;
      height: 60px; /* Fixed height for navbar */
    }

    .logo-area {
      display: flex;
      align-items: center;
      gap: 10px;
      height: 100%;
    }

    .logo-area img {
      height: 35px; /* Smaller logo size */
      width: auto; /* Maintain aspect ratio */
      object-fit: contain;
    }

    .logo-area span {
      margin: 0;
      font-size: 1.5rem; /* Slightly smaller text */
      font-weight: bold;
      color: rgb(17, 16, 16);
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
      color:rgb(44, 28, 21);
    }

     /* Hamburger menu */
    .ham-menu {
      height: 40px; /* Slightly smaller hamburger */
      width: 40px;
      position: relative;
      cursor: pointer;
      z-index: 1001;
    }

    .ham-menu span {
      height: 3px; /* Thinner lines */
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
      max-width: 1100px;
      margin: 50px auto;
      padding: 20px;
    }

    h2 {
      text-align: center;
      font-size: 25px;
      margin-bottom: 20px;
      color: #5c2d13;
    }

    .filter-form {
      text-align: center;
      margin-bottom: 30px;
    }

    .filter-form select {
      padding: 8px 14px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .reservation-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
      gap: 25px;
    }

    .reservation-card {
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      position: relative;
      transition: transform 0.3s, box-shadow 0.3s;
      min-height: 230px;
    }

    .reservation-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(0,0,0,0.15);
    }

    .info-line {
      margin: 10px 0;
      font-size: 16px;
      word-wrap: break-word;
    }

    .date-time-tag {
      background-color: #f0d7c2;
      color: #70412d;
      font-size: 0.9rem;
      padding: 6px 12px;
      border-radius: 20px;
      position: absolute;
      bottom: 20px;
      right: 25px;
      font-weight: bold;
    }

    .no-data {
      text-align: center;
      color: #6b3e26;
      font-size: 1.2rem;
      margin-top: 40px;
    }

    .back-button {
      text-align: center;
      margin-top: 60px;
    }

    .back-button a {
      display: inline-block;
      padding: 12px 28px;
      background-color: #8F3C15;
      color: white;
      text-decoration: none;
      border-radius: 30px;
      font-size: 16px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .back-button a:hover {
      background-color: #6b3e26;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .navbar {
        padding: 10px 20px;
      }
      
      .logo-area img {
        height: 30px;
      }
      
      .logo-area span {
        font-size: 1.3rem;
      }
    }

    @media (max-width: 500px) {
      .reservation-card {
        padding: 25px;
        min-height: 250px;
      }
      
      .navbar {
        padding: 8px 15px;
      }
      
      .logo-area img {
        height: 28px;
      }
      
      .logo-area span {
        font-size: 1.2rem;
      }
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
  <div class="container">
    <h2>All Reservation Details</h2>

    <!-- Filter Form -->
    <form method="GET" class="filter-form">
      <label for="sort">Sort by Date:</label>
      <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="desc" <?= $sort_order === 'desc' ? 'selected' : '' ?>>Newest First</option>
        <option value="asc" <?= $sort_order === 'asc' ? 'selected' : '' ?>>Oldest First</option>
      </select>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <div class="reservation-grid">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="reservation-card">
            <div class="info-line"><strong>ID:</strong> <?= htmlspecialchars($row['ReservationID']); ?></div>
            <div class="info-line"><strong>Name:</strong> <?= htmlspecialchars($row['ReservationName']); ?></div>
            <div class="info-line"><strong>Email:</strong> <?= htmlspecialchars($row['ReservationEmail']); ?></div>
            <div class="info-line"><strong>Phone:</strong> <?= htmlspecialchars($row['RPhoneNum']); ?></div>
            <div class="info-line"><strong>Purpose:</strong> <?= htmlspecialchars($row['RPurpose']); ?></div>
            <div class="date-time-tag">
              <?php
                $datetime = new DateTime($row['RDate'] . ' ' . $row['RTime']);
                echo $datetime->format('d M Y, h:i A');
              ?>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="no-data">No reservation records found.</p>
    <?php endif; ?>

    <div class="back-button">
      <a href="slide8adminhomepage.php">&larr; Back</a>
    </div>
  </div>

</body>
</html>

<?php mysqli_close($dbc); ?>

<script>
  const hamMenu = document.querySelector('.ham-menu');
const offScreenMenu = document.querySelector('.off-screen-menu');

hamMenu.addEventListener('click', () => {
  hamMenu.classList.toggle('active');
  offScreenMenu.classList.toggle('active');
});
  </script>