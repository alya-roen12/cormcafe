<?php
$dbc = mysqli_connect("localhost", "root", "", "corm");
if (mysqli_connect_errno()) {
  die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$query = "SELECT * FROM reservation ORDER BY ReservationID DESC";
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
      background-color: #8F3C15;
      padding: 15px 30px;
      color: white;
    }

    .logo-area {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-area img {
      height: 40px;
    }

    .navbar nav a {
      color: white;
      margin: 0 12px;
      text-decoration: none;
      font-weight: 600;
    }

    .navbar nav a:hover {
      text-decoration: underline;
    }

    .container {
      max-width: 1100px;
      margin: 50px auto;
      padding: 20px;
    }

    h2 {
      text-align: center;
      font-size: 25px;
      margin-bottom: 40px;
      color: #5c2d13;
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

    @media (max-width: 500px) {
      .reservation-card {
        padding: 25px;
        min-height: 250px;
      }
    }
  </style>
</head>
<body>

  <div class="navbar">
    <div class="logo-area">
      <img src="corm_logo.png" alt="Corm Logo">
      <span style="font-size: 24px; font-weight: bold;">Corm Cafe</span>
    </div>
    <nav>
      <a href="homepage.html">Home</a>
      <a href="#">Contact Us</a>
      <a href="#">About Us</a>
    </nav>
  </div>

  <div class="container">
    <h2>All Reservation Details</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <div class="reservation-grid">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="reservation-card">
            <div class="info-line"><strong>ID:</strong> <?php echo htmlspecialchars($row['ReservationID']); ?></div>
            <div class="info-line"><strong>Name:</strong> <?php echo htmlspecialchars($row['ReservationName']); ?></div>
            <div class="info-line"><strong>Email:</strong> <?php echo htmlspecialchars($row['ReservationEmail']); ?></div>
            <div class="info-line"><strong>Phone:</strong> <?php echo htmlspecialchars($row['RPhoneNum']); ?></div>
            <div class="info-line"><strong>Purpose:</strong> <?php echo htmlspecialchars($row['RPurpose']); ?></div>
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
  </div>

</body>
</html>

<?php mysqli_close($dbc); ?>
