<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
	$username = $_SESSION['username'];

	$address = $_POST['delivery_address'];
	$paymentMethod = $_POST['payment_method'];
	$order_id = $_POST['order_id']; 

	$updateStmt = $pdo->prepare("UPDATE members SET Address = ? WHERE Username = ?");
	$updateStmt->execute([$address, $username]);

	$fetchTotal = $pdo->prepare("SELECT subTotal FROM orders WHERE ID = ?");
	$fetchTotal->execute([$order_id]);
	$subtotal = $fetchTotal->fetchColumn();

	$discount = $subtotal * 0.10;
	$finalTotal = $subtotal - $discount;

	$update = $pdo->prepare("UPDATE orders SET Status = 'Order Placed', Payment_Method = ?, Total_Amount = ?, Order_Date = CURRENT_TIMESTAMP WHERE ID = ?");
	$update->execute([$paymentMethod, $finalTotal, $order_id]);

	if ($update)
		header("Location: cart.php?msg=success");
	else
		echo "Error processing order: " . $conn->error;
	$stmt->close();
} else
  header("Location: cart.php");
?>