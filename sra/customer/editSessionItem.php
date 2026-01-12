<?php
include '../config.php';
session_start();

$id = $_GET['id'] ?? null;

// If no id provided, go back
if (!$id) {
	header("Location: result.php");
	exit();
}

$idInDB = $_SESSION["temp_order"][$id]["table"] === "drinks" ? $_SESSION['temp_order'][$id]["drinkID"] : $id;

// fetch item name
if ($_SESSION['temp_order'][$id]['table'] === "drinks")
	$stmt = $pdo->prepare("SELECT Name FROM drinks WHERE drinkID = ?");
else if ($_SESSION['temp_order'][$id]['table'] === "addons")
	$stmt = $pdo->prepare("SELECT Name FROM addons WHERE addonID = ?");
else if ($_SESSION['temp_order'][$id]['table'] === "food")
	$stmt = $pdo->prepare("SELECT Name FROM food WHERE foodID = ?");
$stmt->execute([$idInDB]);
$itemName = $stmt->fetchColumn();

// get current quantity from session
$currentQty = isset($_SESSION["temp_order"][$id]["qty"]) ? $_SESSION["temp_order"][$id]["qty"] : 1;

// update quantity
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$newQty = intval($_POST['quantity']);
	if ($newQty > 0)
		$_SESSION["temp_order"][$id]["qty"] = $newQty;
	else
		unset($_SESSION['temp_order'][$id]);
	header("Location: review.php");
	exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Summary</title><link rel="stylesheet" href="style.css"></head>
<body>
	<div style="width:300px; margin:50px auto; background:white; padding:20px; text-align:center; border-radius:8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
		<h2 style="margin-top:0;">Edit Order</h2>
		<h3 style="color:#555;"><?= htmlspecialchars($itemName) . (!empty($_SESSION['temp_order'][$id]['type']) ? " (" . $_SESSION['temp_order'][$id]['type'] . ")" : ''); ?></h3>
		<form method="POST" action="">
			<input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
			<label style="font-weight:bold;">Quantity:</label>
			<br>
			<input type="number" name="quantity" value="<?php echo $currentQty; ?>" min="0" max="10" style="width:80px; padding:8px; margin:10px 0; font-size:16px; text-align:center;">
			<br><br>
			<div style="display: flex; gap: 10px; justify-content: center;">
				<input type="submit" value="Update" class="navButton" style="margin:0; flex:1;">
				<a href="review.php" class="navButton" style="margin:0; flex:1; background-color: #555; text-decoration:none; line-height: 20px;">Cancel</a>
			</div>
		</form>
	</div>
</body>
</html>