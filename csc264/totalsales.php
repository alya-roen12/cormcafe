<?php
session_start();
$conn = new mysqli("localhost", "root", "", "corm");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filter = $_GET['filter'] ?? 'daily';
$where_clause = '';
$title = 'Today';

if ($filter === 'weekly') {
    $where_clause = "WHERE RDate >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
    $title = 'This Week';
} elseif ($filter === 'monthly') {
    $where_clause = "WHERE RDate >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    $title = 'This Month';
} else {
    $where_clause = "WHERE RDate = CURDATE()";
}

$sql = "SELECT r.ReservationID, r.ReservationName, r.RDate, SUM(o.Price * o.Quantity) AS total
        FROM reservation r
        JOIN order_items o ON r.ReservationID = o.ReservationID
        $where_clause
        GROUP BY r.ReservationID";

$result = $conn->query($sql);

$total_sales = 0;
$orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
        $total_sales += $row['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - View Sales</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #fffaf0;
      padding: 40px;
      margin: 0;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    h2 {
      color: #5d4037;
      text-align: center;
      margin-bottom: 20px;
    }
    select {
      padding: 8px;
      font-size: 16px;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #8F3C15;
      color: white;
    }
    .total {
      font-weight: bold;
      text-align: right;
      margin-top: 20px;
      font-size: 18px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Total Sales - <?= htmlspecialchars($title) ?></h2>

    <form method="GET" style="text-align: center;">
      <label for="filter">View By:</label>
      <select name="filter" id="filter" onchange="this.form.submit()">
        <option value="daily" <?= $filter == 'daily' ? 'selected' : '' ?>>Daily</option>
        <option value="weekly" <?= $filter == 'weekly' ? 'selected' : '' ?>>Weekly</option>
        <option value="monthly" <?= $filter == 'monthly' ? 'selected' : '' ?>>Monthly</option>
      </select>
    </form>

    <?php if (!empty($orders)): ?>
    <table>
      <tr>
        <th>Reservation ID</th>
        <th>Name</th>
        <th>Date</th>
        <th>Total (RM)</th>
      </tr>
      <?php foreach ($orders as $order): ?>
      <tr>
        <td><?= $order['ReservationID'] ?></td>
        <td><?= htmlspecialchars($order['ReservationName']) ?></td>
        <td><?= $order['RDate'] ?></td>
        <td>RM <?= number_format($order['total'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <p class="total">Total Sales: RM <?= number_format($total_sales, 2) ?></p>
    <?php else: ?>
    <p style="text-align: center;">No sales records found for <?= htmlspecialchars($title) ?>.</p>
    <?php endif; ?>
  </div>
</body>
</html>

<?php $conn->close(); ?>
