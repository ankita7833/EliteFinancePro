<?php
include 'db.php';
if(isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $table = ($_GET['type'] == 'income') ? "income_records" : "expense_records";
    $conn->query("DELETE FROM $table WHERE id = $id");
}
header("Location: dashboard.php");
exit();
?>