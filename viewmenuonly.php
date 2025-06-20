<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Corm Cafe - Menu List</title>
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
      margin-bottom: 15px;
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
  </style>
</head>
<body>

  <div class="navbar">
    <div class="logo-area">
      <img src="corm_logo.png" alt="Logo">
      <span>Corm</span>
    </div>
    <div class="nav-links">
      <a href="slide1.html">HOME</a>
      <a href="slide3contactus.html">CONTACT US</a>
      <a href="slide4aboutus.html">ABOUT US</a>
    </div>
  </div>

  <div class="container">
    <h2>Our Menu</h2>

    <?php
      // Connect to database
      $dbc = new mysqli("localhost", "root", "", "corm");

      // Check connection
      if ($dbc->connect_error) {
        echo "<p class='no-data'>Connection failed: " . htmlspecialchars($dbc->connect_error) . "</p>";
        exit();
      }

      // Run query
      $query = "SELECT * FROM menu";
      $result = $dbc->query($query);

      if ($result && $result->num_rows > 0) {
        echo "<table>
                <tr>
                  <th>Menu ID</th>
                  <th>Name</th>
                  <th>Price (RM)</th>
                  <th>Description</th>
                  <th>Availability</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>" . htmlspecialchars($row['MenuID']) . "</td>
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

      $dbc->close();
    ?>
  </div>

</body>
</html>
