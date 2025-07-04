<?php
session_start();
$conn = new mysqli("localhost", "root", "", "corm");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cart = $_SESSION['cart'] ?? [];

// Handle AJAX quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_index'], $_POST['new_quantity'])) {
    $i = (int)$_POST['update_index'];
    $newQty = max(1, (int)$_POST['new_quantity']);
    if (isset($_SESSION['cart'][$i])) {
        $_SESSION['cart'][$i]['Quantity'] = $newQty;
        echo "success";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Corm Cafe - Cart</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #fffaf0;
      padding: 40px;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      padding: 30px;
    }
    h2 {
      color: #3e2723;
      margin-bottom: 25px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      padding: 14px 18px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #ffe0b2;
      color: #3e2723;
    }
    input[type="number"] {
      width: 60px;
      padding: 4px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .confirm-button {
      display: block;
      margin: 20px auto 10px;
      background-color: #7b3f00;
      color: white;
      font-size: 16px;
      padding: 10px 22px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: bold;
    }
    .confirm-button:hover {
      background-color: #5a2d00;
    }
    .back-button {
      display: block;
      width: fit-content;
      margin: 10px auto 0;
      background-color: #8f3c15;
      color: white;
      font-size: 13px;
      padding: 6px 16px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      text-align: center;
      text-decoration: none;
      transition: background 0.3s ease;
    }
    .back-button:hover {
      background-color: #6b2a0f;
    }
    .empty-message {
      text-align: center;
      font-size: 18px;
      color: #8c6239;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Your Cart</h2>

  <?php if (empty($cart)): ?>
    <p class="empty-message">Your cart is empty.</p>
  <?php else: ?>
    <form method="POST" action="reservationForm.php">
      <table>
        <thead>
          <tr>
            <th>Food</th>
            <th>Quantity</th>
            <th>Price (RM)</th>
            <th>Subtotal (RM)</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($cart as $index => $item): 
          $name = htmlspecialchars($item['MenuName']);
          $quantity = (int)$item['Quantity'];
          $price = (float)$item['MenuPrice'];
          $subtotal = $price * $quantity;
        ?>
          <tr>
            <td><?= $name ?></td>
            <td>
              <input type="number" min="1" value="<?= $quantity ?>" data-index="<?= $index ?>" data-price="<?= $price ?>" onchange="updateCart(this)">
            </td>
            <td>RM <?= number_format($price, 2) ?></td>
            <td class="subtotal">RM <?= number_format($subtotal, 2) ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <th colspan="3">Total</th>
          <th id="totalCell">RM <?= number_format(array_reduce($cart, fn($sum, $i) => $sum + $i['MenuPrice'] * $i['Quantity'], 0), 2) ?></th>
        </tr>
        </tbody>
      </table>

      <button type="submit" name="confirm" class="confirm-button">🛒 Confirm and Pay</button>
    </form>

    <a href="menu_list.php" class="back-button">← Back to Menu</a>
  <?php endif; ?>
</div>

<script>
function updateCart(input) {
  const index = input.dataset.index;
  const price = parseFloat(input.dataset.price);
  const qty = Math.max(1, parseInt(input.value));
  const subtotalCell = input.closest("tr").querySelector(".subtotal");

  // Update subtotal display
  const newSubtotal = price * qty;
  subtotalCell.textContent = "RM " + newSubtotal.toFixed(2);

  // Update total
  let total = 0;
  document.querySelectorAll(".subtotal").forEach(sub => {
    const val = parseFloat(sub.textContent.replace("RM", "").trim());
    total += isNaN(val) ? 0 : val;
  });
  document.getElementById("totalCell").textContent = "RM " + total.toFixed(2);

  // Send AJAX to update session
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "view_cart.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("update_index=" + index + "&new_quantity=" + qty);
}
</script>
</body>
</html>

<?php $conn->close(); ?>
