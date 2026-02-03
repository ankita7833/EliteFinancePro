<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_project'])) {
    $_SESSION['username'] = $_POST['username'];
    $initial_balance = $_POST['initial_balance'];
    $date = date("Y-m-d");

    $sql = "INSERT INTO income_records (amount, income_date, category, notes) 
            VALUES ('$initial_balance', '$date', 'Salary', 'Initial Budget')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body { background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; }
        .welcome-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center; width: 400px; }
        input { width: 90%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; }
        button { width: 96%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>
    <div class="welcome-card">
        <h2>Hello! 👋</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Your Name" required>
            <input type="number" name="initial_balance" placeholder="Starting Balance (₹)" required>
            <button type="submit" name="start_project">Start Tracking</button>
        </form>
    </div>
</body>
</html>