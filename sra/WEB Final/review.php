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

// cancel order
if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
  unset($_SESSION['temp_order']);
  header("Location: customerpage.php");
  exit();
}

// order submission
if (!isset($_SESSION['temp_order']))
    $_SESSION['temp_order'] = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_step2'])) {
  if (!isset($_POST['item'])) {
    header("Location: customerpage.php");
    exit();
  }
  foreach ($_POST['item'] as $id => $qty) {
    if ($qty > 0) {
      $_SESSION['temp_order'][$id] = array_merge(
        $_SESSION['temp_order'][$id] ?? [],
        [
          'qty' => $qty,
          'table' => $_POST['table'][$id] ?? 'addons',
          'type' => $_POST['type'][$id] ?? null
        ]
      );
    }
  }
}

$total = 0;
$display_rows = "";
$cartItems = isset($_SESSION['temp_order']) ? $_SESSION['temp_order'] : [];

foreach ($cartItems as $id => $item) {
  if (!isset($item['table']) || empty($item['qty']) || $item['qty'] <= 0) continue;
  $price = 0;
  $subtotal = 0;
  $name = "";

  if (!isset($item['table'])) continue; 
  if ($item["table"] === "drinks" && !empty($item['drinkID'])){
    $drinkID = $item['drinkID'];
    if (!isset($drinksBy_drinkID[$drinkID])) continue;
    $drink = $drinksBy_drinkID[$drinkID];
    $name = $drink['Name'];
    if ($item["type"] === "Hot")
      $price = $drink["hotPrice"];
    else
      $price = $drink["coldPrice"];
  }
  else if ($item["table"] === "food" && isset($foodsBy_foodID[$id])){
    $name = $foodsBy_foodID[$id]["Name"];
    $price = $foodsBy_foodID[$id]['Price'];
  }
  else if ($item["table"] === "addons" && isset($addonsBy_addonID[$id])){
    $name = $addonsBy_addonID[$id]["Name"];
    $price =  $addonsBy_addonID[$id]['Price'];
  }
  $subtotal =  $price * $item["qty"];
  $_SESSION['temp_order'][$id]['subtotal'] = $subtotal;
  $total += $subtotal;
  
  $displayName = $name;
  if (!empty($item['type']))
    $displayName .= ' (' . $item['type'] . ')';
  $display_rows .= "<tr>
                      <td>{$displayName}</td>
                      <td>{$item['qty']}</td>
                      <td>{$price}</td>
                      <td>RM " . number_format($subtotal, 2) . "</td>
                      <td><a href='editSessionItem.php?id=$id' class='navButton' style='padding:5px 10px; font-size:12px;'>Edit</a></td>
                    </tr>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Summary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Order Summary</h2>
<div id="result">
  <table class='table-5col'>
    <tr>
      <th>Item</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Subtotal</th>
      <th>Action</th>
    </tr>  
    <?= $display_rows; ?>    
    <tr>
      <th colspan="1">Total</th>
      <th colspan="4">RM <?php echo number_format($total, 2); ?></th>
    </tr>
  </table>
</div>

<div class="buttonContainer">
  <a href="review.php?action=cancel" class="navButton" style="background-color: #555;">Cancel</a>
  <form action="addToCart.php" method="post" style="margin-bottom: 0 !important">
    <button type="submit" class="navButton">Add to Cart</button>
  </form>
</div>

</body>
</html>