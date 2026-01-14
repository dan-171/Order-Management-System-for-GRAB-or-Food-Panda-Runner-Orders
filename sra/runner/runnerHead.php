<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../../config.php';

// Security: Kick user back to login page if unauthorized
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'runner') {
    header("Location: login.php");
    exit;
}

//By default view the active order panel
$activeOrder = true;
$history = false;
$profile = false;

if (isset($_GET['tab'])) {
    if ($_GET['tab'] === 'history') {
        $activeOrder = false;
        $history = true;
    } elseif ($_GET['tab'] === 'active') {
        $activeOrder = true;
        $history = false;
    }
}

$currentRunnerID = $_SESSION['user']['id'];
$fetchOrders = $pdo->prepare("SELECT 
    o.ID as orderId, 
    o.Type, 
    m.Name as customerName, 
    m.Address as address,
    o.Member_ID AS memberId, 
    o.Runner_ID AS runnerId, 
    o.Total_Amount AS totalAmount, 
    o.Order_Date as 'dates.ordered', 
    o.Ready_Date as 'dates.ready', 
    o.PickedUp_Date as 'dates.picked', 
    o.Delivered_Date as 'dates.delivered', 
    o.Payment_Method as paymentMethod, 
    o.Status 
    FROM orders o 
    JOIN members m ON o.Member_ID = m.ID 
    WHERE o.Status IN ('Readying Order', 'In Transit', 'Delivered')  AND o.Runner_ID = ?
    ORDER BY o.Order_Date ASC");
$fetchOrders->execute([$currentRunnerID]);
$orders = $fetchOrders->fetchAll(PDO::FETCH_ASSOC);

// 2. Fetch Nested Items for each order
foreach($orders as &$order){
    $fetchItems = $pdo->prepare("SELECT 
        oi.Quantity, 
        oi.Subtotal, 
        f.Name as fName, 
        d.Name as dName 
        FROM order_items oi 
        LEFT JOIN food f ON oi.foodID = f.foodID 
        LEFT JOIN drinks d ON oi.drinkID = d.drinkID 
        WHERE oi.Order_ID = ?");
    $fetchItems->execute([$order['orderId']]);
    $order['items'] = $fetchItems->fetchAll(PDO::FETCH_ASSOC);
}
unset($order); // Clean up reference

// 3. Fetch Runner details for the header
$fetchRunners = $pdo->prepare("SELECT 
    ID as id, 
    Name as name, 
    Platform as platform, 
    BirthDate as Bdate, 
    Tel as telephone, 
    Email as email, 
    Status as status,
    Password as password,
    Plate as plate
    FROM runners WHERE ID = ?");
$fetchRunners->execute([$currentRunnerID]);
$runnerData = $fetchRunners->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Runner</title>
   <link rel="stylesheet" href="../../css/common.css">
  <link rel="stylesheet" type="text/css" href="css/runner.css">
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

</head>

<body>
    <header>
      <div class="pageHeader">
          <div class="titleWithLogo">
              <figure><img src="../../images/banner.webp" class="logo" alt="Logo"></figure>
          </div>
          <div id="topText">
              <h2 id="topHeader">Welcome, <?= htmlspecialchars($runnerData['name']) ?> from</h2>
              <?php if ($runnerData['platform'] === 'Grab'): ?>
                  <img src="../../images/grab-food-logo.png" id="logoRunner" alt="Grab">
              <?php elseif ($runnerData['platform'] === 'Food Panda'): ?>
                  <img src="../../images/foodpanda-logo.png"  id="logoRunner" alt="FoodPanda">
              <?php endif; ?>
          </div>
        </div>
        <nav>
            <li class="nav-items"><a href="runnerMain.php" onclick =>📋 Active Order</a></li>
            <li class="nav-items"><a href="runnerHistory.php">📚 History</a></li>
            <li class="nav-items"><a href="runnerProfile.php">☰ Profile</a></li>
        </nav>
    </header>

    <main class="container">
        <div class="left-panel">
            <div class="cardPanel" id = "ordersPanel">
                <div class="cardPanelTitle">Orders List (<span id="orderCount">0</span>)</div>
                <input type="text" class="searchBar" id="searchInput" placeholder="Search..." onkeyup="renderOrderList()">
                <div id="orderList"></div>
            </div>
        </div>
        <div class="right-panel">
            <div class="cardPanel" id="summaryPanel">
                <div class="cardPanelTitle">Order Summary</div>
                <div id="summaryContent">Select an order to view details.</div>
            </div>
            <div class="cardPanel hidden" id="actionPanel">
                <div class="cardPanelTitle" id="actionTitle"></div>
                <div class="action-grid" id="actionButtons"></div>
            </div>
        </div>
    </main>
    <script>
    let orders = <?= json_encode($orders) ?>;
    const currentRunner = <?= json_encode($runnerData) ?>;
    let selectedOrderId = null;  


    // Formats the time from database strings
    function formatTime(dateStr) {
        if (!dateStr || dateStr === '0000-00-00 00:00:00') return '-';
        return new Date(dateStr).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function formatDateTime(dateStr) {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year :'numeric' }) + ', ' + 
              d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function calculateTotal(order) {
        let subtotal = 0;
        order.items.forEach(item => {
            const menu = menuItems[item.itemID];
            if(menu) subtotal += menu.price * item.quantity;
        });

        // check member Id
        let discount = 0;
        if (order.memberId) {
            discount = subtotal * 0.10; // 10% Discount for members
        }

        return {
            finalTotal: (subtotal - discount).toFixed(2),
        };
    }
</script>
