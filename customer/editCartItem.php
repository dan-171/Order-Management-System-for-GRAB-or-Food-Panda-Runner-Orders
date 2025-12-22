<?php
include '../config.php';
session_start();

if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
$username = $_SESSION['username'];

// ID in order_items
$id = $_GET['id'] ?? null;

// fetch current item details
if ($id) {
  $foodStmt = $pdo->prepare("
		SELECT oi.ID, f.Name AS name, f.Type AS type, f.Price AS price, oi.Quantity AS quantity, oi.Subtotal AS subtotal
		FROM order_items oi
		JOIN orders o ON oi.Order_ID = o.ID
		JOIN food f ON oi.foodID = f.foodID
		WHERE o.Member_ID = (SELECT ID FROM members WHERE username = ?) AND oi.ID = ? AND o.Status = 'Cart'
	");
	$foodStmt->execute([$username, $id]);
	$cartItem = $foodStmt->fetch(PDO::FETCH_ASSOC);

	if (!$cartItem) {
		$drinkStmt = $pdo->prepare("
			SELECT oi.ID, d.Name AS name, d.hotPrice, d.coldPrice, oi.Type AS type, oi.Quantity AS quantity, oi.Subtotal AS subtotal
			FROM order_items oi
			JOIN orders o ON oi.Order_ID = o.ID
			JOIN drinks d ON oi.drinkID = d.drinkID
			WHERE o.Member_ID = (SELECT ID FROM members WHERE username = ?) AND oi.ID = ? AND o.Status = 'Cart'
		");
		$drinkStmt->execute([$username, $id]);
		$cartItem= $drinkStmt->fetch(PDO::FETCH_ASSOC);
	}

	if (!$cartItem){
		$addonStmt = $pdo->prepare("
			SELECT oi.ID, a.Name AS name, a.Price AS price, oi.Quantity AS quantity, oi.Subtotal AS subtotal
			FROM order_items oi
			JOIN orders o ON oi.Order_ID = o.ID
			JOIN addons a ON oi.addonID = a.addonID
			WHERE o.Member_ID = (SELECT ID FROM members WHERE username = ?) AND oi.ID = ? AND o.Status = 'Cart'
		");
		$addonStmt->execute([$username, $id]);
		$cartItem = $addonStmt->fetch(PDO::FETCH_ASSOC);
	}
	if (!$cartItem) {
		echo "<script>alert('Cannot edit this item (Order already purchased or invalid).'); window.location.href='cart.php';</script>";
		exit();
	}
}

// handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$newQty = $_POST['quantity'];
	$itemID = $_POST['item_id']; // id from order_items
	if ($cartItem) {
		if (isset($cartItem['price'])) // food or addon
			$subtotal = $cartItem['price'] * $newQty;
		else if (isset($cartItem['hotPrice']) && isset($cartItem['coldPrice'])){ // drink
			$price = ($cartItem['type'] == 'Hot') ? $cartItem['hotPrice'] : $cartItem['coldPrice'];
			$subtotal = $price * $newQty;
		} else
			$subtotal = 0;
	}
	if ($newQty > 0) {
		// update quantity & subtotal
		$update = $pdo->prepare("UPDATE order_items SET Quantity = ?, Subtotal = ? WHERE ID = ?");
		$update->execute([$newQty, $subtotal, $itemID]);
	} else {
		// delete from order_items
		$del = $pdo->prepare("DELETE FROM order_items WHERE ID = ?");
		$del->execute([$itemID]);
	}
	header("Location: cart.php");
	exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Item</title><link rel="stylesheet" href="style.css"></head>
<body>
	<div style="width:300px; margin:50px auto; background:white; padding:20px; text-align:center; border-radius:8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
		<h2 style="margin-top:0;">Edit Quantity</h2>
		
		<?php if ($cartItem): ?>
			<h3 style="color:#555;"><?php echo htmlspecialchars($cartItem['name'] . (!empty($cartItem['type']) ? " (" . $cartItem['type'] . ")" : '')); ?></h3>
				
			<form method="POST" action="">
				<input type="hidden" name="item_id" value="<?php echo $id; ?>">
				
				<label style="font-weight:bold;">Quantity:</label>
				<br>
				<input type="number" name="quantity" value="<?php echo $cartItem['quantity']; ?>" min="0" max="10" style="width:80px; padding:8px; margin:10px 0; font-size:16px; text-align:center;">
				
				<br><br>
					
				<div style="display: flex; gap: 10px; justify-content: center;">
					<input type="submit" value="Update" class="navButton" style="margin:0; flex:1;">
					<a href="cart.php" class="navButton" style="margin:0; flex:1; background-color: #555; text-decoration:none; line-height: 20px;">Cancel</a>
				</div>
			</form>
				
			<br>
			<form method="POST" action="" onsubmit="return confirm('Remove this item?');">
				<input type="hidden" name="item_id" value="<?php echo $id; ?>">
				<input type="hidden" name="quantity" value="0"> <input type="submit" value="Remove Item" style="background:none; border:none; color:red; text-decoration:underline; cursor:pointer;">
			</form>
				
		<?php else: ?>
			<p>Item not found.</p>
			<a href="cart.php">Back</a>
		<?php endif; ?>
	</div>
</body>
</html>