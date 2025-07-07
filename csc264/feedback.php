<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $rating = $_POST['star'];
  $feedbackdesc = $_POST['feedbackdesc'];

  $dbc = mysqli_connect("localhost", "root", "", "corm");
  if (mysqli_connect_errno()) {
    echo "<script>alert('Failed to connect to MySQL: " . mysqli_connect_error() . "');</script>";
  } else {
    $sql = "INSERT INTO feedback (StarRating, FeedbackDesc) VALUES ('$rating', '$feedbackdesc')";
    $result = mysqli_query($dbc, $sql);
    if ($result) {
      $_SESSION['feedback_success'] = true;
      header("Location: feedback.php");
      exit();
    } else {
      echo "<script>alert('Error submitting feedback. Please try again.');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Corm Cafe - Feedback</title>
  
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fdf1e6;
      background-image: url("food_banner.jpg");
      background-repeat: no-repeat;
      background-size: 100% auto;
      background-position: top;
    }

    /* Header/Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #dfd2b6;
      color: white;
      padding: 15px 20px;
      position: relative;
    }

    .navbar .logo-area {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .navbar .logo-area img {
      height: 45px;
      width: auto;
    }

    .navbar .logo-area span {
      margin: 0;
      font-size: 1.8rem;
      font-weight: bold;
      color:rgb(17, 16, 16);
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
      font-size: 1rem;
    }

    .customer-link:hover {
      text-decoration: underline;
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

    /* Banner */
    .top-banner {
      display: block;
      width: 100%;
      max-height: 150px;
      object-fit: cover;
      margin-bottom: 20px;
      border-style: solid;
      border-width: 2px;
      border-color: #8f3c15;
    }

    /* Feedback Form */
    .feedback-form-container {
      max-width: 400px;
      margin: 40px auto 50px;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      border: 1px solid #d0b192;
      text-align: center;
    }

    .feedback-form-container h2 {
      color: #8F3C15;
      font-size: 1.6rem;
      margin-bottom: 20px;
      text-decoration: underline;
      text-underline-offset: 8px;
    }

    .thank-you-box {
      background-color: #28a745;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      padding: 15px 20px;
      margin: 20px auto;
      display: inline-block;
      font-size: 1rem;
    }

    .service-question {
      font-size: 1.1rem;
      font-weight: 600;
      color: #8F3C15;
      margin-bottom: 15px;
    }

    .stars {
      display: flex;
      justify-content: center;
      flex-direction: row-reverse;
      margin: 15px 0 20px;
      gap: 5px;
    }

    .stars input[type="radio"] {
      display: none;
    }

    .stars label {
      font-size: 2.5rem;
      color: #d3d3d3;
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .stars input[type="radio"]:checked ~ label,
    .stars label:hover,
    .stars label:hover ~ label {
      color: #ff9900;
    }

    textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #a75f2e;
      border-radius: 8px;
      font-size: 1rem;
      resize: vertical;
      margin: 10px 0 20px;
      font-family: inherit;
      box-sizing: border-box;
      min-height: 100px;
    }

    .submit-btn,
    .home-btn {
      padding: 12px 30px;
      background-color: #8F3C15;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s ease;
      text-decoration: none;
      display: inline-block;
      margin-top: 10px;
    }

    .submit-btn:hover,
    .home-btn:hover {
      background-color: #6b3e26;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .navbar {
        padding: 10px 15px;
      }
      
      .navbar .logo-area span {
        font-size: 1.5rem;
      }
      
      .feedback-form-container {
        margin: 20px;
        padding: 20px;
      }
      
      .stars label {
        font-size: 2rem;
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

<!-- Banner Image -->
<img src="asset/homepagee.png" alt="Feedback Banner" class="top-banner">

<!-- Feedback Form Container -->
<div class="feedback-form-container">
  <h2>RESERVATION SUCCESSFUL!</h2>

  <?php
  if (isset($_SESSION['feedback_success']) && $_SESSION['feedback_success']) {
    echo '<div class="thank-you-box">Your feedback has been recorded. Thank you!</div>';
    echo '<a href="homepageaftersignin.php" class="home-btn">Back to Homepage</a>';
    unset($_SESSION['feedback_success']);
  } else {
  ?>

  <form method="POST" action="feedback.php">
    <p class="service-question"><strong>Happy with our service?</strong></p>

    <div class="stars">
      <input type="radio" id="star5" name="star" value="5" required>
      <label for="star5">&#9733;</label>
      <input type="radio" id="star4" name="star" value="4">
      <label for="star4">&#9733;</label>
      <input type="radio" id="star3" name="star" value="3">
      <label for="star3">&#9733;</label>
      <input type="radio" id="star2" name="star" value="2">
      <label for="star2">&#9733;</label>
      <input type="radio" id="star1" name="star" value="1">
      <label for="star1">&#9733;</label>
    </div>

    <p>Kindly take a moment to tell us what you think</p>
    <textarea name="feedbackdesc" rows="5" placeholder="Leave your feedback here..." required></textarea>
    
    <button type="submit" class="submit-btn">Submit Feedback</button>
  </form>

  <?php } ?>
</div>

<script>
const hamMenu = document.querySelector('.ham-menu');
const offScreenMenu = document.querySelector('.off-screen-menu');

hamMenu.addEventListener('click', () => {
  hamMenu.classList.toggle('active');
  offScreenMenu.classList.toggle('active');
});

// Close menu when clicking outside
document.addEventListener('click', (e) => {
  if (!hamMenu.contains(e.target) && !offScreenMenu.contains(e.target)) {
    hamMenu.classList.remove('active');
    offScreenMenu.classList.remove('active');
  }
});
</script>

</body>
</html>