<?php
session_start();
$conn = new mysqli("localhost", "root", "", "corm");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reservation_id = $_SESSION['reservation_id'] ?? null;
$order_id = $_SESSION['order_id'] ?? null;
$total_price = $_SESSION['total_price'] ?? 0;
$cust_id = $_SESSION['CustID'] ?? null;
$payment_method = $_SESSION['payment_method'] ?? 'Not specified';
$payment_date = date("Y-m-d H:i:s");

if ($order_id && $reservation_id) {
    // INSERT into payment table
    $stmt = $conn->prepare("INSERT INTO payment (CustID, ReservationID, OrderID, PaymentMethod, PaymentTotal, PaymentDate) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisds", $cust_id, $reservation_id, $order_id, $payment_method, $total_price, $payment_date);
    $stmt->execute();
    $stmt->close();
}

// FETCH data for display
$reservation = $order = $payment = [];
$order_items = [];

// Fetch reservation
$stmt = $conn->prepare("SELECT * FROM reservation WHERE ReservationID = ?");
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$res = $stmt->get_result();
$reservation = $res->fetch_assoc();
$stmt->close();

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE OrderID = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
$order = $res->fetch_assoc();
$stmt->close();

// Fetch items
$stmt = $conn->prepare("SELECT * FROM order_items WHERE OrderID = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $order_items[] = $row;
}
$stmt->close();

// Fetch payment
$stmt = $conn->prepare("SELECT * FROM payment WHERE OrderID = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
$payment = $res->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Payment Success - Corm Cafe</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #f2e8dc;
      padding: 50px;
      color: #3e1f0d;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background: #fffdf8;
      padding: 40px;
      border-radius: 16px;
      text-align: left;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    h1 {
      color: #6b3000;
      text-align: center;
      margin-bottom: 20px;
    }
    h2 {
      margin-top: 30px;
      color: #7b3f00;
    }
    p {
      font-size: 17px;
      margin: 8px 0;
    }
    .success {
      color: green;
      font-weight: bold;
      font-size: 20px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
    }
    th, td {
      border-bottom: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #7b3f00;
      color: white;
    }
    .btn {
      margin-top: 30px;
      padding: 12px 24px;
      font-size: 16px;
      background-color: #7b3f00;
      color: white;
      border: none;
      border-radius: 10px;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }
    .btn:hover {
      background-color: #5a2d00;
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Payment Success</h1>
  <p class="success">✅ Your payment has been successfully processed!</p>

  <h2>Payment Details</h2>
  <p><strong>Payment Method:</strong> <?= htmlspecialchars($payment['PaymentMethod']) ?></p>
  <p><strong>Total Paid:</strong> RM <?= number_format($payment['PaymentTotal'], 2) ?></p>
  <p><strong>Payment Date:</strong> <?= htmlspecialchars($payment['PaymentDate']) ?></p>

  <h2>Reservation Info</h2>
  <p><strong>Name:</strong> <?= htmlspecialchars($reservation['ReservationName']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($reservation['ReservationEmail']) ?></p>
  <p><strong>Phone:</strong> <?= htmlspecialchars($reservation['RPhoneNum']) ?></p>
  <p><strong>Purpose:</strong> <?= htmlspecialchars($reservation['RPurpose']) ?></p>
  <p><strong>Date:</strong> <?= htmlspecialchars($reservation['RDate']) ?></p>
  <p><strong>Time:</strong> <?= htmlspecialchars($reservation['RTime']) ?></p>

  <h2>Order Summary</h2>
  <table>
    <tr>
      <th>Food</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Subtotal</th>
    </tr>
    <?php foreach ($order_items as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['FoodName']) ?></td>
      <td><?= $item['Quantity'] ?></td>
      <td>RM <?= number_format($item['Price'], 2) ?></td>
      <td>RM <?= number_format($item['Quantity'] * $item['Price'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <th colspan="3" style="text-align:right">Total:</th>
      <th>RM <?= number_format($order['TotalPrice'], 2) ?></th>
    </tr>
  </table>

  <div style="text-align:center">
    <a href="feedback.php" class="btn">Leave Feedback</a>
  </div>
</div>

</body>
</html>
