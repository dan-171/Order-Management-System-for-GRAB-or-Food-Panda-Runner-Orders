<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
$username = $_SESSION['username'];

// fetch active order
$cartTotal = 0;
$foodStmt = $pdo->prepare("
	SELECT oi.ID, f.Name AS name, f.Type AS type, f.Price AS price, oi.Quantity AS quantity, oi.Subtotal AS subtotal
	FROM order_items oi
	JOIN orders o ON oi.Order_ID = o.ID
	JOIN food f ON oi.foodID = f.foodID
	WHERE o.Member_ID = (SELECT ID FROM members WHERE username = ?) AND o.Status = 'Cart'
");
$foodStmt->execute([$username]);
$foodItems = $foodStmt->fetchAll(PDO::FETCH_ASSOC);
$drinkStmt = $pdo->prepare("
	SELECT oi.ID, d.Name AS name, d.hotPrice, d.coldPrice, oi.Type AS type, oi.Quantity AS quantity, oi.Subtotal AS subtotal
	FROM order_items oi
	JOIN orders o ON oi.Order_ID = o.ID
	JOIN drinks d ON oi.drinkID = d.drinkID
	WHERE o.Member_ID = (SELECT ID FROM members WHERE username = ?) AND o.Status = 'Cart'
");
$drinkStmt->execute([$username]);
$drinkItems = $drinkStmt->fetchAll(PDO::FETCH_ASSOC);
$addonStmt = $pdo->prepare("
	SELECT oi.ID, a.Name AS name, a.Price AS price, oi.Quantity AS quantity, oi.Subtotal AS subtotal
	FROM order_items oi
	JOIN orders o ON oi.Order_ID = o.ID
	JOIN addons a ON oi.addonID = a.addonID
	WHERE o.Member_ID = (SELECT ID FROM members WHERE username = ?) AND o.Status = 'Cart'
");
$addonStmt->execute([$username]);
$addonItems = $addonStmt->fetchAll(PDO::FETCH_ASSOC);
$cartItems = array_merge($foodItems, $drinkItems, $addonItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Your Cart</title><link rel="stylesheet" href="style.css">
<style>
  .discount-row {
    background-color: #f8f9fa;
    color: #28a745;
    font-weight: bold;
  }
  .final-total-row {
    background-color: #e9ecef;
    font-weight: bold;
    font-size: 1.1em;
  }
</style>
</head>
<body>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
  <h3 style="color:green; text-align:center;">Purchase Successful! Check "Order Status" to track your delivery.</h3>
<?php endif; ?>

<h2>Your Active Cart</h2>
<table class="table-5col">
	<tr>
		<th>Item</th>
		<th>Quantity</th>
		<th>Price (RM)</th>
		<th>Subtotal (RM)</th>
		<th>Action</th></tr>
	<?php
		if (count($cartItems) > 0) {
			foreach($cartItems as $cartItem){
				if(isset($cartItem['type']) && ($cartItem['type'] === "Hot" || $cartItem['type'] === "Cold" || $cartItem['type'] === ""))
					$price = ($cartItem['type'] === 'Hot') ? $cartItem['hotPrice'] : $cartItem['coldPrice'];
				else
					$price = $cartItem['price'];
				$cartTotal += $cartItem['subtotal'];
				echo "<tr>
								<td>" . $cartItem['name'] . (!empty($cartItem['type']) ? " (" . $cartItem['type'] . ")" : '') . "</td>
								<td>{$cartItem['quantity']}</td>
								<td>" . number_format($price, 2) . "</td>
								<td>" . number_format($cartItem['subtotal'], 2) . "</td>
								<td><a href='editCartItem.php?id={$cartItem['ID']}' class='navButton' style='padding:5px 10px; font-size:12px;'>Edit/Remove</a></td>
							</tr>";
			}
		} else 
				echo "<tr><td colspan='5'>Cart is empty.</td></tr>";
  ?>
  
  <tr>
    <th style="text-align:right;">Subtotal:</th>
    <th colspan="4">RM <?php echo number_format($cartTotal, 2); ?></th>
  </tr>
  
  <?php
    $discount = $cartTotal * 0.10;
    $finalTotal = $cartTotal - $discount;
  ?>
  <tr class="discount-row">
    <th style="text-align:right;">Member Discount (10%):</th>
    <th colspan="4" style="color:green">- RM <?php echo number_format($discount, 2); ?></th>
  </tr>
  
  <tr class="final-total-row">
    <th style="text-align:right;">Total to Pay:</th>
    <th colspan="4">RM <?php echo number_format($finalTotal, 2); ?></th>
  </tr>
</table>

<div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back to Menu</a>
    <?php if ($cartTotal > 0): ?>
        <a href="purchase.php" class="navButton">Purchase</a>
    <?php endif; ?>
</div>

</body>
</html>