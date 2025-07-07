<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Success - Corm</title>
    <style>
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
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }

        h1 {
            color: #a34f27;
            margin-bottom: 20px;
            font-size: 36px;
        }

        .user-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .user-info p {
            margin: 10px 0;
            font-size: 16px;
        }

        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
            margin-top: 10px;
        }

        .role-customer { background-color: #28a745; }
        .role-staff { background-color: #007bff; }
        .role-admin { background-color: #dc3545; }

        .logout-btn {
            background-color: #a34f27;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background-color: #8f3c15;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Corm!</h1>
        <p>Login successful!</p>
        
        <div class="user-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Role:</strong> 
                <span class="role-badge role-<?php echo strtolower($_SESSION['role']); ?>">
                    <?php echo htmlspecialchars($_SESSION['role']); ?>
                </span>
            </p>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
        </div>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>

