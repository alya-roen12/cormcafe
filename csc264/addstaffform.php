
<?php
session_start(); // Start session for AdminID
if (!isset($_SESSION['AdminID'])) {
  // Redirect if admin not logged in
  echo "<script>alert('Please login as admin first.'); window.location.href='adminlogin.php';</script>";
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['StaffName'];
  $phone = $_POST['StaffPhoneNum'];
  $email = $_POST['StaffEmail'];
  $password = $_POST['StaffPassword'];
  $house = $_POST['StaffHouseNum'];
  $city = $_POST['StaffPostcode'];
  $postcode = $_POST['StaffCity'];
  $state = $_POST['StaffState'];
  $position = $_POST['StaffPosition'];
  $dob = $_POST['StaffDOB'];
  $admin_id = $_SESSION['AdminID']; // Get AdminID from session

  $dbc = mysqli_connect("localhost", "root", "", "corm");
  if (mysqli_connect_errno()) {
    echo "<script>alert('Failed to connect to MySQL: " . mysqli_connect_error() . "');</script>";
  } else {
    $sql = "INSERT INTO staff 
            (StaffName, StaffPhoneNum, StaffEmail, StaffPassword, StaffHouseNum, StaffPostcode, StaffCity, StaffState, StaffPosition, StaffDOB, AdminID) 
            VALUES 
            ('$name', '$phone', '$email', '$password', '$house', '$city', '$postcode', '$state', '$position', '$dob', '$admin_id')";
    
    $result = mysqli_query($dbc, $sql);
    if ($result) {
      echo "<script>alert('Staff record added successfully.'); window.location.href='slide9editstaffhomepage.php';</script>";
    } else {
      echo "<script>alert('Error adding staff record: " . mysqli_error($dbc) . "');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Corm Cafe - Add Staff</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; }
    body { font-family: Calibri, sans-serif; background-color: #f3e7d3; min-height: 100vh; }
    .navbar { background-color: #DFD2B6; display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 2px solid #8F3C15; }
    .logo-area { display: flex; align-items: center; }
    .logo-area img { height: 60px; margin-right: 10px; }
    .logo-area span { font-size: 40px; font-weight: bold; color: #2A211B; }
    
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


    .off-screen-menu a:hover {
      background-color: #8F3C15;
    }
    .banner { width: 100%; height: 300px; background: url('homepage.png') no-repeat center center; background-size: cover; }
   .main-content { display: flex; justify-content: center; align-items: center; padding: 30px 30px; top: -250px; position: relative; }
    .form-container { background-color: #fff; padding: 40px; border-radius: 20px; max-width: 600px; width: 100%; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .form-container h2 { color: #6f2c10; margin-bottom: 30px; font-size: 32px; text-align: center; }
    .form-input, .form-select { width: 100%; padding: 15px 20px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; font-size: 16px; }
    .form-input:focus, .form-select:focus { border-color: #8F3C15; outline: none; }
    .button-container { display: flex; justify-content: space-between; margin-top: 30px; }
    .button-container button { padding: 12px 30px; font-size: 16px; border-radius: 30px; cursor: pointer; border: none; font-weight: bold; }
    .btn-cancel, .btn-create { background-color: black; color: white; }
    @media (max-width: 600px) {
      .nav-links { display: none; }
      .form-container { padding: 25px; }
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


  <div class="banner"></div>

  <div class="main-content">
    <div class="form-container">
      <h2>ADD STAFF</h2>
      <form method="POST" action="">
        <input class="form-input" name="StaffName" type="text" placeholder="Staff Name" required />
        <input class="form-input" name="StaffPhoneNum" type="tel" placeholder="Phone Number" required />
        <input class="form-input" name="StaffEmail" type="email" placeholder="Email" required />
        <input class="form-input" name="StaffPassword" type="password" placeholder="Password" required />
        <input class="form-input" name="StaffHouseNum" type="text" placeholder="House Number" required />

        <select class="form-select" name="StaffCity" id="city" required onchange="updatePostcode()">
          <option value="" disabled selected>Select Town/City</option>
          <option value="Kuala Lumpur">Kuala Lumpur</option>
          <option value="Petaling Jaya">Petaling Jaya</option>
          <option value="Shah Alam">Shah Alam</option>
          <option value="Subang Jaya">Subang Jaya</option>
          <option value="Seremban">Seremban</option>
          <option value="Nilai">Nilai</option>
          <option value="Melaka">Melaka</option>
          <option value="Ipoh">Ipoh</option>
          <option value="Taiping">Taiping</option>
          <option value="George Town">George Town</option>
          <option value="Butterworth">Butterworth</option>
          <option value="Johor Bahru">Johor Bahru</option>
          <option value="Batu Pahat">Batu Pahat</option>
          <option value="Kota Bharu">Kota Bharu</option>
          <option value="Kuala Terengganu">Kuala Terengganu</option>
          <option value="Kuantan">Kuantan</option>
          <option value="Alor Setar">Alor Setar</option>
          <option value="Sungai Petani">Sungai Petani</option>
          <option value="Kangar">Kangar</option>
          <option value="Kuching">Kuching</option>
          <option value="Miri">Miri</option>
          <option value="Kota Kinabalu">Kota Kinabalu</option>
          <option value="Sandakan">Sandakan</option>
          <option value="Putrajaya">Putrajaya</option>
          <option value="Labuan">Labuan</option>
        </select>

        <select class="form-select" name="StaffPostcode" id="postcode" required>
          <option value="" disabled selected>Select Postcode</option>
        </select>

        <select class="form-select" name="StaffState" required>
          <option value="" disabled selected>Select State</option>
          <option value="Johor">Johor</option>
          <option value="Kedah">Kedah</option>
          <option value="Kelantan">Kelantan</option>
          <option value="Melaka">Melaka</option>
          <option value="Negeri Sembilan">Negeri Sembilan</option>
          <option value="Pahang">Pahang</option>
          <option value="Penang">Penang</option>
          <option value="Perak">Perak</option>
          <option value="Perlis">Perlis</option>
          <option value="Sabah">Sabah</option>
          <option value="Sarawak">Sarawak</option>
          <option value="Selangor">Selangor</option>
          <option value="Terengganu">Terengganu</option>
          <option value="Kuala Lumpur">Kuala Lumpur</option>
          <option value="Labuan">Labuan</option>
          <option value="Putrajaya">Putrajaya</option>
        </select>

        <select class="form-select" name="StaffPosition" required>
          <option value="" disabled selected>Select Staff Position</option>
          <option value="Manager">Manager</option>
          <option value="Supervisor">Supervisor</option>
          <option value="Barista">Barista</option>
          <option value="Chef">Chef</option>
          <option value="Cleaner">Cleaner</option>
          <option value="Cashier">Cashier</option>
        </select>

        <input class="form-input" name="StaffDOB" type="date" required />

        <div class="button-container">
          <button class="btn-cancel" type="button" onclick="window.location.href='slide9editstaffhomepage.php'">BACK</button>
          <button class="btn-create" type="submit">ADD STAFF</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const hamMenu = document.querySelector('.ham-menu');
const offScreenMenu = document.querySelector('.off-screen-menu');

hamMenu.addEventListener('click', () => {
  hamMenu.classList.toggle('active');
  offScreenMenu.classList.toggle('active');
});
    const cityToPostcode = {
      'Kuala Lumpur': '50000', 'Petaling Jaya': '46000', 'Shah Alam': '40000',
      'Subang Jaya': '47500', 'Seremban': '70300', 'Nilai': '71800',
      'Melaka': '75000', 'Ipoh': '30000', 'Taiping': '34000', 'George Town': '10000',
      'Butterworth': '12000', 'Johor Bahru': '80000', 'Batu Pahat': '83000',
      'Kota Bharu': '15000', 'Kuala Terengganu': '20000', 'Kuantan': '25000',
      'Alor Setar': '05000', 'Sungai Petani': '08000', 'Kangar': '01000',
      'Kuching': '93000', 'Miri': '98000', 'Kota Kinabalu': '88000',
      'Sandakan': '90000', 'Putrajaya': '62000', 'Labuan': '87000'
    };

    function updatePostcode() {
      const cityInput = document.getElementById('city').value.trim();
      const postcodeSelect = document.getElementById('postcode');
      postcodeSelect.innerHTML = '<option value="" disabled>Select Postcode</option>';
      if (cityInput in cityToPostcode) {
        const postcode = cityToPostcode[cityInput];
        const option = document.createElement('option');
        option.value = postcode;
        option.textContent = postcode;
        option.selected = true;
        postcodeSelect.appendChild(option);
      }
    }
  </script>
</body>
</html>
