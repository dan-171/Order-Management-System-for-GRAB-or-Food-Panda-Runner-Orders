<?php
require_once '../../config.php';

session_start();

$selectedOrderId = isset($_GET['order_id']) ? $_GET['order_id'] : null;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$msg = ""; 

// POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // update order status
    if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
        $p_oid = $_POST['order_id'];
        $p_status = $_POST['status'];
        
        $timeSql = "";
        if ($p_status == 'Readying Order') $timeSql = ", Ready_Date = NOW()";
        if ($p_status == 'In Transit') $timeSql = ", PickedUp_Date = NOW()"; 
        if ($p_status == 'Delivered') $timeSql = ", Delivered_Date = NOW()";
        
        $sql = "UPDATE orders SET Status = ? $timeSql WHERE ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$p_status, $p_oid]);

        $_SESSION['msg'] = "Order ORD-$p_oid updated to: $p_status";
    }

    // assign runner
    if (isset($_POST['action']) && $_POST['action'] === 'assign_runner') {
        $p_oid = $_POST['order_id'];
        $p_rid = $_POST['runner_id'];

        // Auto-assign logic, update runner id to order table
        if ($p_rid === 'auto') {
            $stmt = $pdo->query("SELECT ID FROM runners WHERE Status = 'Active' ORDER BY RAND() LIMIT 1");
            $luckyRunner = $stmt->fetch(PDO::FETCH_ASSOC);
            $p_rid = $luckyRunner ? $luckyRunner['ID'] : null;
            $msgPart = $p_rid ? "Auto-assigned runner." : "No active runners found!";
        } else {
            $msgPart = "Runner assigned.";
        }

        // Update order with selected runner
        if ($p_rid) {
            $sql = "UPDATE orders SET Runner_ID = ? WHERE ID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$p_rid, $p_oid]);
            $_SESSION['msg'] = "Order ORD-$p_oid: $msgPart";
        }
    }

    // Redirect back to avoid form resubmission when refreshing
    $redirectUrl = "staffMain.php?order_id=" . $p_oid;
    if($searchQuery) $redirectUrl .= "&search=" . urlencode($searchQuery);
    
    header("Location: " . $redirectUrl);
    exit;
}

// GET
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// get order list
$sqlOrders = "SELECT o.*, m.Name as MemberName 
              FROM orders o 
              LEFT JOIN members m ON o.Member_ID = m.ID 
              WHERE o.Status != 'Completed' ";

// search filter
if ($searchQuery) {
    $cleanId = str_ireplace(['ORD-','ORD'], '', $searchQuery);
    $sqlOrders .= " AND (o.ID LIKE ? OR m.Name LIKE ? OR o.Type LIKE ?)";
    $params = ["%$cleanId%", "%$searchQuery%", "%$searchQuery%"];
} else {
    $params = [];
}
$sqlOrders .= " ORDER BY o.Order_Date DESC";

$stmt = $pdo->prepare($sqlOrders);
$stmt->execute($params);
$ordersList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get active runners list
$stmtRunners = $pdo->query("SELECT * FROM runners WHERE Status = 'Active'");
$runnersList = $stmtRunners->fetchAll(PDO::FETCH_ASSOC);

// get selected order details
$currentOrder = null;
$currentItems = [];

