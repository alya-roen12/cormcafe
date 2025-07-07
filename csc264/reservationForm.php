<?php
session_start();
$conn = new mysqli("localhost", "root", "", "corm");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['MenuPrice'] * $item['Quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ReservationName']) && !empty($cart)) {
    $name = $_POST['ReservationName'];
    $email = $_POST['ReservationEmail'];
    $phone = $_POST['RPhoneNum'];
    $purpose = $_POST['RPurpose'];
    $date = $_POST['RDate'];
    $time = $_POST['RTime'];

    // Insert into reservation table
    $stmt = $conn->prepare("INSERT INTO reservation (ReservationName, ReservationEmail, RPhoneNum, RPurpose, RDate, RTime) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $purpose, $date, $time);
    $stmt->execute();
    $reservation_id = $stmt->insert_id;
    $stmt->close();

    // Insert into orders table (now with OrderDate)
    $order_date = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO orders (ReservationID, TotalPrice, OrderDate) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $reservation_id, $total, $order_date);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert each item into order_items table
    foreach ($cart as $item) {
        $fid = $item['MenuID'];
        $fname = $item['MenuName'];
        $qty = $item['Quantity'];
        $price = $item['MenuPrice'];

        $stmt = $conn->prepare("INSERT INTO order_items (OrderID, ReservationID, FoodID, FoodName, Quantity, Price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisid", $order_id, $reservation_id, $fid, $fname, $qty, $price);
        $stmt->execute();
        $stmt->close();
    }

    // Store important session data
    $_SESSION['reservation_id'] = $reservation_id;
    $_SESSION['order_id'] = $order_id;
    $_SESSION['total_price'] = $total;
    unset($_SESSION['cart']);

    header("Location: payment_method.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Corm Cafe - Reservation Form</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #f3e7d3;
      padding: 40px;
      margin: 0;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      border-radius: 16px;
      padding: 30px 40px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #6f2c10;
      margin-bottom: 20px;
    }
    form label {
      font-weight: bold;
      color: #5e412f;
    }
    form input, select, textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }
    textarea {
      resize: vertical;
      height: 80px;
    }
    .cart-table {
      width: 100%;
      margin-bottom: 20px;
      border-collapse: collapse;
    }
    .cart-table th, .cart-table td {
      padding: 12px 16px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    .cart-table th {
      background-color: #8F3C15;
      color: #fff;
    }
    .cart-table tr:hover {
      background-color: #fef1e6;
    }
    .submit-btn {
      background-color: #8F3C15;
      color: white;
      padding: 14px 24px;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      display: block;
      width: 100%;
      margin-bottom: 15px;
    }
    .submit-btn:hover {
      background-color: #6f2c10;
    }
    .back-btn {
      background-color: #ccc;
      color: #333;
      padding: 10px 20px;
      font-size: 14px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      text-align: center;
      display: block;
      margin: auto;
      text-decoration: none;
      width: fit-content;
    }
    .back-btn:hover {
      background-color: #bbb;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Reservation Details and Order Summary</h2>
  <form method="POST">
    <label for="ReservationName">Full Name:</label>
    <input type="text" name="ReservationName" id="ReservationName" required>

    <label for="ReservationEmail">Email:</label>
    <input type="email" name="ReservationEmail" id="ReservationEmail" required>

    <label for="RPhoneNum">Phone Number:</label>
    <input type="text" name="RPhoneNum" id="RPhoneNum" required>

    <label for="RPurpose">Purpose:</label>
    <textarea name="RPurpose" id="RPurpose" required></textarea>

    <label for="RDate">Reservation Date:</label>
    <input type="date" name="RDate" id="RDate" required>

    <label for="RTime">Time:</label>
    <select name="RTime" id="RTime" required>
      <?php
        $start = strtotime("18:00");
        $end = strtotime("24:00");
        while ($start <= $end) {
            echo "<option value='" . date("H:i", $start) . "'>" . date("h:i A", $start) . "</option>";
            $start = strtotime("+30 minutes", $start);
        }
      ?>
    </select>

    <?php if (!empty($cart)): ?>
      <table class="cart-table">
        <tr>
          <th>Food</th>
          <th>Quantity</th>
          <th>Price (RM)</th>
        </tr>
        <?php foreach ($cart as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['MenuName']) ?></td>
            <td><?= (int)$item['Quantity'] ?></td>
            <td><?= number_format($item['MenuPrice'] * $item['Quantity'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <th colspan="2">Total</th>
          <th>RM <?= number_format($total, 2) ?></th>
        </tr>
      </table>
    <?php else: ?>
      <p>No items in cart. Please add items before making a reservation.</p>
    <?php endif; ?>

    <?php if (!empty($cart)): ?>
      <button type="submit" class="submit-btn">Confirm Reservation</button>
    <?php endif; ?>
  </form>

  <a href="view_cart.php" class="back-btn">← Back to View Cart</a>
</div>
</body>
</html>
