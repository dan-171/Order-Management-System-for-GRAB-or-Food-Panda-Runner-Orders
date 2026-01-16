<?php
include 'config.php';
session_start();

$fetchFoods = $pdo->prepare("SELECT * FROM food ORDER BY CAST(SUBSTRING(foodID,2) AS UNSIGNED)");
$fetchFoods->execute();
$foods = $fetchFoods->fetchAll(PDO::FETCH_ASSOC); 

$foodsBy_foodID = [];
foreach ($foods as $food) {
  $foodsBy_foodID[$food['foodID']] = $food;
}

$fetchDrinks = $pdo->prepare("SELECT * FROM drinks ORDER BY CAST(SUBSTRING(drinkID,2) AS UNSIGNED)");
$fetchDrinks->execute();
$drinks = $fetchDrinks->fetchAll(PDO::FETCH_ASSOC); 

$drinksBy_drinkID = [];
foreach ($drinks as $drink) {
  $drinksBy_drinkID[$drink['drinkID']] = $drink;
}

$fetchAddons = $pdo->prepare("SELECT * FROM addons ORDER BY CAST(SUBSTRING(addonID,2) AS UNSIGNED)");
$fetchAddons->execute();
$addons = $fetchAddons->fetchAll(PDO::FETCH_ASSOC); 

$addonsBy_addonID = [];
foreach ($addons as $addon) {
  $addonsBy_addonID[$addon['addonID']] = $addon;
}

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

if (empty($_SESSION['temp_order'])) {
	echo "<script>alert('No items to add!'); window.location.href='customerpage.php';</script>";
	exit();
}

$username = $_SESSION['username'];
$memberID = $_SESSION['memberID'];
$cartItems = $_SESSION['temp_order'];

$getCurrCart = $pdo->prepare("SELECT ID FROM orders WHERE Member_ID = ? AND Status = 'Cart' LIMIT 1");
$getCurrCart->execute([$memberID]);
$cart = $getCurrCart->fetch();

if ($cart)
  $orderID = $cart['ID'];
else {
	$createOrder = $pdo->prepare("INSERT INTO orders (Member_ID, Status) VALUES (?, 'Cart')");
	$createOrder->execute([$memberID]);
	$getNewOrderID = $pdo->prepare("SELECT ID FROM orders WHERE Member_ID = ? AND Status = 'Cart'");
	$getNewOrderID->execute([$memberID]);
	$orderID = $getNewOrderID->fetchColumn();
}

$itemIdMap = [
  'drinks' => 'drinkID',
  'food'   => 'foodID',
  'addons' => 'addonID'
];

foreach ($cartItems as $id => $item) {
	if (!isset($itemIdMap[$item['table']])) continue;
	$itemId = $itemIdMap[$item['table']];
	$realItemId = ($item['table'] === 'drinks') ? $item['drinkID'] : $id;
	$type = $item['type'] ?? null;

	if ($item['table'] === 'drinks')
		$price = ($item['type'] === 'Hot') ? $drinksBy_drinkID[$realItemId]['hotPrice'] : $drinksBy_drinkID[$realItemId]['coldPrice'];
	else if ($item['table'] === 'addons')
		$price = $addonsBy_addonID[$realItemId]['Price'];
	else
		$price = $foodsBy_foodID[$realItemId]['Price'];
  $subtotal = $price * $item['qty'];

	$ifItemExistsInOrder = $pdo->prepare("SELECT ID FROM order_items WHERE Order_ID = ? AND {$itemId} = ? AND ((Type = ?) OR (Type IS NULL AND ? IS NULL))");
	$ifItemExistsInOrder->execute([$orderID, $realItemId, $type, $type]);
	$itemExistsInOrder = $ifItemExistsInOrder->fetch();

	if ($itemExistsInOrder){
		$updateStmt = $pdo->prepare("UPDATE order_items SET Quantity = Quantity + ?,  Subtotal = Subtotal + ? WHERE Order_ID = ? AND {$itemId} = ? AND ((Type = ?) OR (Type IS NULL AND ? IS NULL))");
		$updateStmt->execute([$item['qty'], $subtotal, $orderID, $realItemId, $type, $type]);
	}else{
		$updateStmt = $pdo->prepare("INSERT INTO order_items (Order_ID, $itemId, Type, Quantity, Subtotal) VALUES (?, ?, ?, ?, ?)");
		$updateStmt->execute([$orderID, $realItemId, $item['type'] ?? null, $item['qty'], $subtotal]);
	}
	$updateStmt = $pdo->prepare("UPDATE orders SET Total_Amount = Total_Amount + ? WHERE ID = ?");
	$updateStmt->execute([$subtotal, $orderID]);
}

unset($_SESSION['temp_order']);
echo "<script>alert('Items added to cart successfully!'); window.location.href='cart.php';</script>";
?>