if ($selectedOrderId) {
    $sqlDetail = "SELECT o.*, m.Name as MemberName, r.Name as RunnerName, r.Platform, r.Tel as RunnerTel
                  FROM orders o 
                  LEFT JOIN members m ON o.Member_ID = m.ID
                  LEFT JOIN runners r ON o.Runner_ID = r.ID
                  WHERE o.ID = ?";
    $stmtDetail = $pdo->prepare($sqlDetail);
    $stmtDetail->execute([$selectedOrderId]);
    $currentOrder = $stmtDetail->fetch(PDO::FETCH_ASSOC);

    // get order items
    if ($currentOrder) {
        $sqlItems = "SELECT oi.*, 
                     COALESCE(f.Name, d.Name, a.Name) as ItemName,
                     CASE 
                        WHEN oi.foodID IS NOT NULL THEN f.Price
                        WHEN oi.addonID IS NOT NULL THEN a.Price
                        WHEN oi.drinkID IS NOT NULL AND oi.Type = 'Hot' THEN d.hotPrice
                        WHEN oi.drinkID IS NOT NULL AND oi.Type = 'Cold' THEN d.coldPrice
                        ELSE 0
                     END as UnitPrice
                     FROM order_items oi
                     LEFT JOIN food f ON oi.foodID = f.foodID
                     LEFT JOIN drinks d ON oi.drinkID = d.drinkID
                     LEFT JOIN addons a ON oi.addonID = a.addonID
                     WHERE oi.Order_ID = ?";
        $stmtItems = $pdo->prepare($sqlItems);
        $stmtItems->execute([$selectedOrderId]);
        $currentItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Staff</title>
  <link rel="stylesheet" type="text/css" href="css/staffMainStyle.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>

  <header>
    <div class="titleWithLogo">
        <h1>SUP TULANG ZZ</h1>
        <figure>
            <img src="../RESOURCE/menuzz/Slide1.jpeg" class="logo" alt="Logo">
        </figure>
    </div>
    <nav>
      <a href="staffMain.php">DASHBOARD</a>
      <a href="staffMenu.php">MENU</a>
    </nav>
  </header>

  <main class="container">

    <div class="left-panel">
      <div class="cardPanel">
        <div class="cardPanelTitle">Active Orders (<?php echo count($ordersList); ?>)</div>
        
        <form method="GET" action="staffMain.php">
            <input type="text" name="search" class="searchBar" placeholder="Search ID..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <?php if($selectedOrderId): ?><input type="hidden" name="order_id" value="<?php echo $selectedOrderId; ?>"><?php endif; ?>
        </form>

        <div id="orderList">
            <?php if (empty($ordersList)): ?>
                <div style="padding:20px; text-align:center; color:#888;">No active orders found.</div>
            <?php else: ?>
                <?php foreach ($ordersList as $ord): ?>
                    <?php 
                        // Determine status class
                        $statusClass = 'st-placed';
                        $s = strtolower($ord['Status']);
                        if (strpos($s, 'readying') !== false) $statusClass = 'st-readying';
                        elseif (strpos($s, 'transit') !== false || strpos($s, 'picked') !== false) $statusClass = 'st-picked';
                        elseif (strpos($s, 'delivered') !== false) $statusClass = 'st-delivered';

                        $isSelected = ($ord['ID'] == $selectedOrderId) ? 'selected' : '';
                        $custName = $ord['MemberName'] ? $ord['MemberName'] : "Walk-in Guest";
                        $timeStr = date("d M, H:i", strtotime($ord['Order_Date']));
                    ?>

                    <!-- Order Card -->
                    <div class="order-card <?php echo $isSelected; ?>">
                        <a href="?order_id=<?php echo $ord['ID']; ?>&search=<?php echo urlencode($searchQuery); ?>" style="text-decoration:none; color:inherit; display:block;">
                            <div class="info-row">
                                <strong>ORD-<?php echo $ord['ID']; ?></strong>
                                <span>RM <?php echo $ord['Total_Amount']; ?></span>
                            </div>
                            <div style="font-size:11px; color:#888; margin-bottom:4px;">
                                📅 <?php echo $timeStr; ?>
                            </div>
                            <div style="font-size:13px; color:#555; margin-bottom:5px;">
                                <?php echo htmlspecialchars($custName); ?> (<?php echo $ord['Type']; ?>)
                            </div>
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <?php echo strtoupper($ord['Status']); ?>
                            </span>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="right-panel">
      <!-- Order Summary Panel -->
      <div class="cardPanel" id="summaryPanel">
        <div class="cardPanelTitle">Order Summary</div>
        
        <?php if (!$currentOrder): ?>
            <div style="color:gray; padding:20px;">Select an order from the list to view details.</div>
        <?php else: ?>
            <div style="margin-bottom:10px;">
                <h3 style="font-size:20px; display:inline-block; margin-right:5px;">
                    <?php echo $currentOrder['MemberName'] ? htmlspecialchars($currentOrder['MemberName']) : "Walk-in Guest"; ?>
                </h3>
                <?php if($currentOrder['MemberName']): ?>
                    <span style="color:#f57c00; font-weight:bold; font-size:12px;">★ MEMBER</span>
                <?php endif; ?>
            </div>

            <!-- Order Details -->
            <div style="margin-bottom:12px; padding-bottom:8px; border-bottom:1px solid #ddd; font-size:14px; color:#444;">
                <p>Order ID: <strong>ORD-<?php echo $currentOrder['ID']; ?></strong></p>
                <p>Type: <strong><?php echo strtoupper($currentOrder['Type']); ?></strong></p>
                <p>Payment: <?php echo $currentOrder['Payment_Method']; ?></p>
            </div>

            <div style="background:white; padding:10px; border:1px solid #ddd; border-radius:6px;">
                <?php 
                    $subtotal = 0;
                    foreach($currentItems as $item): 
                        $lineTotal = $item['UnitPrice'] * $item['Quantity'];
                        $subtotal += $lineTotal;
                ?>
                    <div class="info-row">
                        <span>
                            <?php echo $item['Quantity']; ?>x 
                            <?php echo htmlspecialchars($item['ItemName']); ?>
                            <?php echo $item['Type'] ? '('.$item['Type'].')' : ''; ?>
                        </span> 
                        <span>RM <?php echo number_format($lineTotal, 2); ?></span>
                    </div>
                <?php endforeach; ?>

                <?php 
                    $dbSubtotal = $currentOrder['subTotal'];
                    $dbTotal = $currentOrder['Total_Amount'];
                    
                    // calculate discount amount
                    $discountAmount = $dbSubtotal - $dbTotal;
                    $isMember = !empty($currentOrder['MemberName']);
                    
                    // display subtotal
                    echo '<div style="margin-top:10px; padding-top:5px; border-top:1px dashed #eee; font-size:13px; color:#555; display:flex; justify-content:space-between;">';
                    echo '<span>Subtotal</span>';
                    echo '<span>RM ' . number_format($dbSubtotal, 2) . '</span>';
                    echo '</div>';

                    // display discount if applicable
                    if ($isMember && $discountAmount > 0) {
                        echo '<div class="discount-row">';
                        echo '<span>Member Discount(-10%)</span>';
                        echo '<span>-RM ' . number_format($discountAmount, 2) . '</span>';
                        echo '</div>';
                    }
                ?>
                
                <!--  display total amount -->
                <div class="total-row">
                    <span>TOTAL AMOUNT</span> 
                    <span>RM <?php echo number_format($dbTotal, 2); ?></span>
                </div>
            </div>

            <hr style="margin:15px 0; border:0; border-top:1px solid #ccc;">
            <div class="info-row"><small>Ordered:</small> <small><?php echo date("H:i", strtotime($currentOrder['Order_Date'])); ?></small></div>
            <div class="info-row"><small>Ready:</small> <small><?php echo $currentOrder['Ready_Date'] ? date("H:i", strtotime($currentOrder['Ready_Date'])) : '-'; ?></small></div>
            <?php if($currentOrder['Type'] == 'online'): ?>
                <div class="info-row"><small>Picked Up:</small> <small><?php echo $currentOrder['PickedUp_Date'] ? date("H:i", strtotime($currentOrder['PickedUp_Date'])) : '-'; ?></small></div>
            <?php endif; ?>
            <div class="info-row"><small>Delivered:</small> <small><?php echo $currentOrder['Delivered_Date'] ? date("H:i", strtotime($currentOrder['Delivered_Date'])) : '-'; ?></small></div>

        <?php endif; ?>
      </div>

      <?php if ($currentOrder): ?>
        
        <?php if ($currentOrder['Type'] == 'online'): ?>
            <div class="cardPanel" id="deliveryPanel">
                <div class="cardPanelTitle">Delivery Assignment</div>
                
                <?php if ($currentOrder['Runner_ID']): ?>
                    <div style="background:white; padding:10px; border:1px solid #4caf50; border-radius:6px; border-left: 5px solid #4caf50;">
                        <div style="font-weight:bold; color:#4caf50;">✓ Runner Assigned</div>
                        <div>Name: <strong><?php echo htmlspecialchars($currentOrder['RunnerName']); ?></strong></div>
                        <div>Platform: <?php echo $currentOrder['Platform']; ?></div>
                        <div>Tel: <?php echo $currentOrder['RunnerTel']; ?></div>
                    </div>
                <?php else: ?>
                    <form method="POST" style="display:flex; gap:10px;">
                        <input type="hidden" name="action" value="assign_runner">
                        <input type="hidden" name="order_id" value="<?php echo $currentOrder['ID']; ?>">
                        
                        <select name="runner_id" style="flex:1; padding:8px; border:2px solid black; border-radius:6px;" required>
                            <option value="" disabled selected>Select Runner...</option>
                            <option value="auto">Auto-Assign (Random)</option>
                            <?php foreach($runnersList as $r): ?>
                                <option value="<?php echo $r['ID']; ?>"><?php echo htmlspecialchars($r['Name']); ?> (<?php echo $r['Platform']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="primary">Assign</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="cardPanel" id="actionPanel">
            <div class="cardPanelTitle">
                <?php echo ($currentOrder['Type'] == 'walk-in') ? "Walk-in Flow" : "Online Order Flow"; ?>
            </div>
            
            <div class="action-grid">
                <?php 
                    $st = $currentOrder['Status'];
                    
                    function renderBtn($oid, $newStatus, $label, $isDisabled, $isPrimary=false) {
                        $cls = $isPrimary ? 'primary' : '';
                        $disAttr = $isDisabled ? 'disabled' : '';
                        echo "
                        <form method='POST'>
                            <input type='hidden' name='action' value='update_status'>
                            <input type='hidden' name='order_id' value='$oid'>
                            <input type='hidden' name='status' value='$newStatus'>
                            <button type='submit' class='$cls' $disAttr>$label</button>
                        </form>
                        ";
                    }
                ?>

                <?php if ($currentOrder['Type'] == 'walk-in'): ?>
                    <?php 
                        $canReady = ($st == 'Order Placed');
                        $canComplete = ($st == 'Readying Order'); 
                    ?>
                    <?php renderBtn($currentOrder['ID'], 'Readying Order', '1. Readying Order', !$canReady); ?>
                    <?php renderBtn($currentOrder['ID'], 'Completed', '2. Delivered & Complete', !$canComplete, true); ?>
                
                <?php else: ?>
                    <?php 
                        $canReady = ($st == 'Order Placed');
                        
                        // check if runner assigned and not already picked up
                        $hasRunner = !empty($currentOrder['Runner_ID']);
                        $alreadyPicked = ($st == 'In Transit' || $st == 'Delivered');
                        
                        // only allow pick up if order is readying and has runner assigned
                        $canPickUp = ($st == 'Readying Order') && $hasRunner;

                        $pickUpText = "2. Picked Up (In Transit)";
                        if (!$hasRunner && $st == 'Readying Order') $pickUpText .= " [Assign Runner First]";
                        if ($alreadyPicked) $pickUpText = "2. Picked Up (In Transit)";
                    ?>
                    
                    <?php renderBtn($currentOrder['ID'], 'Readying Order', '1. Readying Order', !$canReady); ?>
                    
                    <?php renderBtn($currentOrder['ID'], 'In Transit', $pickUpText, !$canPickUp); ?>

                    <?php if($st == 'In Transit'): ?>
                        <div style="grid-column: 1 / -1; text-align:center; padding:10px; color:#666; font-size:13px; background:#e8e8e8; border-radius:6px;">
                            Status: <strong>In Transit</strong>. Delivery update managed by Runner.
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>

      <?php endif; ?>

    </div>
  </main>

  <footer class="notifications">
      <?php echo $msg ? htmlspecialchars($msg) : "System Ready."; ?>
  </footer>

  <?php if($msg): ?>
  <script>
      setTimeout(function(){
          document.querySelector('.notifications').innerText = "System Ready.";
      }, 3000);
  </script>
  <?php endif; ?>

</body>
</html>