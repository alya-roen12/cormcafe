<?php
session_start();
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "corm");
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $role = $_POST['role'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? ''); // Added trim here
    $username = trim($_POST['username'] ?? '');

    if ($role === "Customer") {
        $stmt = $conn->prepare("SELECT * FROM customer WHERE CustUsername = ? AND CustPassword = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $customer = $result->fetch_assoc();
            $_SESSION['role'] = 'Customer';
            $_SESSION['username'] = $customer['CustUsername'];
            $_SESSION['CustID'] = $customer['CustID'];
            header("Location: homepageaftersignin.php");
            exit();
        } else {
            $errorMsg = "Invalid username or password.";
        }
    }

    if ($role === "Admin") {
        $stmt = $conn->prepare("SELECT AdminID, AdminEmail, AdminPassword FROM admin WHERE TRIM(AdminEmail) = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            
            // Compare passwords with trimming to remove any spaces
            if (trim($admin['AdminPassword']) === trim($password)) {
                $_SESSION['role'] = 'Admin';
                $_SESSION['email'] = $admin['AdminEmail'];
                $_SESSION['AdminID'] = $admin['AdminID'];
                header("Location: slide8adminhomepage.php");
                exit();
            } else {
                $errorMsg = "Invalid admin credentials.";
            }
        } else {
            $errorMsg = "Invalid admin credentials.";
        }
    }

    if ($role === "Staff") {
        $stmt = $conn->prepare("SELECT * FROM staff WHERE StaffEmail = ? AND StaffPassword = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $staff = $result->fetch_assoc();
            $_SESSION['role'] = 'Staff';
            $_SESSION['email'] = $staff['StaffEmail'];
            $_SESSION['StaffID'] = $staff['StaffID'];
            header("Location: slide36staffhomepage.php");
            exit();
        } else {
            $errorMsg = "Invalid staff credentials.";
        }
    }

    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>
<!-- HTML Part remains unchanged -->


<!DOCTYPE html>
<html>
<head>
  <title>Corm Cafe - Login</title>
  <style>
    body {
      font-family: Calibri, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('img/chile.jpg') no-repeat center center/cover;
      opacity: 0.7;
      z-index: -2;
    }

    .container {
      background: white;
      padding: 30px;
      border-radius: 15px;
      width: 380px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      border: 2px solid #ccc;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .logo {
      width: 80px;
      margin-bottom: 10px;
    }

    h2 {
      color: #973300;
      margin-bottom: 20px;
    }

    .radio-group {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
      display: none;
    }

    input, button {
      width: 100%;
      padding: 10px;
      border-radius: 20px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    button {
      background: black;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .error {
      color: red;
      margin-bottom: 10px;
    }

    .link {
      margin-top: 10px;
      font-size: 14px;
    }

    .floating-feedbacks {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 0;
      pointer-events: none;
    }

    .bubble {
      position: absolute;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 25px;
      padding: 8px 15px;
      color: #5b2c01;
      font-size: 14px;
      white-space: nowrap;
      animation: rise linear infinite;
      display: flex;
      flex-direction: column;
      align-items: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .bubble .stars {
      font-size: 14px;
      color: #ffa500;
      font-weight: bold;
    }

    .bubble .desc {
      font-size: 13px;
      font-style: italic;
      color: #333;
      text-align: center;
    }

    @keyframes rise {
      0% {
        transform: translateY(0) translateX(0);
        opacity: 1;
      }
      25% {
        transform: translateY(-25vh) translateX(20px);
      }
      50% {
        transform: translateY(-50vh) translateX(-20px);
      }
      75% {
        transform: translateY(-75vh) translateX(10px);
      }
      100% {
        transform: translateY(-100vh) translateX(0);
        opacity: 0;
      }
    }
  </style>
</head>
<body>

<div class="floating-feedbacks" id="feedbackContainer"></div>

<div class="container">
  <img src="asset/corm_logo_noword.png" alt="Corm Logo" class="logo">
  <h2>Login to Corm Cafe</h2>

  <?php if (!empty($errorMsg)) : ?>
    <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <form method="POST" action="" id="loginForm">
    <?php if (!isset($_GET['role'])): ?>
      <div class="radio-group">
        <label><input type="radio" name="role" value="Customer" onclick="showForm()"> Customer</label>
        <label><input type="radio" name="role" value="Admin" onclick="showForm()"> Admin</label>
        <label><input type="radio" name="role" value="Staff" onclick="showForm()"> Staff</label>
      </div>
    <?php else: ?>
      <input type="hidden" name="role" value="<?= htmlspecialchars($_GET['role']) ?>">
    <?php endif; ?>

    <div class="form-group" id="usernameGroup">
      <input type="text" name="username" placeholder="Username">
    </div>

    <div class="form-group" id="emailGroup">
      <input type="email" name="email" placeholder="Email">
    </div>

    <div class="form-group" id="passwordGroup">
      <input type="password" name="password" placeholder="Password">
    </div>

    <div class="form-group" id="submitBtn">
      <button type="submit">Sign In</button>
    </div>

    <div class="form-group link" id="createLink">
      Don’t have an account? <a href="customer_signup.php">Create Account</a>
    </div>
  </form>
</div>

<script>
  function showForm() {
    const role = document.querySelector('input[name="role"]:checked')?.value;
    updateForm(role);
  }

  function updateForm(role) {
    document.getElementById("usernameGroup").style.display = role === "Customer" ? "block" : "none";
    document.getElementById("emailGroup").style.display = role !== "Customer" ? "block" : "none";
    document.getElementById("passwordGroup").style.display = "block";
    document.getElementById("submitBtn").style.display = "block";
    document.getElementById("createLink").style.display = role === "Customer" ? "block" : "none";
  }

  window.onload = function () {
    const role = new URLSearchParams(window.location.search).get("role");
    if (role) {
      updateForm(role);
      document.querySelector(".radio-group")?.style.setProperty("display", "none");
    }

    fetch('viewfeedback.php?json=1')
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('feedbackContainer');
        data.forEach((fb, i) => {
          const bubble = document.createElement('div');
          bubble.className = 'bubble';
          const top = 100 + Math.random() * 20;
          const left = Math.random() * 90;
          bubble.style.top = `${top}%`;
          bubble.style.left = `${left}%`;
          bubble.style.animationDuration = `${10 + Math.random() * 6}s`;
          bubble.style.animationDelay = `${Math.random() * 5}s`;

          const stars = document.createElement('div');
          stars.className = 'stars';
          stars.innerHTML = '★'.repeat(fb.stars) + '☆'.repeat(5 - fb.stars);

          const desc = document.createElement('div');
          desc.className = 'desc';
          desc.textContent = fb.text;

          bubble.appendChild(stars);
          bubble.appendChild(desc);
          container.appendChild(bubble);
        });
      });
  };
</script>
</body>
</html>
