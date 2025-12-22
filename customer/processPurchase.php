<?php
include '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
	$username = $_SESSION['username'];

	$address = $_POST['delivery_address'];
	$paymentMethod = $_POST['payment_method'];
	$order_id = $_POST['order_id']; // Passed from purchase.php

	// save member address
	$updateStmt = $pdo->prepare("UPDATE members SET Address = ? WHERE Username = ?");
	$updateStmt->execute([$address, $username]);

	// finalize order
	$fetchTotal = $pdo->prepare("SELECT Total_Amount FROM orders WHERE ID = ?");
	$fetchTotal->execute([$order_id]);
	$total = $fetchTotal->fetchColumn();

	// Set status to 'purchased', save the calculated total, address, and payment method
	$update = $pdo->prepare("UPDATE orders SET Status = 'Order Placed', Payment_Method = ?, Order_Date = CURRENT_TIMESTAMP WHERE ID = ?");
	$update->execute([$paymentMethod, $order_id]);

	if ($update)
		header("Location: cart.php?msg=success");
	else
		echo "Error processing order: " . $conn->error;
	$stmt->close();
} else
  header("Location: cart.php");
?>