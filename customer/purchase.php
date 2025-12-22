<?php
include '../config.php';
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$username = $_SESSION['username'];
$grandTotal = 0;
$order_id = null;

// fetch member's saved address
$stmt = $pdo->prepare("SELECT Address FROM members WHERE Username = ?");
$stmt->execute([$username]);
$member = $stmt->fetch();
$currentAddress = $member['Address'] ?? ""; 

// fetch cart total
$fetchCart = $pdo->prepare("SELECT o.ID, o.Total_Amount FROM orders o JOIN members m ON o.Member_ID = m.ID WHERE m.Username = ? AND o.status = 'cart'");
$fetchCart->execute([$username]);
$cart = $fetchCart->fetch();

if ($cart) {
  $grandTotal = $cart['Total_Amount'] ?? 0;
  $order_id = $cart['ID'];
}

// Redirect if cart is empty
if ($grandTotal == 0) {
	echo "<script>alert('Your cart is empty!'); window.location.href='cart.php';</script>";
	exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Purchase</title><link rel="stylesheet" href="style.css"></head>
<body>
	<div style="width: 400px; margin: 50px auto; text-align:center; background:white; padding:30px; border-radius:8px;">
		<h2>Confirm Purchase</h2>
		<p>Total Amount: <strong>RM <?php echo number_format($grandTotal, 2); ?></strong></p>
		
		<form method="POST" action="processPurchase.php">
			<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
			
			<label style="display:block; text-align:left; margin:10px 0; font-weight:bold;">Delivery Address:</label>
			<textarea name="delivery_address" 
								required 
								rows="4" 
								placeholder="Please enter your delivery address here..." 
								style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px; resize:vertical;"><?php echo htmlspecialchars($currentAddress); ?></textarea>

			<label style="display:block; text-align:left; margin:10px 0; font-weight:bold;">Payment Method:</label>
			<select name="payment_method" style="width:100%; padding:10px; margin-bottom:20px; border:1px solid #ccc; border-radius:5px;">
				<option value="Cash on Delivery">Cash on Delivery</option>
				<option value="Online Banking">Online Banking</option>
				<option value="E-Wallet">E-Wallet</option>
			</select>
			
			<input type="submit" name="confirm_purchase" value="Confirm Payment" class="navButton" style="width:100%;">
			<br><br>
			<a href="cart.php" style="color:#8b0000; text-decoration:none;">Cancel</a>
		</form>
	</div>
</body>
</html>