<?php
include 'db.php';
session_start();
if (!isset($_SESSION['username'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['username'];
$login_time = date("h:i A"); 

// QUICK TAP LOGIC (One-click insert)
if (isset($_GET['quick_add'])) {
    $amt = (int)$_GET['amt']; 
    $cat = mysqli_real_escape_string($conn, $_GET['cat']); 
    $date = date("Y-m-d");
    $conn->query("INSERT INTO expense_records (amount, expense_date, category) VALUES ('$amt', '$date', '$cat')");
    header("Location: dashboard.php"); exit();
}

// Stats Calculations
$inc = $conn->query("SELECT SUM(amount) as total FROM income_records")->fetch_assoc()['total'] ?? 0;
$exp = $conn->query("SELECT SUM(amount) as total FROM expense_records")->fetch_assoc()['total'] ?? 0;
$balance = $inc - $exp;
$percent = ($inc > 0) ? ($balance / $inc) * 100 : 0;

// DAILY BURN RATE
$daily_budget = ($inc > 0) ? ($inc / 30) : 0;
$today_spent = $conn->query("SELECT SUM(amount) as total FROM expense_records WHERE expense_date = CURDATE()")->fetch_assoc()['total'] ?? 0;
$burn_status = ($today_spent > $daily_budget) ? "Over Budget" : "Safe Zone";

$history = $conn->query("(SELECT id, amount, income_date as date, category, 'income' as type FROM income_records) UNION ALL (SELECT id, amount, expense_date as date, category, 'expense' as type FROM expense_records) ORDER BY date DESC LIMIT 6");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Elite Finance Pro | Team Edition</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg: #020617; --card: #1e293b; --panel: #0f172a; --text: #f8fafc;
            --primary: #38bdf8; --green: #4ade80; --red: #fb7185; --border: #334155;
        }
        body.light-mode {
            --bg: #f8fafc; --card: #ffffff; --panel: #ffffff; --text: #334155;
            --primary: #0284c7; --green: #16a34a; --red: #dc2626; --border: #e2e8f0;
        }
        body { margin: 0; background: var(--bg); display: flex; justify-content: center; align-items: center; min-height: 100vh; font-family: 'Segoe UI', sans-serif; color: var(--text); transition: 0.3s; }
        
        .app-container { width: 1280px; height: 720px; background: var(--panel); border-radius: 32px; border: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 40px 100px rgba(0,0,0,0.4); }
        .header { background: var(--card); padding: 10px 40px; display: flex; align-items: center; border-bottom: 1px solid var(--border); height: 70px; }
        .main-layout { display: flex; flex: 1; overflow: hidden; }
        .left-panel { width: 68%; padding: 35px; overflow-y: auto; border-right: 1px solid var(--border); }
        .right-panel { width: 32%; padding: 35px; background: var(--bg); }

        /* Stat Boxes Restored */
        .stat-box { background: var(--card); padding: 20px; border-radius: 20px; border: 1px solid var(--border); flex: 1; }
        .progress-bar { width: 100%; height: 8px; background: var(--border); border-radius: 10px; margin-top: 15px; overflow: hidden; }
        .progress-fill { height: 100%; width: <?php echo $percent; ?>%; background: <?php echo ($percent < 30) ? 'var(--red)' : 'var(--green)'; ?>; transition: 1s; }

        /* Quick Taps */
        .quick-tap { padding: 10px; border-radius: 12px; border: 1px solid var(--border); background: var(--card); color: var(--text); cursor: pointer; text-decoration: none; font-size: 13px; text-align: center; flex: 1; transition: 0.2s; }
        .quick-tap:hover { border-color: var(--primary); transform: translateY(-2px); }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td { padding: 14px 10px; border-bottom: 1px solid var(--border); }
        .btn { background: var(--primary); border: none; padding: 12px 24px; border-radius: 14px; font-weight: 600; cursor: pointer; color: white; }
        .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); backdrop-filter: blur(8px); justify-content:center; align-items:center; z-index:1000; }
        .modal-content { background: var(--card); padding: 30px; border-radius: 25px; width: 380px; border: 1px solid var(--border); }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border-radius: 10px; border: 1px solid var(--border); background: var(--panel); color: var(--text); box-sizing: border-box; }
    </style>
