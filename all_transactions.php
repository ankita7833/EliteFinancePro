<?php
include 'db.php';
session_start();
if (!isset($_SESSION['username'])) { header("Location: index.php"); exit(); }

$all = $conn->query("(SELECT amount, income_date as date, created_at as time, category, 'income' as type FROM income_records) UNION ALL (SELECT amount, expense_date as date, created_at as time, category, 'expense' as type FROM expense_records) ORDER BY date DESC, time DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>All History</title>
    <style>
        body { background: #0f172a; color: white; font-family: sans-serif; padding: 40px; }
        table { width: 100%; border-collapse: collapse; background: #1e293b; border-radius: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #334155; }
        .income { color: #4ade80; } .expense { color: #fb7185; }
        .btn { color: #38bdf8; text-decoration: none; display: block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <a href="dashboard.php" class="btn">← Back to Dashboard</a>
    <h2>Full Transaction History</h2>
    <table>
        <thead><tr><th>Date</th><th>Time</th><th>Category</th><th>Type</th><th>Amount</th></tr></thead>
        <tbody>
            <?php while($r = $all->fetch_assoc()): ?>
            <tr>
                <td><?php echo $r['date']; ?></td>
                <td><?php echo date("h:i A", strtotime($r['time'])); ?></td>
                <td><?php echo $r['category']; ?></td>
                <td class="<?php echo $r['type']; ?>"><?php echo strtoupper($r['type']); ?></td>
                <td class="<?php echo $r['type']; ?>">₹<?php echo number_format($r['amount'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>