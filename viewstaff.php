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

    .nav-links a {
      color: #8F3C15;
      padding: 0 35px;
      font-weight: bold;
      font-size: 16px;
      text-decoration: none;
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

    .delete-btn {
      background-color: #8F3C15;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: #a14d25;
    }

    .button {
      padding: 8px 15px;
      background-color: #6f2c10;
      color: white;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      margin-right: 5px;
    }

    .button:hover {
      background-color: #a14d25;
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
                <th>Email</th>
                <th>Password</th>
                <th>House No.</th>
                <th>City</th>
                <th>Postcode</th>
                <th>State</th>
                <th>Position</th>
                <th>DOB</th>
                <th>Action</th>
              </tr>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['StaffID']}</td>
                <td>{$row['StaffName']}</td>
                <td>{$row['StaffPhoneNum']}</td>
                <td>{$row['StaffEmail']}</td>
                <td>{$row['StaffPassword']}</td>
                <td>{$row['StaffHouseNum']}</td>
                <td>{$row['StaffCity']}</td>
                <td>{$row['StaffPostcode']}</td>
                <td>{$row['StaffState']}</td>
                <td>{$row['StaffPosition']}</td>
                <td>{$row['StaffDOB']}</td>
                <td>
                  <a class='button' href='updatestaff.php?id={$row['StaffID']}'>Update</a>
                  <form method='post' action='deletestaffprocess.php' onsubmit='return confirm(\"Are you sure you want to delete this staff?\")' style='display:inline;'>
                    <input type='hidden' name='StaffID' value='{$row['StaffID']}'>
                    <input type='submit' class='delete-btn' value='Delete'>
                  </form>
                </td>
              </tr>";
      }
      echo "</table>";
    } else {
      echo "<p style='text-align:center;'>No staff records found.</p>";
    }

    mysqli_close($dbc);
  ?>
</div>

</body>
</html>
