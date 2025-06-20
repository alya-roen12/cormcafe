<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "corm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$hideCreateBtn = false;
$createSuccess = false;
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $role = $_POST['role'] ?? '';

  $inputEmail = trim($_POST['CustEmail'] ?? '');
  $inputPassword = $_POST['CustPassword'] ?? '';

  if ($role == "Customer") {
    $inputUsername = trim($_POST['CustUsername'] ?? '');
    $action = $_POST['action'] ?? '';

    if ($action == "signin") {
      $stmt = $conn->prepare("SELECT * FROM customer WHERE CustUsername = ? AND CustEmail = ? AND CustPassword = ?");
      $stmt->bind_param("sss", $inputUsername, $inputEmail, $inputPassword);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        session_regenerate_id(true);
        $_SESSION['username'] = $inputUsername;
        $_SESSION['role'] = "Customer";
        header("Location: menulist.php");
        exit();
      } else {
        $errorMsg = "Incorrect username/email/password.";
      }
      $stmt->close();

    } elseif ($action == "create") {
      // Check duplicates
      $checkStmt = $conn->prepare("SELECT * FROM customer WHERE CustUsername = ? OR CustEmail = ?");
      $checkStmt->bind_param("ss", $inputUsername, $inputEmail);
      $checkStmt->execute();
      $checkResult = $checkStmt->get_result();

      if ($checkResult->num_rows > 0) {
        $errorMsg = "Username or Email already exists.";
      } elseif (empty($inputUsername) || empty($inputEmail) || empty($inputPassword)) {
        $errorMsg = "Please fill all fields to create account.";
      } else {
        $insertStmt = $conn->prepare("INSERT INTO customer (CustUsername, CustEmail, CustPassword) VALUES (?, ?, ?)");
        $insertStmt->bind_param("sss", $inputUsername, $inputEmail, $inputPassword);
        if ($insertStmt->execute()) {
          $createSuccess = true;
          $hideCreateBtn = true;
        } else {
          $errorMsg = "Error creating account. Please try again.";
        }
        $insertStmt->close();
      }
      $checkStmt->close();
    }
  }

  if ($role == "Admin") {
    if (empty($inputEmail) || empty($inputPassword)) {
      $errorMsg = "Please enter email and password.";
    } else {
      $stmt = $conn->prepare("SELECT * FROM admin WHERE AdminEmail = ? AND AdminPassword = ?");
      $stmt->bind_param("ss", $inputEmail, $inputPassword);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows == 1) {
        session_regenerate_id(true);
        $_SESSION['role'] = "Admin";
        $_SESSION['email'] = $inputEmail;
        header("Location: slide8adminhomepage.php");
        exit();
      } else {
        $errorMsg = "Incorrect email/password.";
      }
      $stmt->close();
    }
  }

  if ($role == "Staff") {
    if (empty($inputEmail) || empty($inputPassword)) {
      $errorMsg = "Please enter email and password.";
    } else {
      $stmt = $conn->prepare("SELECT * FROM staff WHERE StaffEmail = ? AND StaffPassword = ?");
      $stmt->bind_param("ss", $inputEmail, $inputPassword);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows == 1) {
        session_regenerate_id(true);
        $_SESSION['role'] = "Staff";
        $_SESSION['email'] = $inputEmail;
        header("Location: slide36staffhomepage.php");
        exit();
      } else {
        $errorMsg = "Incorrect email/password.";
      }
      $stmt->close();
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Corm Cafe - Login</title>
  <style>
    body {
      margin: 0;
      font-family: Calibri, sans-serif;
      background-color: #f3e8d9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      display: flex;
      width: 900px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      overflow: hidden;
    }

    .left-panel {
      background-color: #c74f00;
      color: white;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 30px;
    }

    .left-panel h2 {
      margin: 20px 0 0;
    }

    .left-panel .role-label {
      font-size: 24px;
      text-transform: lowercase;
      margin-top: 10px;
    }

    .right-panel {
      flex: 2;
      padding: 40px;
    }

    h2 {
      color: #973300;
      margin-bottom: 15px;
    }

    .radio-group {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    input[type="text"], input[type="email"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 20px;
      border: 1px solid #ccc;
    }

    button {
      background-color: black;
      color: white;
      padding: 10px 20px;
      margin-right: 10px;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-weight: bold;
    }

    .form-group {
      margin-bottom: 10px;
    }

    #createBtn {
      display: <?php echo $hideCreateBtn ? 'none' : 'inline-block'; ?>;
    }

    .error {
      color: red;
      margin-bottom: 15px;
    }

    .success {
      color: green;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <img src="https://img.icons8.com/ios-filled/100/user.png" alt="icon"/>
      <h2>Corm</h2>
      <div class="role-label" id="roleLabel" style="visibility: hidden;"></div>
    </div>
    <div class="right-panel">
      <h2>Welcome!</h2>

      <?php if (!empty($errorMsg)) : ?>
        <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
      <?php endif; ?>

      <?php if ($createSuccess) : ?>
        <div class="success">Account created successfully! You can now sign in.</div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="radio-group">
          <label><input type="radio" name="role" value="Customer" onclick="updateForm()" required> Customer</label>
          <label><input type="radio" name="role" value="Admin" onclick="updateForm()"> Admin</label>
          <label><input type="radio" name="role" value="Staff" onclick="updateForm()"> Staff</label>
        </div>

        <div class="form-group" id="usernameField" style="display:none;">
          <input type="text" name="CustUsername" placeholder="Enter your username">
        </div>

        <div class="form-group" id="emailField">
          <input type="email" name="CustEmail" placeholder="Enter your e-mail" required>
        </div>

        <div class="form-group">
          <input type="password" name="CustPassword" placeholder="Password" required>
        </div>

        <button type="submit" name="action" value="signin" id="signInBtn">SIGN IN</button>
        <button type="submit" name="action" value="create" id="createBtn">CREATE ACCOUNT</button>
      </form>
    </div>
  </div>

  <script>
    function updateForm() {
      const roleInput = document.querySelector('input[name="role"]:checked');
      if (!roleInput) return;
      const role = roleInput.value;
      const roleLabel = document.getElementById("roleLabel");
      roleLabel.textContent = role.toLowerCase();
      roleLabel.style.visibility = "visible";

      const usernameField = document.getElementById("usernameField");
      const createBtn = document.getElementById("createBtn");

      if (role === "Customer") {
        usernameField.style.display = "block";
        createBtn.style.display = "<?php echo $hideCreateBtn ? 'none' : 'inline-block'; ?>";
      } else {
        usernameField.style.display = "none";
        createBtn.style.display = "none";
      }
    }

    // Optional: To keep the form consistent if page reloads with errors or create success
    window.onload = function() {
      const checkedRole = document.querySelector('input[name="role"]:checked');
      if (checkedRole) {
        updateForm();
      }
    };
  </script>
</body>
</html>
