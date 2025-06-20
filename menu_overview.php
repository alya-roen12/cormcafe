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

    .action-btn {
      padding: 6px 12px;
      margin: 0 4px;
      border-radius: 20px;
      border: none;
      font-weight: bold;
      cursor: pointer;
      color: white;
      font-size: 14px;
      text-decoration: none;
      display: inline-block;
    }

    .update-btn {
      background-color: #2A211B;
    }

    .update-btn:hover {
      background-color: #504230;
    }

    .delete-btn {
      background-color: #8F3C15;
    }

    .delete-btn:hover {
      background-color: #6f2c10;
    }

    .add-btn {
      background-color: #3b7a57; /* a nice green */
    }

    .add-btn:hover {
      background-color: #2e5c3f;
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
      $dbc = mysqli_connect("localhost", "root", "", "corm");
      if (mysqli_connect_errno()) {
        die("<p class='no-data'>Failed to connect to MySQL: " . mysqli_connect_error() . "</p>");
      }

      // Handle delete request
      if (isset($_GET['delete'])) {
        $menuIdToDelete = intval($_GET['delete']);
        $deleteQuery = "DELETE FROM menu WHERE MenuID = $menuIdToDelete";
        mysqli_query($dbc, $deleteQuery);
        // Redirect to avoid resubmission
        header("Location: menu_overview.php");
        exit;
      }

      $query = "SELECT * FROM menu";
      $result = mysqli_query($dbc, $query);

      if (mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                  <th>Menu ID</th>
                  <th>Name</th>
                  <th>Price (RM)</th>
                  <th>Description</th>
                  <th>Availability</th>
                  <th>Actions</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
                  <td>{$row['MenuID']}</td>
                  <td>{$row['MenuName']}</td>
                  <td>{$row['MenuPrice']}</td>
                  <td>{$row['MenuDescription']}</td>
                  <td>{$row['MenuAvailability']}</td>
                  <td>
                    <a href='update_menu.php?MenuID={$row['MenuID']}' class='action-btn update-btn'>Update</a>
                    <a href='menu_overview.php?delete={$row['MenuID']}' class='action-btn delete-btn' onclick=\"return confirm('Are you sure you want to delete this menu item?');\">Delete</a>
                    <a href='addmenu.php' class='action-btn add-btn'>Add</a>
                  </td>
                </tr>";
        }
        echo "</table>";
      } else {
        echo "<p class='no-data'>No menu records found.</p>";
      }

      mysqli_close($dbc);
    ?>
  </div>

</body>
</html>
