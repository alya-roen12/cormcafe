<?php
session_start();

$error = "";

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "corm";
    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = trim($_POST['CustUsername'] ?? '');
    $email = trim($_POST['CustEmail'] ?? '');
    $password = $_POST['CustPassword'] ?? '';
    $role = $_POST['role'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $error = "Please fill in all fields.";
    } else {
        switch ($role) {
            case 'customer': $table = 'customer'; break;
            case 'admin': $table = 'admin'; break;
            case 'staff': $table = 'staff'; break;
            default: $error = "Invalid role selected."; break;
        }

        if (empty($error)) {
            $query = "SELECT * FROM $table WHERE CustUsername = ? AND CustEmail = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Use plain password comparison
                if ($password === $user['CustPassword']) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    header("Location: homepage.html");
                    exit();
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "Username or email not found.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    /* Your full CSS unchanged — paste your existing CSS here */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Montserrat", sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #dfd2b6;
    }

    .container {
      display: flex;
      width: 850px;
      height: 550px;
      border-style: solid;
      border-width: 1px;
      background-color: #a34f27;
    }

    .Form-box h1 {
      position: absolute;
      text-align: left;
      top: 130px;
      left: 53%;
      transform: translateX(-50%);
      font-size: 50px !important;
      color: #8f3c15;
    }

    .Form-box {
      right: 0;
      width: 60%;
      height: 100%;
      background-color: #ffffff;
      display: flex;
      align-items: center;
      color: #000000;
      text-align: left;
    }

    form {
      width: 100%;
    }

    .container h1 {
      font-size: 41px;
      margin: 10px;
    }

    .input-box {
      bottom: -10px;
      font-size: 17px;
      position: relative;
      margin: 20px;
    }

    .input-box input {
      width: 100%;
      font-size: 15px;
      padding: 13px;
      background-color: #ffffff;
      border-radius: 25px;
      border: 1px solid #ccc;
    }

    .choose-box label {
      font-size: 16px;
      margin: 25px;
      accent-color: #232323;
    }

    .button {
      display: none;
    }

    .button.customer {
      position: absolute;
      top: 550px;
      right: 250px;
      transform: translateX(-50%);
      text-align: center;
      display: flex;
      gap: 15px;
    }

    .button.staff-admin {
      position: absolute;
      top: 550px;
      right: 400px;
      text-align: center;
    }

    .button button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #000000;
      color: #e7e2e2;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .button button:hover {
      background-color: #6d6060;
    }

    .left-panel {
      width: 40%;
      height: 100%;
      background-color: #a34f27;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .logo {
      width: 119%;
      display: flex;
      justify-content: center;
      padding-left: 10px;
      position: relative;
      top: -50px;
    }

    .logo-img {
      max-width: 100px;
      height: auto;
    }

    .logo h1 {
      font-size: 55px;
      text-align: center;
      color: #2a211b;
    }

    .user {
      width: 119%;
      display: flex;
      justify-content: center;
      padding-left: 10px;
      position: relative;
      top: -30px;
    }

    .user-img {
      max-width: 400px;
      height: auto;
    }

    .user h1 {
      position: absolute;
      bottom: -80px;
      left: 49%;
      transform: translateX(-50%);
      text-align: center;
      color: #000000;
      font-size: 43.5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <div class="logo">
        <img src="asset/corm_logo_noword.png" alt="corm_logo" class="logo-img">
        <h1>Corm</h1>
      </div>
      <div class="user">
        <img src="asset/user_icon.png" alt="user_icon" class="user-img">
        <h1 id="userRole">Admin</h1>
      </div>
    </div>

    <div class="Form-box">
      <form name="form1" method="post" action="registration.php">
        <h1>Welcome!</h1>
        <div class="choose-box">
          <label><input type="radio" name="individual" value="Customer" onchange="displayRole(this.value)"> Customer</label>
          <label><input type="radio" name="individual" value="Staff" onchange="displayRole(this.value)"> Staff</label>
          <label><input type="radio" name="individual" value="Admin" onchange="displayRole(this.value)"> Admin</label>
        </div>

        <div class="input-box">
          <input type="text" name="username" placeholder="Enter your username" required> <br><br>
          <input type="email" name="email" placeholder="Enter your email" required> <br><br>
          <input type="password" name="password" placeholder="Password" required> <br><br>
        </div>

        <div class="button customer" id="customerButtons">
          <button type="submit" name="action" value="signup">Sign In</button>
          <button type="submit" name="action" value="create">Create Account</button>
        </div>

        <div class="button staff-admin" id="staffAdminButtons">
          <button type="submit" name="action" value="signin">Sign In</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function displayRole(selectedRole) {
      const userRoleElement = document.getElementById('userRole');
      userRoleElement.textContent = selectedRole;

      const customerButtons = document.getElementById('customerButtons');
      const staffAdminButtons = document.getElementById('staffAdminButtons');

      customerButtons.style.display = 'none';
      staffAdminButtons.style.display = 'none';

      if (selectedRole === 'Customer') {
        customerButtons.style.display = 'flex';
      } else if (selectedRole === 'Staff' || selectedRole === 'Admin') {
        staffAdminButtons.style.display = 'block';
      }
    }
  </script>
</body>
</html>
