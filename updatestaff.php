<?php
$dbc = mysqli_connect("localhost", "root", "", "corm");

if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid staff ID.";
    exit;
}

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_name = $_POST['StaffName'];
    $staff_phone = $_POST['StaffPhoneNum'];
    $house_number = $_POST['StaffHouseNum'];
    $city = $_POST['StaffCity'];
    $postcode = $_POST['StaffPostcode'];
    $state = $_POST['StaffState'];
    $position = $_POST['StaffPosition'];
    $dob = $_POST['StaffDOB'];

    $update = "UPDATE staff SET 
        StaffName = '$staff_name',
        StaffPhoneNum = '$staff_phone',
        StaffHouseNum = '$house_number',
        StaffCity = '$city',
        StaffPostcode = '$postcode',
        StaffState = '$state',
        StaffPosition = '$position',
        StaffDOB = '$dob'
        WHERE StaffID = '$id'";

    if (mysqli_query($dbc, $update)) {
        echo "<script>alert('Staff updated successfully'); window.location.href='viewstaff.php';</script>";
        exit;
    } else {
        echo "Error: " . mysqli_error($dbc);
    }
}

// Get current staff data
$result = mysqli_query($dbc, "SELECT * FROM staff WHERE StaffID = '$id'");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Staff not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Update Staff</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Calibri, sans-serif;
      background-color: #f3e7d3;
      padding: 0;
      margin: 0;
    }

    .main-container {
      width: 100vw;
      min-height: 100vh;
      background-color: #dfd2b6;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      margin: 0;
      padding: 0;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #dfd2b6;
      padding: 15px 30px;
      color: white;
      width: 100%;
      position: relative;
    }

    .logo-area {
      display: flex;
      align-items: center;
    }

    .logo-area img {
      height: 60px;
      width: 60px;
      margin-right: 15px;
      border-radius: 50%;
      object-fit: cover;
    }

    .logo-area span {
      margin: 0;
      font-size: 1.8rem;
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
      position: relative;
    }
    
    .customer-link:hover {
      color: rgb(44, 28, 21);
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

    .background {
      width: 100%;
      height: 300px;
      overflow: hidden;
    }

    .bg-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
      border: 2px solid #8f3c15; 
    }

    form {
      background: white;
      padding: 30px;
      border-radius: 15px;
      width: calc(40% - 40px);
      margin: 40px auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    input, select {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    
    button {
      padding: 12px 25px;
      background-color: black;
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-weight: bold;
    }
    
    h2 {
      text-align: center;
      color: #6f2c10;
      margin-bottom: 25px;
    }
  </style>
</head>
<body>

<div class="main-container">
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

  <div class="background"> 
    <img src="asset/bg-food 1.jpg" alt="bg food" class="bg-image">
  </div>

  <form method="POST">
    <h2>Update Staff</h2>
    <input type="text" name="StaffName" value="<?= $data['StaffName']; ?>" placeholder="Staff Name" required />
    <input type="text" name="StaffPhoneNum" value="<?= $data['StaffPhoneNum']; ?>" placeholder="Phone Number" required />
    <input type="text" name="StaffHouseNum" value="<?= $data['StaffHouseNum']; ?>" placeholder="House Number" required />
    <input type="text" name="StaffCity" value="<?= $data['StaffCity']; ?>" placeholder="City" required />
    <input type="text" name="StaffPostcode" value="<?= $data['StaffPostcode']; ?>" placeholder="Postcode" required />
    <input type="text" name="StaffState" value="<?= $data['StaffState']; ?>" placeholder="State" required />
    <select name="StaffPosition" required>
      <option value="">Select Position</option>
      <option value="Barista" <?= $data['StaffPosition'] == 'Barista' ? 'selected' : '' ?>>Barista</option>
      <option value="Chef" <?= $data['StaffPosition'] == 'Chef' ? 'selected' : '' ?>>Chef</option>
      <option value="Waiter" <?= $data['StaffPosition'] == 'Waiter' ? 'selected' : '' ?>>Waiter</option>
      <option value="Manager" <?= $data['StaffPosition'] == 'Manager' ? 'selected' : '' ?>>Manager</option>
    </select>
    <input type="date" name="StaffDOB" value="<?= $data['StaffDOB']; ?>" required />
    <button type="submit">Update Staff</button>

    <div style="text-align:center; margin-top:20px;">
      <a href="viewstaff.php" style="text-decoration:none; color:#8B4513; font-weight:bold;">← Back to Staff List</a>
    </div>
  </form>
</div>

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

<?php mysqli_close($dbc); ?>