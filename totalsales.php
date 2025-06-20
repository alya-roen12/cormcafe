<?php
// --- PHP BACKEND: Fetch from reservation + payment, store in total_sales ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "corm";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed");

function fetchSales($conn) {
  $period = $_GET['period'] ?? 'daily';
  $name = $_GET['customer'] ?? '';
  $from = $_GET['dateFrom'] ?? '';
  $to = $_GET['dateTo'] ?? '';

  // WHERE filters
  $where = "1=1";
  if ($name) $where .= " AND r.ReservationName LIKE '%" . $conn->real_escape_string($name) . "%'";
  if ($from) $where .= " AND r.RDate >= '$from'";
  if ($to) $where .= " AND r.RDate <= '$to'";

  // 1️⃣ Fetch data
  $data = [];
  $result = $conn->query("
    SELECT 
      r.ReservationName AS customer, 
      r.RTime AS time, 
      r.RDate AS date, 
      p.PaymentAmount AS amount
    FROM reservation r
    JOIN payment p ON r.ReservationID = p.ReservationID
    WHERE $where
    ORDER BY r.RDate DESC
  ");
  while ($row = $result->fetch_assoc()) $data[] = $row;

  // 2️⃣ Ensure total_sales exists
  $conn->query("
    CREATE TABLE IF NOT EXISTS total_sales (
      id INT AUTO_INCREMENT PRIMARY KEY,
      CustUsername VARCHAR(255),
      ReservationTime TIME,
      ReservationDate DATE,
      TotalAmount DECIMAL(10,2)
    )
  ");

  // 3️⃣ Optional: clear total_sales each fetch
  $conn->query("TRUNCATE TABLE total_sales");

  // 4️⃣ Insert fetched data into total_sales
  if ($data) {
    $stmt = $conn->prepare("
      INSERT INTO total_sales 
      (CustUsername, ReservationTime, ReservationDate, TotalAmount)
      VALUES (?, ?, ?, ?)
    ");
    foreach ($data as $row) {
      $stmt->bind_param("sssd", 
        $row['customer'], 
        $row['time'], 
        $row['date'], 
        $row['amount']
      );
      $stmt->execute();
    }
    $stmt->close();
  }

  // 5️⃣ Calculate daily/weekly/monthly totals
  $totals = [];
  foreach (['daily' => 1, 'weekly' => 7, 'monthly' => 30] as $key => $days) {
    $res = $conn->query("
      SELECT SUM(p.PaymentAmount) AS total
      FROM reservation r
      JOIN payment p ON r.ReservationID = p.ReservationID
      WHERE r.RDate >= CURDATE() - INTERVAL $days DAY
    ");
    $totals[$key] = (float)($res->fetch_assoc()['total'] ?? 0);
  }

  return [
    'success' => true,
    'records' => $data,
    'totals' => $totals
  ];
}

if (isset($_GET['fetch'])) {
  header('Content-Type: application/json');
  echo json_encode(fetchSales($conn));
  $conn->close();
  exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Corm Cafe - Total Sales</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #fafafa; padding: 40px; color: #333; }
    h1 { color: #8F3C15; }
    .card-group { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; }
    .sales-card {
      flex: 1;
      min-width: 180px;
      background: #fff;
      border: 2px solid #8F3C15;
      padding: 15px;
      border-radius: 10px;
      cursor: pointer;
      text-align: center;
    }
    .sales-card.active { background: #8F3C15; color: white; }
    .search-bar { margin: 20px 0; }
    .search-bar input, .search-bar button {
      padding: 8px;
      margin-right: 8px;
    }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    th { background: #eee; }
  </style>
</head>
<body>

<h1>Corm Cafe - Total Sales</h1>

<div class="card-group">
  <div class="sales-card active" data-period="daily" onclick="loadSalesData('daily')">Daily Sales: <span id="dailyAmount">RM0.00</span></div>
  <div class="sales-card" data-period="weekly" onclick="loadSalesData('weekly')">Weekly Sales: <span id="weeklyAmount">RM0.00</span></div>
  <div class="sales-card" data-period="monthly" onclick="loadSalesData('monthly')">Monthly Sales: <span id="monthlyAmount">RM0.00</span></div>
</div>

<div class="search-bar">
  <input type="text" id="searchInput" placeholder="Customer name" />
  <input type="date" id="dateFrom" />
  <input type="date" id="dateTo" />
  <button onclick="performSearch()">Search</button>
  <button onclick="clearSearch()">Clear</button>
</div>

<table>
  <thead>
    <tr>
      <th>Customer</th>
      <th>Reservation Time</th>
      <th>Reservation Date</th>
      <th>Amount (RM)</th>
    </tr>
  </thead>
  <tbody id="salesTableBody"></tbody>
</table>

<script>
let currentPeriod = 'daily';
let isSearchActive = false;

async function loadSalesData(period) {
  currentPeriod = period;
  document.querySelectorAll('.sales-card').forEach(card => card.classList.remove('active'));
  document.querySelector(`.sales-card[data-period="${period}"]`).classList.add('active');

  const params = new URLSearchParams({ fetch: 'true', period });

  if (isSearchActive) {
    const name = document.getElementById('searchInput').value.trim();
    const from = document.getElementById('dateFrom').value;
    const to = document.getElementById('dateTo').value;
    if (name) params.append('customer', name);
    if (from) params.append('dateFrom', from);
    if (to) params.append('dateTo', to);
  }

  const response = await fetch('?' + params.toString());
  const data = await response.json();

  const tbody = document.getElementById('salesTableBody');
  tbody.innerHTML = data.records.length
    ? data.records.map(r => `<tr><td>${r.customer}</td><td>${r.time}</td><td>${r.date}</td><td>RM${parseFloat(r.amount).toFixed(2)}</td></tr>`).join('')
    : '<tr><td colspan="4">No records found</td></tr>';

  document.getElementById('dailyAmount').textContent = 'RM' + data.totals.daily.toFixed(2);
  document.getElementById('weeklyAmount').textContent = 'RM' + data.totals.weekly.toFixed(2);
  document.getElementById('monthlyAmount').textContent = 'RM' + data.totals.monthly.toFixed(2);
}

function performSearch() {
  isSearchActive = true;
  loadSalesData(currentPeriod);
}

function clearSearch() {
  isSearchActive = false;
  document.getElementById('searchInput').value = '';
  document.getElementById('dateFrom').value = '';
  document.getElementById('dateTo').value = '';
  loadSalesData(currentPeriod);
}

document.addEventListener('DOMContentLoaded', () => loadSalesData('daily'));
</script>

</body>
</html>
