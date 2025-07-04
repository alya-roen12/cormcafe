<?php
session_start();

$errorMsg = "";
$successMsg = "";
$redirect = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['CustUsername'] ?? '');
  $email = trim($_POST['CustEmail'] ?? '');
  $password = $_POST['CustPassword'] ?? '';

  if (empty($username) || empty($email) || empty($password)) {
    $errorMsg = "All fields are required.";
  } else {
    $conn = new mysqli("localhost", "root", "", "corm");
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $checkStmt = $conn->prepare("SELECT * FROM customer WHERE CustUsername = ? OR CustEmail = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
      $errorMsg = "Username or Email already exists.";
    } else {
      $insertStmt = $conn->prepare("INSERT INTO customer (CustUsername, CustEmail, CustPassword) VALUES (?, ?, ?)");
      $insertStmt->bind_param("sss", $username, $email, $password);
      if ($insertStmt->execute()) {
        $successMsg = "Account created successfully! Redirecting to login...";
        $redirect = true;
      } else {
        $errorMsg = "Failed to create account. Try again.";
      }
      $insertStmt->close();
    }
    $checkStmt->close();
    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Create Account - Corm Cafe</title>
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
      background: url('loginbackground.jpg') no-repeat center center/cover;
      opacity: 0.7;
      z-index: -1;
    }

    .container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      width: 400px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      text-align: center;
      position: relative;
      z-index: 1;
    }

    h2 {
      color: #973300;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
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

    .success {
      color: green;
      margin-bottom: 10px;
    }

    .link {
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
  <?php if ($redirect): ?>
    <meta http-equiv="refresh" content="1;url=login.php?role=Customer">
  <?php endif; ?>
</head>
<body>
  <div class="container">
    <h2>Create Account</h2>

    <?php if (!empty($errorMsg)): ?>
      <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <?php if (!empty($successMsg)): ?>
      <div class="success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>

    <?php if (!$redirect): ?>
    <form method="POST" action="">
      <div class="form-group">
        <input type="text" name="CustUsername" placeholder="Username" required>
      </div>
      <div class="form-group">
        <input type="email" name="CustEmail" placeholder="Email" required>
      </div>
      <div class="form-group">
        <input type="password" name="CustPassword" placeholder="Password" required>
      </div>
      <div class="form-group">
        <button type="submit">Create Account</button>
      </div>
    </form>

    <div class="link">
      Already have an account? <a href="login.php?role=Customer">Sign In</a>
    </div>
    <?php endif; ?>
  </div>
</body>
</html>
