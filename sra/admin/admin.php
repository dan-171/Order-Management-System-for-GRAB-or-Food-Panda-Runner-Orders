<?php
  session_start();
  include '../../config.php';

  // kick user back to login page if url directly entered
  if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
  }

  //add or update staff
  $editing = false;
  $staffIdToEdit = null;
  $idToDel = null;
  $msg = $_SESSION['msg'] ?? null;
  //disable/activate runner
  $runnerStatus = null;

  if(isset($_SESSION['staffIdToEdit'])){ //fetch id to edit to switch into staff edit mode
    $editing = true;
    $staffIdToEdit = $_SESSION['staffIdToEdit'];
  }

  $runnerToView = null;
  if(isset($_SESSION['runnerToView'])){ //fetch more details for specified runner
    $runnerToView = $_SESSION['runnerToView'];
    unset($_SESSION['runnerToView']);
  }

  //cpw msg
  $cpwMsg = $_SESSION["cpwMsg"] ?? null;
  unset($_SESSION["cpwMsg"]);

  //fetch admin current pw
  $fetchAdmin = $pdo->prepare("SELECT * FROM admin WHERE ID = ?");
  $fetchAdmin->execute([$_SESSION['user']['id']]);
  $admin = $fetchAdmin->fetch(PDO::FETCH_ASSOC); 

  $runners = [];
  $searchError = "";

  //restaurant
  //generate ID for new menu item
  function generateNextId(PDO $pdo, string $table, string $column, string $prefix): string {
    $stmt = $pdo->prepare("SELECT $column FROM $table ORDER BY $column DESC LIMIT 1");
    $stmt->execute();
    $lastId = $stmt->fetchColumn();
    if ($lastId) {
      $num = (int) substr($lastId, 1); // remove prefix
      $num++;
    } else
      $num = 1;
    return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
  }

  //item panel
  //fetch items
  $fetchFood = $pdo->prepare("SELECT foodID AS ID, Category, Section, Type, Name, Price AS 'Price (RM)'FROM food");
  $fetchFood->execute();
  $foods = $fetchFood->fetchAll(PDO::FETCH_ASSOC);
  $foodCat = array_values(array_unique(array_column($foods, 'Category')));
  $foodSection = array_values(array_unique(array_column($foods, 'Section')));
  $foodType = array_values(array_unique(array_column($foods, 'Type')));

  $fetchDrinks = $pdo->prepare("SELECT drinkID AS ID, Section, Name, hotPrice AS 'Price (RM)[Hot]', coldPrice AS 'Price (RM)[Cold]' FROM drinks");
  $fetchDrinks->execute();
  $drinks = $fetchDrinks->fetchAll(PDO::FETCH_ASSOC);
  $drinksSection = array_values(array_unique(array_column($drinks, 'Section')));

  $fetchAddons = $pdo->prepare("SELECT addonID AS ID, Category, Section, Name, Price AS 'Price (RM)' FROM addons");
  $fetchAddons->execute();
  $addons = $fetchAddons->fetchAll(PDO::FETCH_ASSOC);
  $addonCat = array_values(array_unique(array_column($addons, 'Category')));
  $addonSection = array_values(array_unique(array_column($addons, 'Section')));

  $items = []; // for item table headers
  $itemTypeCM = $_GET['itemTypeCM'] ?? 'food';
  $itemType = $_POST['itemType'] ?? $_GET['itemType'] ?? 'food';
  $itemCat  = $_POST['itemCat']  ?? $_GET['itemCat']  ?? 'All';

  $catForItems = [
    "food" => $foodCat,
    "addons" => $addonCat
  ];

  $sectionForItems = [
    "food" => $foodSection,
    "drinks" => $drinksSection,
    "addons" => $addonSection
  ];

  // category select
  $catListCM = ($itemTypeCM !== "drinks") ? $catForItems[$itemTypeCM] ?? [] : [];
  $catList = ($itemType !== "drinks") ? array_merge(['All'], $catForItems[$itemType] ?? []) : [];

  // section select
  $sectionListCM = $sectionForItems[$itemTypeCM] ?? [];

  // items for table
  if ($itemType === "food")
    $items = $foods;
  elseif ($itemType === "drinks")
    $items = $drinks;
  elseif ($itemType === "addons")
    $items = $addons;
  else
    $items = [];
  // table headers
  $itemKeys = !empty($items) ? array_merge(array_keys($items[0]), [""]) : [];
  // filter by category
  $itemsByCat = [];
  if ($itemCat === "All" || $itemType === "drinks")
    $itemsByCat = $items;
  else {
    foreach ($items as $item) {
      if (trim($item["Category"]) === trim($itemCat))
        $itemsByCat[] = $item;
    }
  }

  $itemCreateMsg = $_SESSION['itemCreateMsg'] ?? "";
  unset ($_SESSION['itemCreateMsg']);
  $itemUpdateMsg = $_SESSION['itemUpdateMsg'] ?? "";
  unset ($_SESSION['itemUpdateMsg']);

  //order panel
  $fetchOrders = $pdo->prepare("SELECT ID, Type, Member_ID AS 'Member ID', Runner_ID AS 'Runner ID', Total_Amount AS 'Total (RM)', Status FROM orders WHERE Status != 'Cart' ORDER BY Order_Date");
  $fetchOrders->execute();
  $allOrders = $fetchOrders->fetchAll(PDO::FETCH_ASSOC);

  $orderStatus  = $_GET['orderStatus']  ?? 'All';

  if($_SERVER["REQUEST_METHOD"] === "POST"){
    // staff/runner acc
    if (isset($_POST['staffIdToEdit'])) //switch to edit staff mode
      $_SESSION["staffIdToEdit"] = $_POST["staffIdToEdit"];
    else if(isset($_POST["updateStaffBtn"])){ //update staff
      if(empty(trim($_POST['staffPw'])))
        $_SESSION["msg"] = "Update failed - Password cannot be left blank!";
      else{
        $staffPw = password_hash(trim($_POST["staffPw"]), PASSWORD_DEFAULT);
        $updateStaff = $pdo->prepare("UPDATE staff SET Password = ? WHERE ID = ?");
        $updateStaff->execute([$staffPw, $staffIdToEdit]);
        unset($_SESSION["staffIdToEdit"]);
        $_SESSION["msg"] = "✅ Staff {$staffIdToEdit} updated successfully!";
      }
    }else if(isset($_POST["cancelStaffEditBtn"])){ //cancel staff edit
      unset($_SESSION["staffIdToEdit"]);
    }else if(isset($_POST["createStaffBtn"])){ //new staff
      if(empty(trim($_POST["staffPw"])))
        $_SESSION["msg"] = "Staff account creation failed - Password cannot be left blank!";
      else{
        $staffId = trim($_POST["staffId"]);
        $staffPw = password_hash(trim($_POST["staffPw"]), PASSWORD_DEFAULT);
        $newStaff = $pdo->prepare("INSERT INTO staff(ID, Password) VALUES(?, ?)");
        $newStaff->execute([$staffId, $staffPw]);
      }
    }else if(isset($_POST["idToDel"])){ //del staff or runner
      $idToDel = $_POST["idToDel"];
      if($_POST["type"] === "staff")
        $delAcc = $pdo->prepare("DELETE FROM staff WHERE ID = ?");
      else if($_POST["type"] === "runner")
        $delAcc = $pdo->prepare("DELETE FROM runners WHERE ID = ?");
      $delAcc->execute([$idToDel]);
    }else if(isset($_POST["runnerIdToEdit"])){ //disable/activate runner acc
      $runnerId = $_POST["runnerIdToEdit"];
      $runnerStatus = ($_POST["runnerStatus"] === "Active") ? "Disabled" : "Active";
      $updateRunner = $pdo->prepare("UPDATE runners SET Status = ? WHERE ID = ?");
      $updateRunner->execute([$runnerStatus, $runnerId]);
    }else if(isset($_POST["runnerIdToView"])) {
      $fetchRunnerToView = $pdo->prepare("SELECT * FROM runners WHERE ID = ?");
      $fetchRunnerToView->execute([$_POST["runnerIdToView"]]);
      $_SESSION["runnerToView"] = $fetchRunnerToView->fetch(PDO::FETCH_ASSOC);
    } 

    //admin acc
    //prevent empty entry;
    if (empty(trim($_POST["currPw"])) || empty(trim(($_POST["newPw"]))) && empty(trim(($_POST["newPwRe"]))))
      $_SESSION["cpwMsg"] = "Please fill in all required fields.";
    else{
      if (password_verify(trim($_POST["currPw"]), $admin["Password"])){ //check if curr pw matches that in db
        if($_POST["newPw"] === $_POST["newPwRe"]){ //check if new pw = new pw retyped
          $newPw = password_hash(trim($_POST["newPw"]), PASSWORD_DEFAULT);
          $updatePw = $pdo->prepare("UPDATE admin SET Password = ? WHERE ID = ?");
          $updatePw->execute([$newPw, $_SESSION["user"]["id"]]);
          $_SESSION['cpwDone'] = true;
        }else $_SESSION["cpwMsg"] = "Your retyped password doesn’t match. Please try again";
      }else $_SESSION["cpwMsg"] = "Your current password doesn't match. Please try again";
    }

    //create menu
    if (isset($_POST['createItemBtn'])) {
      $itemTypeCM = $_POST['itemTypeCM'] ?? 'food';
      $name       = trim($_POST['nameCM'] ?? '');
      $section    = trim($_POST['itemSectionCM'] ?? '');
      if ($name === '' || $section === '') {
        $_SESSION['itemCreateMsg'] = "❌ Name and Section are required.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
      }
      if ($itemTypeCM === 'food') {
        $category = trim($_POST['itemCatCM'] ?? '');
        $type     = trim($_POST['foodTypeCM'] ?? null);
        $price    = trim($_POST['priceCM'] ?? '');
        if ($price === '' || !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
          $_SESSION['itemCreateMsg'] = "❌ Invalid food price.";
          header("Location: " . $_SERVER['PHP_SELF']);
          exit;
        }
        $foodID = generateNextId($pdo, 'food', 'foodID', 'F');
        $stmt = $pdo->prepare("INSERT INTO food (foodID, Category, Section, Type, Name, Price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$foodID, $category, $section, $type !== '' ? $type : null, $name, $price]);
      }
      elseif ($itemTypeCM === 'drinks') {
        $hot  = trim($_POST['hotPriceCM'] ?? '');
        $cold = trim($_POST['coldPriceCM'] ?? '');
        if ($hot === '' && $cold === '') {
          $_SESSION['itemCreateMsg'] = "❌ At least one drink price is required.";
          header("Location: " . $_SERVER['PHP_SELF']);
          exit;
        }
        $hot  = $hot  !== '' && preg_match('/^\d+(\.\d{1,2})?$/', $hot)  ? $hot  : null;
        $cold = $cold !== '' && preg_match('/^\d+(\.\d{1,2})?$/', $cold) ? $cold : null;
        $drinkID = generateNextId($pdo, 'drinks', 'drinkID', 'B');
        $stmt = $pdo->prepare("INSERT INTO drinks (drinkID, Section, Name, hotPrice, coldPrice) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$drinkID, $section, $name, $hot, $cold]);
      }
      elseif ($itemTypeCM === 'addons') {
        $category = trim($_POST['itemCatCM'] ?? '');
        $price    = trim($_POST['priceCM'] ?? '');
        if ($price === '' || !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
          $_SESSION['itemCreateMsg'] = "❌ Invalid addon price.";
          header("Location: " . $_SERVER['PHP_SELF']);
          exit;
        }
        $addonID = generateNextId($pdo, 'addons', 'addonID', 'A');
        $stmt = $pdo->prepare("INSERT INTO addons (addonID, Category, Section, Name, Price)VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$addonID, $category, $section, $name, $price]);
      }
      $_SESSION['itemCreateMsg'] = "✅ Menu item created successfully!";
      header("Location: " . $_SERVER['PHP_SELF'] . "?itemType=$itemTypeCM");
      exit;
    }

    // delete item
    if (isset($_POST['itemIdToDel'])) {
      $id = $_POST['itemIdToDel'];
      $type = $_POST['itemType'];
      if ($type === "food")
        $stmt = $pdo->prepare("DELETE FROM food WHERE foodID = ?");
      elseif ($type === "drinks")
        $stmt = $pdo->prepare("DELETE FROM drinks WHERE drinkID = ?");
      elseif ($type === "addons")
        $stmt = $pdo->prepare("DELETE FROM addons WHERE addonID = ?");
      $stmt->execute([$id]);
      $_SESSION['itemUpdateMsg'] = "✅ Item deleted successfully!";
      header("Location: " . $_SERVER['PHP_SELF'] . "?itemType=" . $type);
      exit;
    }

    //update item price
    if (isset($_POST['updateItemsBtn'])) {
      $updated = false;
      if ($itemType === 'food' && isset($_POST['prices'])) {
        $updateFood = $pdo->prepare("UPDATE food SET Price = ? WHERE foodID = ?");
        foreach ($_POST['prices'] as $id => $price) {
          $price = trim($price);
          if ($price === '') continue;
          if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) continue;
          $updateFood->execute([$price, $id]);
          $updated = true;
        }
      }
      if ($itemType === 'addons' && isset($_POST['prices'])) {
        $updateAddon = $pdo->prepare("UPDATE addons SET Price = ? WHERE addonID = ?");
        foreach ($_POST['prices'] as $id => $price) {
          $price = trim($price);
          if ($price === '') continue;
          if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) continue;
          $updateAddon->execute([$price, $id]);
          $updated = true;
        }
      }
      if ($itemType === 'drinks') {
        $updateDrink = $pdo->prepare("UPDATE drinks SET hotPrice = ?, coldPrice = ? WHERE drinkID = ?");
        foreach ($_POST['pricesHot'] ?? [] as $id => $hotPrice) {
          $hotPrice  = trim($hotPrice);
          $coldPrice = trim($_POST['pricesCold'][$id] ?? '');
          if ($hotPrice === '' && $coldPrice === '') continue;
          if (($hotPrice !== '' && !preg_match('/^\d+(\.\d{1,2})?$/', $hotPrice)) ||($coldPrice !== '' && !preg_match('/^\d+(\.\d{1,2})?$/', $coldPrice))) 
            continue;
          $updateDrink->execute([$hotPrice === '' ? null : $hotPrice,$coldPrice === '' ? null : $coldPrice,$id]);
          $updated = true;
        }
      }
      if ($updated)
        $_SESSION['itemUpdateMsg'] = "✅ Prices updated successfully!";
      header("Location: " . $_SERVER['PHP_SELF'] . "?itemType=$itemType&itemCat=$itemCat");
      exit;
    }

    header("Location:" . $_SERVER['PHP_SELF']);
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === "GET"){
    // search runner
    if(isset($_GET["rnameToSearch"])){
      if(trim($_GET["rnameToSearch"]) !== "") {
        $fetchSearchedRunner = $pdo->prepare("SELECT ID, Name, Status FROM runners WHERE Name LIKE ?");
        $fetchSearchedRunner->execute(["%" . $_GET["rnameToSearch"] . "%"]);
        $searchedRunners = $fetchSearchedRunner->fetchAll(PDO::FETCH_ASSOC);
        if ($searchedRunners){
          $_SESSION['searched_runners'] = $searchedRunners;
          $runners = $searchedRunners;
        }
        else
          $searchError = "No runner found with searched name.";
      }else{
        unset($_SESSION['searched_runners']);
        $runners = [];
      } 
    } else if(isset($_GET["orderStatus"])){ //filter order list
      if($_GET["orderStatus"] === "All")
        $_SESSION['filteredOrders'] = $allOrders;
      else{
        $fetchOrdersByStatus = $pdo->prepare("SELECT ID, Type, Member_ID AS 'Member ID', Runner_ID AS 'Runner ID', Total_Amount AS 'Total (RM)', Status FROM orders WHERE Status = ? ORDER BY Order_Date");
        $fetchOrdersByStatus->execute([$_GET["orderStatus"]]);
        $_SESSION['filteredOrders'] = $fetchOrdersByStatus->fetchAll(PDO::FETCH_ASSOC);
      }
    } else if(isset($_GET["orderIdToView"])){ //selected order to view more details
      $fetchedMoreOrderDetails = [
        'Order' => [],
        'Items' => []
      ];
      $fetchMoreOrderDetail = $pdo->prepare("SELECT o.ID, o.Type, o.Member_ID AS 'Member ID', o.Runner_ID AS 'Runner ID', 
      o.Total_Amount AS 'Total (RM)', o.Status, o.Order_Date AS 'Order Date', o.Ready_Date AS 'Ready Date', 
      o.PickedUp_Date AS 'Pickup Date', o.Delivered_Date AS 'Delivered Date', o.Payment_Method AS 'Payment Method',
      oi.foodID AS 'Food ID', oi.drinkID AS 'Drink ID', oi.Type AS 'Item Type', oi.addonID AS 'Addon ID', oi.Quantity AS 'Quantity'
      FROM orders o LEFT JOIN order_items oi ON o.ID = oi.Order_ID WHERE o.ID = ?");
      $fetchMoreOrderDetail->execute([$_GET["orderIdToView"]]);
      $fetchedOrderRows = $fetchMoreOrderDetail->fetchAll(PDO::FETCH_ASSOC);
      if (!empty($fetchedOrderRows)){
        $fetchedMoreOrderDetails['Order'] = [
          'ID' => $fetchedOrderRows[0]['ID'],
          'Order Type' => $fetchedOrderRows[0]['Type'],
          'Member ID' => $fetchedOrderRows[0]['Member ID'],
          'Runner ID' => $fetchedOrderRows[0]['Runner ID'],
          'Total (RM)' => $fetchedOrderRows[0]['Total (RM)'],
          'Status' => $fetchedOrderRows[0]['Status'],
          'Order Date' => $fetchedOrderRows[0]['Order Date'],
          'Ready Date' => $fetchedOrderRows[0]['Ready Date'],
          'Pickup Date' => $fetchedOrderRows[0]['Pickup Date'],
          'Delivered Date' => $fetchedOrderRows[0]['Delivered Date'],
          'Payment Method' => $fetchedOrderRows[0]['Payment Method'],
        ];

        foreach ($fetchedOrderRows as $fetchedOrderRow) {
          if ($fetchedOrderRow['Food ID'] === null && $fetchedOrderRow['Drink ID'] === null && $fetchedOrderRow['Addon ID'] === null)
            continue;
          $fetchedMoreOrderDetails['Items'][] = [
            'Food ID' => $fetchedOrderRow['Food ID'],
            'Drink ID' => $fetchedOrderRow['Drink ID'],
            'Item Type' => $fetchedOrderRow['Item Type'],
            'Addon ID' => $fetchedOrderRow['Addon ID'],
            'Quantity' => $fetchedOrderRow['Quantity'],
          ];
        }
      $_SESSION['orderToView'] = $fetchedMoreOrderDetails;
      }
    }
    else if (!isset($_GET['orderStatus']) && !isset($_GET['orderIdToView']))
      unset($_SESSION['filteredOrders']);
  }

  $orders = $_SESSION['filteredOrders'] ?? $allOrders ?? null;
  $orderToView = $_SESSION['orderToView'] ?? null;
  $orderMsg = empty($orders) ? (empty($allOrders) ? "No orders have been made yet" : "No orders are in this stage") : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../../css/common.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/aAccount.css">
  <link rel="stylesheet" href="css/aChangePW.css">
  <link rel="stylesheet" href="css/aRestaurant.css">
</head>
<body>
  <nav id="menu-sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li class="nav-option"><div><p>🍽️ Restaurant </p></div></li>
      <li class="nav-option"><div><p>🧑🏻‍💼 Account Management</p></div></li>
      <li class="nav-option"><div><p>🔐 Change Password</p></div></li>
      <li class="nav-option" onclick="logout()"><div><p>🚪 Log Out</p></div></li>
    </ul>
  </nav>
  <div id="main-panel">
    <div class="main-panel-content" id="restaurant-panel"><?php include "aRestaurant.php" ?></div>
    <div class="main-panel-content" id="acc-panel"><?php include "aAccount.php"?></div>
    <div class="main-panel-content" id="pw-panel"><?php include "aChangePW.php"?></div>
  </div>
</body>

<script>
  const navOptions = document.querySelectorAll(".nav-option");
  const mainPanelContents = document.querySelectorAll(".main-panel-content");

  //load restaurant panel by default
  navOptions[0].classList.add("active");
  mainPanelContents[0].classList.add("active");

  // add active class to selected sidebar menu option & show corresponding panel
  for (let i = 0; i < mainPanelContents.length; i++){
    navOptions[i].addEventListener("click", function(){
      for(let j = 0; j < mainPanelContents.length; j++) {
        navOptions[j].classList.remove("active");
        mainPanelContents[j].classList.remove("active");
      }
      this.classList.add("active");
      mainPanelContents[i].classList.add("active");
      localStorage.setItem("activeAdminPanel", i);
    })
  }

  // on page reload
  let savedIndex = localStorage.getItem("activeAdminPanel");
  let defaultIndex = savedIndex !== null ? parseInt(savedIndex) : 0;
  for(let i = 0; i < mainPanelContents.length; i++){
    navOptions[i].classList.remove("active");
    mainPanelContents[i].classList.remove("active"); 
  }
  navOptions[defaultIndex].classList.add("active");
  mainPanelContents[defaultIndex].classList.add("active");

  //logout
  function logout(){
    if (confirm("Log out of admin panel?")) {
      localStorage.removeItem("activeAdminPanel");
      window.location.href = "../logout.php?role=admin";
    }
  }
</script>
</html>