</head>
<body>

<div class="app-container">
    <div class="header">
        <div style="flex:1"><h2 style="margin:0; color:var(--primary)">EliteFinance</h2></div>
        <div style="flex:1; text-align:center">User: <strong><?php echo $user; ?></strong> | <small>Login: <?php echo $login_time; ?></small></div>
        <div style="flex:1; text-align:right">
            <button class="btn" style="background:transparent; border:1px solid var(--border); color:var(--text); margin-right:10px" onclick="openModal('settingsModal')">⚙️</button>
            <button class="btn" onclick="openModal('txnModal')">+ New Record</button>
        </div>
    </div>

    <div class="main-layout">
        <div class="left-panel">
            <div style="margin-bottom:30px">
                <small style="font-weight:700; opacity:0.6; text-transform:uppercase">Quick Indi-Log</small>
                <div style="display:flex; gap:12px; margin-top:10px">
                    <a href="?quick_add=1&amt=10&cat=Chai" class="quick-tap">☕ Chai (₹10)</a>
                    <a href="?quick_add=1&amt=20&cat=Auto" class="quick-tap">🛺 Auto (₹20)</a>
                    <a href="?quick_add=1&amt=50&cat=Snacks" class="quick-tap">🍪 Snacks (₹50)</a>
                </div>
            </div>

            <div style="display:flex; gap:25px; margin-bottom:30px">
                <div class="stat-box" style="border-top: 4px solid var(--green)">
                    <small style="opacity:0.6">TOTAL INCOME</small>
                    <h2 style="margin:10px 0; color:var(--green)">₹<?php echo number_format($inc, 2); ?></h2>
                </div>
                <div class="stat-box" style="border-top: 4px solid var(--red)">
                    <small style="opacity:0.6">TOTAL EXPENSE</small>
                    <h2 style="margin:10px 0; color:var(--red)">₹<?php echo number_format($exp, 2); ?></h2>
                </div>
            </div>

            <div class="stat-box" style="margin-bottom:40px">
                <div style="display:flex; justify-content:space-between">
                    <span><strong>Wallet Health</strong> (₹<?php echo number_format($balance, 2); ?>)</span>
                    <small style="color:var(--primary)">Burn Rate: <?php echo $burn_status; ?></small>
                </div>
                <div class="progress-bar"><div class="progress-fill"></div></div>
                <small style="display:block; margin-top:10px; opacity:0.7">
                    Today's Spend: ₹<?php echo $today_spent; ?> | Retention: <?php echo round($percent, 1); ?>%
                </small>
            </div>

            <div class="history-section">
                <div style="display:flex; justify-content:space-between; align-items:center">
                    <h3 style="margin:0">Recent activity</h3>
                    <a href="all_transactions.php" style="color:var(--primary); text-decoration:none; font-size:14px; font-weight:600">View History →</a>
                </div>
                <table>
                    <?php while($r = $history->fetch_assoc()): ?>
                    <tr>
                        <td style="opacity:0.6"><?php echo date("d M", strtotime($r['date'])); ?></td>
                        <td style="font-weight:600"><?php echo $r['category']; ?></td>
                        <td style="color:<?php echo ($r['type']=='income')?'var(--green)':'var(--red)'; ?>; font-weight:bold"><?php echo strtoupper($r['type']); ?></td>
                        <td style="text-align:right">₹<?php echo number_format($r['amount'], 2); ?></td>
                        <td style="text-align:right"><a href="delete_transaction.php?id=<?php echo $r['id']; ?>&type=<?php echo $r['type']; ?>" style="text-decoration:none">🗑️</a></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <div class="right-panel">
            <h3 style="margin-top:0">Market Analysis</h3>
            <canvas id="barChart"></canvas>
            
            <div id="reminderBadge" style="margin-top:50px; padding:20px; border-radius:20px; background:var(--card); border:1px solid var(--border); text-align:center">
                <small style="opacity:0.6">DAILY CHECK-IN</small>
                <div id="remTimeTxt" style="font-size:18px; font-weight:bold; color:var(--primary); margin:5px 0">--:--</div>
            </div>
        </div>
    </div>
