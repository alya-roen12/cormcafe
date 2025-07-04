<?php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');

$conn = new mysqli("localhost", "root", "", "corm");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$total = $_SESSION['total_price'] ?? 0;
$reservation_id = $_SESSION['reservation_id'] ?? null;
$username = $_SESSION['username'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'Not specified';
    $_SESSION['payment_method'] = $payment_method;

    // Get CustID
    $cust_id = null;
    if ($username) {
        $stmt = $conn->prepare("SELECT CustID FROM customer WHERE CustUsername = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $cust_id = $row['CustID'];
        }
        $stmt->close();
    }

    // Get latest OrderID
    $order_id = null;
    if ($reservation_id) {
        $stmt = $conn->prepare("SELECT OrderID FROM orders WHERE ReservationID = ? ORDER BY OrderID DESC LIMIT 1");
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $order_id = $row['OrderID'];
        }
        $stmt->close();
    }

    // Insert payment record
    $payment_date = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO payment (ReservationID, OrderID, PaymentMethod, PaymentTotal, PaymentDate, CustID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdsi", $reservation_id, $order_id, $payment_method, $total, $payment_date, $cust_id);
    $stmt->execute();
    $stmt->close();

    header("Location: payment_success.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payment Method - Corm Cafe</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #f9f3e7;
      padding: 40px;
      color: #3e1f0d;
    }
    h1 {
      text-align: center;
      color: #7b3f00;
      margin-bottom: 30px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: #fff7ef;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    .payment-option {
      margin: 12px 0;
    }
    input[type="radio"] {
      margin-right: 10px;
      transform: scale(1.2);
    }
    .card-details {
      display: none;
      margin-top: 20px;
      padding: 20px;
      background: #fff1dc;
      border-radius: 12px;
    }
    input[type="text"], input[type="number"] {
      width: 100%;
      padding: 12px;
      margin-top: 8px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }
    .btn {
      margin-top: 30px;
      padding: 14px 24px;
      font-size: 18px;
      background-color: #7b3f00;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #5a2d00;
    }
  </style>
  <script>
    function toggleCardFields(method) {
      const cardForm = document.getElementById('cardDetails');
      const inputs = cardForm.querySelectorAll("input");

      if (method === 'card') {
        cardForm.style.display = "block";
        inputs.forEach(input => input.disabled = false);
      } else {
        cardForm.style.display = "none";
        inputs.forEach(input => {
          input.disabled = true;
          input.value = "";
        });
      }
    }

    function validateForm() {
      const method = document.querySelector('input[name="payment_method"]:checked');
      if (!method) {
        alert("Please select a payment method.");
        return false;
      }

      if (method.value === "Credit/Debit Card") {
        const cardNum = document.getElementById('card_number').value.trim();
        const expiry = document.getElementById('expiry_date').value.trim();
        const cvc = document.getElementById('cvc').value.trim();

        if (!cardNum || !expiry || !cvc) {
          alert("Please fill in all card details.");
          return false;
        }
      }

      return true;
    }
  </script>
</head>
<body>

<h1>Confirm Your Payment</h1>

<div class="container">
  <form method="POST" onsubmit="return validateForm()">
    <p><strong>Total Price: RM <?= number_format($total, 2) ?></strong></p>

    <label>Select Payment Method:</label>
    <div class="payment-option">
      <input type="radio" name="payment_method" value="Online Banking" onclick="toggleCardFields('online')"> Online Banking
    </div>
    <div class="payment-option">
      <input type="radio" name="payment_method" value="Credit/Debit Card" onclick="toggleCardFields('card')"> Credit/Debit Card
    </div>

    <div id="cardDetails" class="card-details">
      <label for="card_number">Card Number</label>
      <input type="text" name="card_number" id="card_number" placeholder="1234 5678 9012 3456" disabled>

      <label for="expiry_date">Expiry Date</label>
      <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" disabled>

      <label for="cvc">CVC/CVV</label>
      <input type="number" name="cvc" id="cvc" placeholder="123" disabled>
    </div>

    <button type="submit" class="btn">Pay Now</button>
  </form>
</div>

</body>
</html>
