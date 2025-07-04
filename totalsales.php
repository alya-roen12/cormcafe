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

$sql = "SELECT r.RDate, SUM(o.Price * o.Quantity) AS total
        FROM reservation r
        JOIN order_items o ON r.ReservationID = o.ReservationID
        $where_clause
        GROUP BY r.RDate
        ORDER BY r.RDate ASC";

$result = $conn->query($sql);

$total_sales = 0;
$daily_sales = [];
$labels = [];
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $daily_sales[] = $row;
        $labels[] = $row['RDate'];
        $data[] = round($row['total'], 2);
        $total_sales += $row['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - View Sales</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Calibri, sans-serif;
      background-color: #fffaf0;
      padding: 40px;
      margin: 0;
    }
    .container {
      max-width: 1000px;
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
    .back-button {
      text-align: center;
      margin-top: 40px;
    }
    .back-button a {
      display: inline-block;
      padding: 12px 28px;
      background-color: #8F3C15;
      color: white;
      text-decoration: none;
      border-radius: 30px;
      font-size: 16px;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    .back-button a:hover {
      background-color: #6b3e26;
    }
    #chart-container {
      margin-top: 40px;
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

    <?php if (!empty($daily_sales)): ?>
    <table>
      <tr>
        <th>Date</th>
        <th>Total Sales (RM)</th>
      </tr>
      <?php foreach ($daily_sales as $day): ?>
      <tr>
        <td><?= $day['RDate'] ?></td>
        <td>RM <?= number_format($day['total'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <p class="total">Total Sales: RM <?= number_format($total_sales, 2) ?></p>

    <div id="chart-container">
      <canvas id="salesChart"></canvas>
    </div>

    <script>
      const ctx = document.getElementById('salesChart').getContext('2d');
      const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [{
            label: 'Total Sales (RM)',
            data: <?= json_encode($data) ?>,
            backgroundColor: '#8F3C15'
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'RM'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Date'
              }
            }
          }
        }
      });
    </script>

    <?php else: ?>
    <p style="text-align: center;">No sales records found for <?= htmlspecialchars($title) ?>.</p>
    <?php endif; ?>

    <div class="back-button">
      <a href="slide8adminhomepage.php">&larr; Back to Homepage</a>
    </div>
  </div>
</body>
</html>

<?php $conn->close(); ?>
