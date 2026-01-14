<?php
session_start();
include '../../config.php';

// Security: Check if user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'runner') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['status'];
   
   if ($newStatus === 'In Transit') {
            $sql = "UPDATE orders SET Status = ?, PickedUp_Date = CURRENT_TIMESTAMP WHERE ID = ?";
            $params = [$newStatus, $orderId];
        } elseif ($newStatus === 'Delivered') {
            $sql = "UPDATE orders SET Status = ?, Delivered_Date = CURRENT_TIMESTAMP WHERE ID = ?";
            $params = [$newStatus, $orderId];
        } else {
            $sql = "UPDATE orders SET Status = ? WHERE ID = ?";
            $params = [$newStatus, $orderId];
        }

       $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            echo "OK";
        } else {
            echo "Database execution failed";
        }
}
?>
