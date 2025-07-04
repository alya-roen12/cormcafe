<?php
session_start();
$conn = new mysqli("localhost", "root", "", "corm");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = '';
$searchValue = '';

if (!empty($search)) {
    $searchCondition = "WHERE CustID LIKE ?";
    $searchValue = "%" . $search . "%";
}

// Prepare SQL query
$sql = "SELECT PaymentID, CustID, ReservationID, PaymentMethod, PaymentTotal, PaymentDate, OrderID 
        FROM payment 
        $searchCondition 
        ORDER BY PaymentDate DESC";

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $stmt->bind_param("s", $searchValue);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Corm Cafe - Payment</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Montserrat", sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      width: 100%;
    }

    .main-container {
      width: 100%;
      max-width: 100vw;
      margin: 0 auto;
      background-color: #dfd2b6;
      min-height: 100vh;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
 
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

    .off-screen-menu a:hover {
      background-color: #8F3C15;
    }

    .logo-area {
      display: flex;
      align-items: center;
    }

    .logo-area img {
      height: 60px;
      width: 60px;
      margin-right: 15px;
      border-radius: 50%;
      object-fit: cover;
    }

    .logo-area span {
      font-size: 36px;
      font-weight: 700;
      color: #2A211B;
      letter-spacing: -1px;
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

    .content-area {
      padding: 40px;
      background: #dfd2b6;
      min-height: calc(100vh - 300px);
    }
    
    .payment-header {
      background: white;
      color: #8f3c15;
      padding: 20px 30px;
      border-radius: 15px 15px 0 0;
      border: 2px solid #dfdbdb;
      font-size: 24px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 4px 15px rgba(143, 60, 21, 0.3);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .search-box {
      position: relative;
      width: 300px;
    }

    .search-box input {
      width: 100%;
      padding: 12px 45px 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 25px;
      font-size: 14px;
      transition: all 0.3s ease;
      background-color: #f9f9f9;
    }

    .search-box input:focus {
      outline: none;
      border-color: #8F3C15;
      background-color: white;
      box-shadow: 0 0 10px rgba(143, 60, 21, 0.2);
    }


    .table-container {
      background: #f7f7f7;
      border-radius: 0 0 15px 15px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      border-left: 3px solid #dfdbdb;
      border-right: 3px solid #dfdbdb;
      border-bottom: 3px solid #dfdbdb;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 600px;
    }

    th {
      background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%);
      color: #333;
      padding: 18px 15px;
      text-align: center;
      font-weight: 600;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #ddd;
    }

    td {
      padding: 18px 15px;
      text-align: center;
      border-bottom: 1px solid #f0f0f0;
      font-size: 14px;
      color: #333;
    }

    tr:nth-child(even) {
      background-color: #fafafa;
    }

    tr:hover {
      background-color: #f5f5f5;
      transform: scale(1.001);
      transition: all 0.2s ease;
    }

    .customer-id {
      font-weight: 600;
      color: #8F3C15;
      font-size: 15px;
    }

    .payment-id {
      font-weight: 500;
      color: #666;
    }

    .amount {
      font-weight: 700;
      color: #6a6a6a;
      font-size: 15px;
    }

    .payment-type {
      font-weight: 500;
      padding: 4px 8px;
      border-radius: 8px;
      background-color: #f8f9fa;
      color: #495057;
      font-size: 12px;
    }

    .no-records {
      text-align: center;
      padding: 60px 20px;
      color: #666;
      font-size: 18px;
      background: white;
    }

    .search-box img {
  position: absolute;
  height: 20px;
  width: 20px;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  border: none;
  object-fit: contain;
}

    .result-info {
      padding: 15px;
      text-align: center;
      background: white;
      font-size: 14px;
      color: #666;
      border-top: 1px solid #eee;
    }

    .result-info a {
      color: #8F3C15;
      text-decoration: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .navbar {
        padding: 12px 20px;
      }
      
      .logo-area img {
        height: 50px;
        width: 50px;
      }
      
      .logo-area span {
        font-size: 28px;
      }
      
      .content-area {
        padding: 20px;
      }
      
      .background {
        height: 200px;
      }
      
      .payment-header {
        font-size: 20px;
        padding: 15px 20px;
        flex-direction: column;
        gap: 15px;
      }
      
      .search-box {
        width: 100%;
        max-width: 300px;
      }
      
      th, td {
        padding: 12px 8px;
        font-size: 12px;
      }
    }

    @media (max-width: 480px) {
      .navbar {
        padding: 10px 15px;
      }
      
      .logo-area img {
        height: 40px;
        width: 40px;
        margin-right: 8px;
      }
      
      .logo-area span {
        font-size: 24px;
      }
      
      .content-area {
        padding: 15px;
      }
      
      .background {
        height: 150px;
      }
      
      .payment-header {
        font-size: 18px;
        padding: 12px 15px;
      }
      
      .search-box input {
        padding: 10px 35px 10px 12px;
        font-size: 13px;
      }
      
      th, td {
        padding: 8px 4px;
        font-size: 11px;
      }

      .off-screen-menu {
        width: 250px;
        right: -250px;
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

    <div class="content-area">
      <div class="payment-header">
        PAYMENT
        <div class="search-box">
          <form method="GET" action="">
            <input type="text" 
                   name="search" 
                   placeholder="Search by Customer ID" 
                   value="<?php echo htmlspecialchars($search); ?>">
                 <img src="search-icon.png" alt="search icon" >
          </form>
        </div>
      </div>

      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Payment<br>ID</th>
              <th>Customer<br>ID</th>
              <th>Reservation<br>ID</th>
              <th>Payment<br>Method</th>
              <th>Payment<br>Total (RM)</th>
              <th>Payment<br>Date</th>
              <th>Order<br>ID</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='payment-id'>" . htmlspecialchars($row["PaymentID"]) . "</td>";
                    echo "<td class='customer-id'>" . htmlspecialchars($row["CustID"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ReservationID"]) . "</td>";
                    echo "<td class='payment-type'>" . htmlspecialchars($row["PaymentMethod"]) . "</td>";
                    echo "<td class='amount'>RM " . number_format($row["PaymentTotal"], 2) . "</td>";
                    echo "<td>" . date('Y-m-d', strtotime($row["PaymentDate"])) . "</td>";
                    echo "<td>" . htmlspecialchars($row["OrderID"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='no-records'>";
                if (!empty($search)) {
                    echo "No payment records found for Customer ID: '" . htmlspecialchars($search) . "'";
                } else {
                    echo "No payment records found";
                }
                echo "</td></tr>";
            }
            ?>
          </tbody>
        </table>
        
        <?php if ($result->num_rows > 0): ?>
        <div class="result-info">
          <?php 
          $totalRecords = $result->num_rows;
          if (!empty($search)) {
              echo "Found $totalRecords record(s) for Customer ID: '" . htmlspecialchars($search) . "' | ";
              echo "<a href='" . $_SERVER['PHP_SELF'] . "'>Show All Records</a>";
          } else {
              echo "Showing $totalRecords total payment record(s)";
          }
          ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

  <script>
  const hamMenu = document.querySelector('.ham-menu');
const offScreenMenu = document.querySelector('.off-screen-menu');

hamMenu.addEventListener('click', () => {
  hamMenu.classList.toggle('active');
  offScreenMenu.classList.toggle('active');
});

    // Enhanced search functionality
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.querySelector('.search-box input');
      const searchForm = document.querySelector('.search-box form');
      
      // Submit form on Enter key
      searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          searchForm.submit();
        }
      });
    });
  </script>
</body>
</html>

<?php
// Close database connections
$stmt->close();
$conn->close();
?>