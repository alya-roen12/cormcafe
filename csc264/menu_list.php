<?php
session_start();
$dbc = mysqli_connect("localhost", "root", "", "corm");
if (!$dbc) die("Database connection failed: " . mysqli_connect_error());

$toast = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'], $_POST['quantity'])) {
    $menu_id = intval($_POST['menu_id']);
    $quantity = intval($_POST['quantity']);

    $menu_query = mysqli_query($dbc, "SELECT * FROM menu WHERE MenuID = $menu_id");
    if ($menu = mysqli_fetch_assoc($menu_query)) {
        $item = [
            'MenuID' => $menu['MenuID'],
            'MenuName' => $menu['MenuName'],
            'MenuPrice' => $menu['MenuPrice'],
            'Quantity' => $quantity
        ];

        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $found = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['MenuID'] == $menu_id) {
                $cart_item['Quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = $item;
        }

        $toast = "Item added to cart!";
    }
}

$cart_count = 0;
$has_cart = false;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['Quantity'];
    }
    $has_cart = true;
}

$query = "SELECT * FROM menu";
$result = mysqli_query($dbc, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Corm Cafe - Menu List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

    /* Off screen menu */
    .off-screen-menu {
      background-color:rgb(169, 135, 96);
      height: 100vh;
      width: 100%;
      max-width: 300px;
      position: fixed;
      top: 0;
      right: -450px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      font-size: 1.5rem;
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
      padding: 10px 20px;
      display: block;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .off-screen-menu a:hover {
      background-color: #8F3C15;
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
      background-color:#8f3c15;
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

    .container {
      max-width: 1200px;
      margin: 40px auto;
      background-color: #fff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .view-cart {
      position: absolute;
      top: 30px;
      right: 30px;
      background-color: #8F3C15;
      color: white;
      padding: 10px 18px;
      border-radius: 25px;
      font-weight: bold;
      text-decoration: none;
      font-size: 15px;
    }

    .view-cart:hover {
      background-color: #a15830;
    }

    h2 {
      text-align: center;
      color: #6f2c10;
      margin-bottom: 40px;
      font-size: 32px;
    }

    .menu-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 25px;
      justify-content: center;
    }

    .menu-card {
      background-color: #fff9f3;
      width: 260px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      overflow: hidden;
      text-align: center;
      padding: 15px;
    }

    .menu-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 10px;
    }

    .menu-info h3 {
      margin: 10px 0 5px;
      color: #6f2c10;
    }

    .menu-info p {
      font-size: 14px;
      color: #4b2a1e;
      margin: 4px 0;
    }

    .menu-info form {
      margin-top: 10px;
    }

    .menu-info input[type="number"] {
      width: 50px;
      padding: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
      margin-right: 8px;
    }

    .add-btn {
      background-color: #8F3C15;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .add-btn:hover {
      background-color: #a15830;
    }

    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #8F3C15;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: bold;
      opacity: 0;
      animation: fadein 0.5s forwards, fadeout 0.5s 2.5s forwards;
      z-index: 999;
    }

    .back-btn {
      display: block;
      margin: 40px auto 0;
      width: fit-content;
      background-color: #8F3C15;
      color: white;
      padding: 10px 20px;
      border-radius: 20px;
      font-weight: bold;
      text-decoration: none;
      font-size: 16px;
      text-align: center;
    }

    .back-btn:hover {
      background-color: #a15830;
    }

    @keyframes fadein {
      to { opacity: 1; }
    }

    @keyframes fadeout {
      to { opacity: 0; }
    }
  </style>
</head>
<body>

<div class="navbar">
  <div class="logo-area">
    <img src="asset/corm_logo_noword.png" alt="Logo">
    <span>Corm</span>
  </div>
  
  <div class="nav-right">
    <a href="homepageaftersignin.php" class="customer-link">CUSTOMER</a>
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
    <li><a href="logout.html">LOGOUT</a></li>
  </ul>
</div>

<div class="container">
  <a class="view-cart" href="view_cart.php">🛒 View Cart (<?= $cart_count ?>)</a>
  <h2>Our Menu</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="menu-grid">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="menu-card">
          <img src="<?= htmlspecialchars($row['MenuImage']) ?>" alt="<?= htmlspecialchars($row['MenuName']) ?>">
          <div class="menu-info">
            <h3><?= htmlspecialchars($row['MenuName']) ?></h3>
            <p><?= htmlspecialchars($row['MenuDescription']) ?></p>
            <p><strong>RM <?= number_format($row['MenuPrice'], 2) ?></strong></p>
            <p><em><?= htmlspecialchars($row['MenuAvailability']) ?></em></p>
            <form method="POST">
              <input type="hidden" name="menu_id" value="<?= $row['MenuID'] ?>">
              <input type="number" name="quantity" min="1" value="1" required>
              <button class="add-btn" type="submit">Add to Cart</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p style="text-align:center;">No menu records found.</p>
  <?php endif; ?>

  <a href="#" class="back-btn" onclick="handleBack()">← Back to Home</a>
</div>

<?php if ($toast): ?>
  <div class="toast"><?= htmlspecialchars($toast) ?></div>
<?php endif; ?>

<script>
  const hamMenu = document.querySelector('.ham-menu');
  const offScreenMenu = document.querySelector('.off-screen-menu');
  
  hamMenu.addEventListener('click', () => {
    hamMenu.classList.toggle('active');
    offScreenMenu.classList.toggle('active');
  });
 
  function handleBack() {
    if (confirm("Are you sure you want to go back? All your cart data will be lost.")) {
      window.location.href = "clear_cart_and_redirect.php";
    }
  }
</script>

</body>
</html>