<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['username_unique'])) { header("Location: login.php"); exit(); }
$user = $_SESSION['username_unique'];

// --- HANDLE "CONFIRM RECEIVED" ACTION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_order_id'])) {
    $orderID = $_POST['confirm_order_id'];
    
    // Update status in the main ORDERS table
    $updateStmt = $conn->prepare("UPDATE orders SET delivery_status = 'Completed' WHERE order_id = ?");
    $updateStmt->bind_param("i", $orderID);
    $updateStmt->execute();
    
    header("Location: orderStatus.php");
    exit();
}

// --- FETCH PURCHASE HISTORY ---
// Join orders, order_items, and food tables
$sqlHist = "SELECT o.order_id, f.name, oi.quantity, o.delivery_status, o.driver_name, o.driver_plate, o.order_date
            FROM orders o 
            JOIN order_items oi ON o.order_id = oi.order_id
            JOIN food f ON oi.food_code = f.food_code 
            WHERE o.username = ? AND o.status = 'purchased'
            ORDER BY o.order_date DESC";

$stmtHist = $conn->prepare($sqlHist);
$stmtHist->bind_param("s", $user);
$stmtHist->execute();
$resHist = $stmtHist->get_result();

// --- GROUP DATA BY ORDER ID ---
// This prevents the same order appearing multiple times for every food item
$groupedOrders = [];
if ($resHist->num_rows > 0) {
    while($row = $resHist->fetch_assoc()) {
        $oid = $row['order_id'];
        
        // If this is the first time we see this Order ID, set up the main info
        if (!isset($groupedOrders[$oid])) {
            $groupedOrders[$oid] = [
                'date' => $row['order_date'],
                'status' => $row['delivery_status'],
                'driver_name' => $row['driver_name'],
                'driver_plate' => $row['driver_plate'],
                'items' => [] // Create an empty list for items
            ];
        }
        
        // Add the food item to the list
        $groupedOrders[$oid]['items'][] = "{$row['name']} (x{$row['quantity']})";
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
    
    .driver-popup {
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
    function showDriver(name, plate) {
        document.getElementById('driverName').innerText = name;
        document.getElementById('driverPlate').innerText = plate;
        document.getElementById('driverModal').style.display = 'block';
        document.getElementById('modalOverlay').style.display = 'block';
    }
    function closeDriver() {
        document.getElementById('driverModal').style.display = 'none';
        document.getElementById('modalOverlay').style.display = 'none';
    }
</script>
</head>
<body>

<h2>Order Status</h2>
<table class="menuTable">
    <tr>
        <th>Order ID / Date</th>
        <th>Menu Items</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php
    if (!empty($groupedOrders)) {
        foreach ($groupedOrders as $orderId => $data) {
            $status = $data['status'];
            
            // 1. Determine Badge Color
            $class = 'status-preparing'; 
            if (stripos($status, 'Delivering') !== false) $class = 'status-delivering';
            if (stripos($status, 'Delivered') !== false)  $class = 'status-delivered'; 
            if (stripos($status, 'Completed') !== false)  $class = 'status-completed'; 

            // 2. Determine Button Logic
            $actionBtn = "-";
            
            // CASE A: Delivering -> Show Driver Info
            if (stripos($status, 'Delivering') !== false) {
                $dName = addslashes($data['driver_name']);
                $dPlate = addslashes($data['driver_plate']);
                $actionBtn = "<button onclick=\"showDriver('$dName', '$dPlate')\" class='navButton' style='padding:5px 10px; font-size:12px;'>Driver Info</button>";
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
                    <td><strong>#$orderId</strong><br><small style='color:#777;'>$dateFormatted</small></td>
                    <td style='text-align:left; padding-left:20px;'>$itemsList</td>
                    <td><span class='status-badge $class'>$status</span></td>
                    <td>$actionBtn</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No past orders found.</td></tr>";
    }
    ?>
</table>

<div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back to Menu</a>
</div>

<div id="modalOverlay" class="overlay" onclick="closeDriver()"></div>
<div id="driverModal" class="driver-popup">
    <h3>Driver Details</h3>
    <p><strong>Name:</strong> <span id="driverName"></span></p>
    <p><strong>Vehicle Plate:</strong> <span id="driverPlate"></span></p>
    <br>
    <button onclick="closeDriver()" class="navButton">Close</button>
</div>

</body>
</html>