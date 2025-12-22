<?php
include '../config.php';
session_start();

if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
$username = $_SESSION['username'];

// --- HANDLE "CONFIRM RECEIVED" ACTION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_order_id'])) {
	$orderID = $_POST['confirm_order_id'];
	// Update status in the main ORDERS table
	$updateStmt = $pdo->prepare("UPDATE orders SET Status = 'Completed' WHERE ID = ?  AND Member_ID = (SELECT ID FROM members WHERE Username = ?)");
	$updateStmt->execute([$orderID, $username]);
	header("Location: orderStatus.php");
	exit();
}

// --- FETCH PURCHASE HISTORY ---
// Join orders, order_items, and food tables
$sqlHist = "SELECT o.ID, f.Name AS foodName, d.Name AS drinkName, a.Name AS addonName, oi.Quantity, o.Status, o.Runner_ID, r.Name AS runnerName, r.Plate, o.Order_Date
            FROM orders o 
            JOIN order_items oi ON o.ID = oi.Order_ID 
            LEFT JOIN food f ON oi.foodID = f.foodID 
						LEFT JOIN drinks d ON oi.drinkID = d.drinkID 
						LEFT JOIN addons a ON oi.addonID = a.addonID 
						LEFT JOIN runners r ON o.Runner_ID = r.ID
						JOIN members m ON o.Member_ID = m.ID
            WHERE m.Username = ?
            ORDER BY o.order_date DESC";

$stmtHist = $pdo->prepare($sqlHist);
$stmtHist->execute([$username]);
$history = $stmtHist->fetchAll();

// --- GROUP DATA BY ORDER ID ---
// This prevents the same order appearing multiple times for every food item
$groupedOrders = [];
if (!empty($history)) {
	foreach ($history as $row) {
		$oid = $row['ID'];
		if (!isset($groupedOrders[$oid])) {
			$groupedOrders[$oid] = [
				'date' => $row['Order_Date'],
				'status' => $row['Status'],
				'runner_id' => $row['Runner_ID'],
				'runner_name' => $row['runnerName'],
				'runner_plate' => $row['Plate'],
				'items' => []
			];
		}
		// determine which column has a value
		if (!empty($row['foodName']))
			$itemStr = "{$row['foodName']} (x{$row['Quantity']})";
		elseif (!empty($row['drinkName']))
			$itemStr = "{$row['drinkName']} (x{$row['Quantity']})";
		elseif (!empty($row['addonName']))
			$itemStr = "{$row['addonName']} (x{$row['Quantity']})";
		else
			$itemStr = "Unknown Item (x{$row['Quantity']})"; // fallback

		$groupedOrders[$oid]['items'][] = $itemStr;
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Order Status</title><link rel="stylesheet" href="style.css">
<style>
	.status-badge {
			padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; color: white;
	}
	.status-preparing { background-color: #f0ad4e; } 
	.status-delivering { background-color: #0275d8; } 
	.status-delivered { background-color: #5cb85c; } 
	.status-completed { background-color: #777; }    
	
	.runner-popup {
			display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
			background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.5); border-radius: 8px; z-index: 1001; text-align: center;
			width: 300px;
	}
	.overlay {
			display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
			background: rgba(0,0,0,0.5); z-index: 1000;
	}
</style>

<script>
	function showrunner(name, plate) {
		document.getElementById('runnerName').innerText = name;
		document.getElementById('runnerPlate').innerText = plate;
		document.getElementById('runnerModal').style.display = 'block';
		document.getElementById('modalOverlay').style.display = 'block';
	}
	function closerunner() {
		document.getElementById('runnerModal').style.display = 'none';
		document.getElementById('modalOverlay').style.display = 'none';
	}
</script>
</head>
<body>

<h2>Order Status</h2>
<table class="menuTable">
	<tr>
		<th style="width:25% !important;">Order ID / Date</th>
		<th style="width:50% !important;">Menu Items</th>
		<th>Status</th>
		<th>Action</th>
	</tr>

	<?php
	if (!empty($groupedOrders)) {
		foreach ($groupedOrders as $orderId => $data) {
			$status = $data['status'];
			
			// determine badge colour
			$class = 'status-preparing'; 
			if (stripos($status, 'Readying Order') !== false || stripos($status, 'In Transit') !== false) $class = 'status-delivering';
			if (stripos($status, 'Delivered') !== false)  $class = 'status-delivered'; 
			if (stripos($status, 'Completed') !== false)  $class = 'status-completed'; 

			// determine Button Logic
			$actionBtn = "-";
			
			// CASE A: Delivering -> Show runner Info
			if (stripos($status, 'In Transit') !== false) {
				$dName = addslashes($data['runner_name']);
				$dPlate = addslashes($data['runner_plate']);
				$actionBtn = "<button onclick=\"showrunner('$dName', '$dPlate')\" class='navButton' style='padding:5px 10px; font-size:12px;'>runner Info</button>";
			}
			
			// CASE B: Delivered -> Show "Confirm Received" Button
			else if (stripos($status, 'Delivered') !== false) {
				$actionBtn = "
				<form method='POST' action='' style='margin:0;'>
					<input type='hidden' name='confirm_order_id' value='$orderId'>
					<input type='submit' value='Confirm Received' class='navButton' style='background-color:#5cb85c; padding:5px 10px; font-size:12px;'>
				</form>";
			}

			// 3. Format Items List (e.g., "Burger (x1)<br> Fries (x2)")
			$itemsList = implode('<br>', $data['items']);
			$dateFormatted = date("d M Y, h:i A", strtotime($data['date']));

			echo "<tr>
							<td style='width:25% !important;'><strong>#$orderId</strong><br><small style='color:#777;'>$dateFormatted</small></td>
							<td style='text-align:left; padding-left:20px; width:50% !important;'>$itemsList</td>
							<td><span class='status-badge $class'>$status</span></td>
							<td>$actionBtn</td>
						</tr>";
		}
	} else
		echo "<tr><td colspan='4'>No past orders found.</td></tr>";
	?>
</table>

<div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back to Menu</a>
</div>

<div id="modalOverlay" class="overlay" onclick="closerunner()"></div>
<div id="runnerModal" class="runner-popup">
    <h3>runner Details</h3>
    <p><strong>Name:</strong> <span id="runnerName"></span></p>
    <p><strong>Vehicle Plate:</strong> <span id="runnerPlate"></span></p>
    <br>
    <button onclick="closerunner()" class="navButton">Close</button>
</div>

</body>
</html>