</div>

<div id="settingsModal" class="modal">
    <div class="modal-content">
        <h3 style="margin-top:0">App Settings</h3>
        <label>Theme</label>
        <select id="themeSelect" onchange="changeTheme()">
            <option value="dark">High-Tech Dark</option>
            <option value="light">Calm Indi Light</option>
        </select>

        <label>Reminder Time</label>
        <input type="time" id="checkInTime" onchange="saveRemTime()">

        <div style="margin-top:20px; padding:15px; background:rgba(0,0,0,0.1); border-radius:15px; font-size:12px; line-height:1.5">
            <strong>Project Info:</strong><br>
            Developed by: <strong>Gemini, Ankita & Sambrudhi</strong><br>
            Tech: <strong>PHP, MySQL, Chart.js</strong><br>
            Storage: <strong>Local XAMPP Database</strong>
        </div>

        <button class="btn" style="width:100%; margin-top:20px" onclick="closeModal('settingsModal')">Save & Close</button>
    </div>
</div>

<div id="txnModal" class="modal">
    <div class="modal-content">
        <h3 style="margin-top:0">Add Record</h3>
        <form method="POST">
            <select name="type" id="type" onchange="updateCats()"><option value="expense">Expense</option><option value="income">Income</option></select>
            <input type="number" name="amount" placeholder="Amount (₹)" required>
            <select name="category" id="category"></select>
            <button type="submit" name="add_transaction" class="btn" style="width:100%">Confirm</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).style.display='flex'; }
    function closeModal(id) { document.getElementById(id).style.display='none'; }

    function changeTheme() {
        const theme = document.getElementById('themeSelect').value;
        localStorage.setItem('userTheme', theme);
        document.body.classList.toggle('light-mode', theme === 'light');
    }

    function saveRemTime() {
        localStorage.setItem('remTime', document.getElementById('checkInTime').value);
        document.getElementById('remTimeTxt').innerText = localStorage.getItem('remTime');
    }

    // New Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Income', 'Expense'],
            datasets: [{ 
                data: [<?php echo $inc; ?>, <?php echo $exp; ?>], 
                backgroundColor: ['#4ade80', '#fb7185'],
                borderRadius: 10
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });

    setInterval(() => {
        const now = new Date();
        const cur = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        if (cur === localStorage.getItem('remTime') && !sessionStorage.getItem('done')) {
            alert("🛎️ Finance Check: Please record any cash expenses from today!");
            sessionStorage.setItem('done', 'true');
        }
    }, 15000);

    const cats = { income: ['Salary', 'Gift', 'Refund'], expense: ['Chai/Snacks', 'Travel', 'Food', 'Misc'] };
    function updateCats() {
        const t = document.getElementById('type').value;
        document.getElementById('category').innerHTML = cats[t].map(c => `<option value="${c}">${c}</option>`).join('');
    }

    window.onload = () => {
        const t = localStorage.getItem('userTheme') || 'dark';
        document.body.classList.toggle('light-mode', t === 'light');
        document.getElementById('themeSelect').value = t;
        document.getElementById('checkInTime').value = localStorage.getItem('remTime') || '21:00';
        document.getElementById('remTimeTxt').innerText = localStorage.getItem('remTime') || '21:00';
        updateCats();
    };
</script>
</body>
</html>