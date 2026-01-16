<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$username = $_SESSION['username'];
$grandTotal = 0;
$order_id = null;

$stmt = $pdo->prepare("SELECT Address FROM members WHERE Username = ?");
$stmt->execute([$username]);
$member = $stmt->fetch();
$currentAddress = $member['Address'] ?? ""; 

$fetchCart = $pdo->prepare("SELECT o.ID, o.Total_Amount FROM orders o JOIN members m ON o.Member_ID = m.ID WHERE m.Username = ? AND o.status = 'cart'");
$fetchCart->execute([$username]);
$cart = $fetchCart->fetch();

if ($cart) {
  $grandTotal = $cart['Total_Amount'] ?? 0;
  $order_id = $cart['ID'];
}

$discount = $grandTotal * 0.10;
$finalTotal = $grandTotal - $discount;

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
		
		<p style="text-align: left; font-weight: bold; margin-bottom: 5px;">Order Summary:</p>
		<div style="text-align: left; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
			<p style="margin: 5px 0;">Subtotal: <strong>RM <?php echo number_format($grandTotal, 2); ?></strong></p>
			<p style="margin: 5px 0; color: #28a745;">Member Discount (10%): <strong>-RM <?php echo number_format($discount, 2); ?></strong></p>
			<hr style="margin: 10px 0;">
			<p style="margin: 5px 0; font-size: 1.1em;">Total Amount: <strong>RM <?php echo number_format($finalTotal, 2); ?></strong></p>
		</div>
		
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
			
			<input type="submit" name="confirm_purchase" value="Confirm Payment (RM <?php echo number_format($finalTotal, 2); ?>)" class="navButton" style="width:100%;">
			<br><br>
			<a href="cart.php" style="color:#8b0000; text-decoration:none;">Cancel</a>
		</form>
	</div>
</body>
</html>