<?php
include 'config.php';
session_start();

$isLoggedIn = isset($_SESSION['username']) ? true : false;

$category = $_GET["category"] ?? "Breakfast";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['temp_order']) && !empty($_SESSION['temp_order'])) {
    foreach ($_SESSION['temp_order'] as $id => $item) {
        if ($item['table'] === 'food') {
            $stmt = $pdo->prepare("SELECT Category FROM food WHERE foodID = ?");
            $stmt->execute([$id]);
            $foodCategory = $stmt->fetchColumn();
            
            if ($foodCategory === $category) {
                unset($_SESSION['temp_order'][$id]);
            }
        }
    }
}

$fetchFoods = $pdo->prepare("SELECT * FROM food WHERE category = ? ORDER BY CAST(SUBSTRING(foodID,2) AS UNSIGNED)");
$fetchFoods->execute(["$category"]);
$foods = $fetchFoods->fetchAll(PDO::FETCH_ASSOC); 

if ($category === "Ala-Carte 2"){
  foreach ($foods as $food) {
    if ($food["Section"] === "Mee Kuah"){
      $name = $food['Name'];
      $type = $food['Type'] ?? null;
      $meeKuah[$name][$type] = $food;
    }
  }
}
else if ($category === "Goreng-goreng"){
  foreach ($foods as $food) {
    if ($food["Section"] === "Nasi Goreng"){
      $name = $food['Name'];
      $type = $food['Type'] ?? null;
      $nasiGoreng[$name][$type] = $food;
    }
    else if ($food["Section"] === "Mee Goreng"){
      $name = $food['Name'];
      $type = $food['Type'] ?? null;
      $meeGoreng[$name][$type] = $food;
    }
  }
}

if ($category === "Drinks"){
  $fetchDrinks = $pdo->prepare("SELECT * FROM drinks ORDER BY CAST(SUBSTRING(drinkID,2) AS UNSIGNED)");
  $fetchDrinks->execute();
  $drinks = $fetchDrinks->fetchAll(PDO::FETCH_ASSOC); 
}

$menuImages = [
  "Signature" => "menuPics/Signature.jpeg",
  "Breakfast" => "menuPics/Breakfast.jpeg",
  "Lunch" => "menuPics/Lunch.jpeg",
  "Roti"  => "menuPics/Roti.jpeg",
  "Ikan"  => "menuPics/Ikan.jpeg",
  "Ala-Carte" => "menuPics/Ala-Carte.jpeg",
  "Ala-Carte 2" => "menuPics/Ala-Carte2.jpeg",
  "Western" => "menuPics/Western.jpeg",
  "Goreng-goreng" => "menuPics/Goreng-goreng.jpeg",
  "Drinks" => "menuPics/Drinks.jpeg"
];

$cap = [
  "Signature" => ["Sections" => ["Sup ZZ", "Mee Rebus ZZ"]],
  "Breakfast" => ["Sections" => ["Masakan Panas", "Roti Bakar"]],
  "Lunch" => ["Sections" => ["Set Nasi & Lauk", "Masakan Panas"]],
  "Roti" => ["Sections" => ["Roti Menu"]],
  "Ikan" => ["Sections" => ["Ikan Siakap", "Bakar-Bakar"]],
  "Ala-Carte" => ["Sections" => ["Sayur", "Aneka Lauk Thai", "Goreng Tepung"]],
  "Ala-Carte 2" => ["Sections" => ["Sup Ala Thai", "Tomyam", "Mee Kuah"]],
  "Western" => ["Sections" => ["Fried & Grill", "Spaghetti", "Burger", "Sides"]],
  "Goreng-goreng" => ["Sections" => ["Nasi Goreng", "Mee Goreng"],],
  "Drinks" => ["Sections" => ["Non-Coffee", "Jus", "Cold Dessert", "Coffee"],]
];

function prtQtt(array $item, string $table, string $category, bool $isTimeValid = true, string $type = null)
{
    if ($item["Availability"] === "Unavailable")
      return "<input type='text' value='Sold Out' disabled style='background-color:#ccc; color:red;'>";

    if ($category === "Roti" && !$isTimeValid)
      return "<input type='text' value='Closed' disabled style='background-color:#eee; color:#555; text-align:center;'>";

    if ($category === "Drinks") {
      $key = $item['drinkID'] . '_' . $type;
      return "<input type='number' name='item[$key]' value='0' min='0' max='10' class='qty-input' onfocus='checkLogin(this)'>
              <input type='hidden' name='table[$key]' value='drinks'>
              <input type='hidden' name='type[$key]' value='{$type}'>
              <input type='hidden' name='drinkID[$key]' value='{$item['drinkID']}'>";
    }else{
      $typeInput = '';
      if (!empty($item['Type']))
          $typeInput = "<input type='hidden' name='type[{$item['foodID']}]' value='{$item['Type']}'>";
      return "<input type='number' name='item[{$item['foodID']}]' value='0' min='0' max='10' class='qty-input' onfocus='checkLogin(this)'>
              <input type='hidden' name='table[{$item['foodID']}]' value='{$table}'>
              {$typeInput}";
    }
}

if (!isset($_SESSION['temp_order']))
    $_SESSION['temp_order'] = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['submit_step1']) || isset($_POST['submit_step2']))) {
  foreach ($_POST['item'] as $id => $qty) {
    if ($qty > 0) {
      if ($_POST['table'][$id] === "drinks"){
        $_SESSION['temp_order'][$id] = [
          'qty' => $qty,
          'table' => 'drinks',
          'type' => $_POST['type'][$id],
          'drinkID' => $_POST['drinkID'][$id]
        ];
      } else{
        $_SESSION['temp_order'][$id] = [
          'qty' => $qty,
          'table' => $_POST['table'][$id] ?? null,
          'type' => $_POST['type'][$id] ?? null,
        ];
      }
    }
  }
  
  $goToAddons = false;
  
  if ($category === "Signature") {
    $supZZSelected = false;
    
    $supZZFoodIDs = [];
    foreach ($foods as $food) {
      if ($food['Section'] === 'Sup ZZ') {
        $supZZFoodIDs[] = $food['foodID'];
      }
    }
    
    foreach ($_SESSION['temp_order'] as $id => $item) {
      if ($item['table'] === 'food' && in_array($id, $supZZFoodIDs)) {
        $supZZSelected = true;
        break;
      }
    }
    
    $goToAddons = $supZZSelected;
    
  } elseif ($category === "Breakfast") {
    $rotiBakarSelected = false;
    
    $rotiBakarFoodIDs = [];
    foreach ($foods as $food) {
      if ($food['Section'] === 'Roti Bakar') {
        $rotiBakarFoodIDs[] = $food['foodID'];
      }
    }
    
    foreach ($_SESSION['temp_order'] as $id => $item) {
      if ($item['table'] === 'food' && in_array($id, $rotiBakarFoodIDs)) {
        $rotiBakarSelected = true;
        break;
      }
    }
    
    $goToAddons = $rotiBakarSelected;
    
  } elseif ($category === "Ala-Carte") {
    $anekaLaukThaiSelected = false;
    
    $anekaLaukThaiFoodIDs = [];
    foreach ($foods as $food) {
      if ($food['Section'] === 'Aneka Lauk Thai') {
        $anekaLaukThaiFoodIDs[] = $food['foodID'];
      }
    }
    
    foreach ($_SESSION['temp_order'] as $id => $item) {
      if ($item['table'] === 'food' && in_array($id, $anekaLaukThaiFoodIDs)) {
        $anekaLaukThaiSelected = true;
        break;
      }
    }
    
    $goToAddons = $anekaLaukThaiSelected;
    
  } elseif ($category === "Ala-Carte 2") {
    $supOrTomyamSelected = false;
    
    $supOrTomyamFoodIDs = [];
    foreach ($foods as $food) {
      if ($food['Section'] === 'Sup Ala Thai' || $food['Section'] === 'Tomyam') {
        $supOrTomyamFoodIDs[] = $food['foodID'];
      }
    }
    
    foreach ($_SESSION['temp_order'] as $id => $item) {
      if ($item['table'] === 'food' && in_array($id, $supOrTomyamFoodIDs)) {
        $supOrTomyamSelected = true;
        break;
      }
    }
    
    $goToAddons = $supOrTomyamSelected;
    
  } elseif ($category === "Western") {
    $burgerSelected = false;
    
    $burgerFoodIDs = [];
    foreach ($foods as $food) {
      if ($food['Section'] === 'Burger') {
        $burgerFoodIDs[] = $food['foodID'];
      }
    }
    
    foreach ($_SESSION['temp_order'] as $id => $item) {
      if ($item['table'] === 'food' && in_array($id, $burgerFoodIDs)) {
        $burgerSelected = true;
        break;
      }
    }
    
    $goToAddons = $burgerSelected;
    
  } else {
    $goToAddons = false;
  }
  
  if ($goToAddons)
    header("Location: orderExtras.php?category=" . $category);
  else
    header("Location: review.php");
  exit();
}

// get current time to determine roti availability (Available: 07:00-11:00 & 18:00-23:00)
date_default_timezone_set("Asia/Kuala_Lumpur");
$currentHour = (int)date('G');
$isTimeValid = ($currentHour >= 7 && $currentHour < 11) || ($currentHour >= 18 && $currentHour < 23);
?>

<!DOCTYPE html>
<html>
<head>
  <title><?=  $category ?> Menu</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<script>
  function checkLogin(inputElement) {
    var userIsLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    if (!userIsLoggedIn) {
      inputElement.blur(); 
      alert("You must log in to make an order.");
      window.location.href = "login.php";
    }
  }

  function validateForm(event) {
    var userIsLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    if (!userIsLoggedIn) {
      alert("You must log in to make an order.");
      window.location.href = "login.php";
      return false;
    }

    let inputs = document.querySelectorAll('.qty-input');
    let total = 0;
    inputs.forEach(input => {
      if(input.value) total += parseInt(input.value);
    });

    if (total === 0) {
      alert("Please select at least ONE item before continuing.");
      return false; 
    }
    return true; 
  }
</script>

<h2><?=  $category ?> Menu</h2>
<img src= "<?= $menuImages[$category] ?>" alt = "<?=  $category ?> Menu"/>

<!-- roti exclusive -->
<?php if ($category === "Roti"): ?>
  <style>
    .time-alert {
        background-color: #ffdddd;
        color: #d8000c;
        border: 1px solid #d8000c;
        padding: 15px;
        margin: 20px auto;
        width: 80%;
        text-align: center;
        border-radius: 5px;
        font-weight: bold;
    }
    .time-success {
        background-color: #ddffdd;
        color: #4CAF50;
        border: 1px solid #4CAF50;
        padding: 10px;
        margin: 10px auto;
        width: 80%;
        text-align: center;
        border-radius: 5px;
        font-size: 14px;
    }
  </style>
  <?php if (!$isTimeValid): ?>
      <div class="time-alert">
          ⚠️ Roti Canai is currently UNAVAILABLE.<br>
          Available hours: 7:00 AM - 11:00 AM & 6:00 PM - 11:00 PM.
      </div>
  <?php else: ?>
      <div class="time-success">
          ✅ Currently Serving (Available: 7am-11am & 6pm-11pm)
      </div>
  <?php endif; ?>
<?php endif; ?>

<!-- order form -->
<form method="POST" action="order.php?category=<?= $category ?>" onsubmit="return validateForm()">
<div>
  <table class="bigTable" border="0">
  <?php 
    $sections = $cap[$category]['Sections'] ?? [];
    for ($i = 0; $i < count($sections); $i++):
      $section = $sections[$i];
  ?>
    <tr>
      <td>
        <h3><?=  htmlspecialchars($section) ?></h3>
        <?php if($category === "Drinks"): ?>
          <?php if($section === "Cold Dessert"):?>
            <table class="menuTable">
          <?php else: ?>
            <table class="table-5col">
          <?php endif; ?>
        <?php else: ?>
          <?php if($section === "Mee Kuah"):?>
            <table class="table-5col">
          <?php elseif($section === "Nasi Goreng" || $section === "Mee Goreng"):?>
            <table class="table-9col">
          <?php else: ?>
            <table class="menuTable">
          <?php endif; ?>
        <?php endif; ?>
          <!-- header row -->
          <?php if($category === "Drinks"): ?>
            <?php if($section === "Cold Dessert"): ?>
              <tr>
                <th>Items</th>
                <th>Price</th>
                <th>Quantity</th>
              </tr>
            <?php else: ?>
              <tr>
                <th>Items</th>
                <th>Hot</th>
                <th>Quantity</th>
                <th>Cold</th>
                <th>Quantity</th>
              </tr>
            <?php endif; ?>
          <?php else: ?>
            <?php if($section === "Mee Kuah"): ?>
              <tr>
                <th>Items</th>
                <th>Price</th>
                <th>Mee</th>
                <th>Mee Hoon</th>
                <th>Kuey Teow</th>
              </tr>
            <?php elseif($section === "Nasi Goreng" || $section === "Mee Goreng"): ?>
              <tr>
                <th>Items</th>
                <th>Original</th>
                <th>Quantity</th>
                <th>Daging</th>
                <th>Quantity</th>
                <th>Udang</th>
                <th>Quantity</th>
                <th>Sotong</th>
                <th>Quantity</th>
              </tr>
            <?php else: ?>
              <tr>
                <th>Items</th>
                <th>Price</th>
                <th>Quantity</th>
              </tr>
            <?php endif; ?>
          <?php endif; ?>
          <!-- data rows -->
          <?php if ($category === "Drinks"): ?>
            <?php if ($section === "Cold Dessert"): ?>
              <?php foreach ($drinks as $drink): ?>
                <?php if ($drink['Section'] === $section): ?>
                  <tr>
                    <td><?= htmlspecialchars($drink['Name']) ?></td>
                    <td>RM <?= number_format($drink['coldPrice'], 2) ?></td>
                    <td><?= prtQtt($drink, "drinks", $category, $isTimeValid, "") ?></td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <?php foreach ($drinks as $drink): ?>
                <?php if ($drink['Section'] === $section): ?>
                  <tr>
                    <td><?= htmlspecialchars($drink['Name']) ?></td>
                    <?php if ($drink['hotPrice'] === null): ?>
                      <td> - </td>
                      <td> - </td>
                    <?php else: ?>
                      <td>RM <?= number_format($drink['hotPrice'], 2) ?></td>
                      <td><?= prtQtt($drink, "drinks", $category, $isTimeValid, "Hot") ?></td>
                    <?php endif; ?>
                    <?php if ($drink['coldPrice'] === null): ?>
                      <td> - </td>
                      <td> - </td>
                    <?php else: ?>
                      <td>RM <?= number_format($drink['coldPrice'], 2) ?></td>
                      <td><?= prtQtt($drink, "drinks", $category, $isTimeValid, "Cold") ?></td>
                    <?php endif; ?>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          <?php else: ?>
            <?php if ($category === "Ala-Carte 2" && $section === "Mee Kuah"): ?>
              <?php foreach ($meeKuah as $name => $types): ?>
                <tr>
                  <td><?= htmlspecialchars($name) ?></td>
                  <td>RM <?= number_format(reset($types)['Price'], 2) ?></td>
                  <td><?= isset($types['Mee']) ? prtQtt($types['Mee'], "food", $category, $isTimeValid) : '-' ?></td>
                  <td><?= isset($types['Mee Hoon']) ? prtQtt($types['Mee Hoon'], "food", $category, $isTimeValid) : '-' ?></td>
                  <td><?= isset($types['Kuey Teow']) ? prtQtt($types['Kuey Teow'], "food", $category, $isTimeValid) : '-' ?></td>
                </tr>
              <?php endforeach; ?>
            <?php elseif ($category === "Goreng-goreng" && $section === "Nasi Goreng"): ?>
              <?php foreach ($nasiGoreng as $name => $types): ?>
                <tr>
                  <td><?= htmlspecialchars($name) ?></td>
                  <td>RM <?= isset($types['Original']) ? number_format($types['Original']['Price'], 2) : '-' ?></td>
                  <td><?= isset($types['Original']) ? prtQtt($types['Original'], "food", $category, $isTimeValid) : '-' ?></td>
                  <td>RM <?= isset($types['Daging']) ? number_format($types['Daging']['Price'], 2) : '-' ?></td>
                  <td><?= isset($types['Daging']) ? prtQtt($types['Daging'], "food", $category, $isTimeValid) : '-' ?></td>
                  <td>RM <?= isset($types['Udang']) ? number_format($types['Udang']['Price'], 2) : '-' ?></td>
                  <td><?= isset($types['Udang']) ? prtQtt($types['Udang'], "food", $category, $isTimeValid) : '-' ?></td>
                  <td>RM <?= isset($types['Sotong']) ? number_format($types['Sotong']['Price'], 2) : '-' ?></td>
                  <td><?= isset($types['Sotong']) ? prtQtt($types['Sotong'], "food", $category, $isTimeValid) : '-' ?></td>
                </tr>
              <?php endforeach; ?>
            <?php elseif ($category === "Goreng-goreng" && $section === "Mee Goreng"): ?>
              <?php foreach ($meeGoreng as $name => $types): ?>
                <tr>
                  <td><?= htmlspecialchars($name) ?></td>
                  <td>RM <?= isset($types['Original']) ? number_format($types['Original']['Price'], 2) : '-' ?></td>
                  <td><?= isset($types['Original']) ? prtQtt($types['Original'], "food", $category, $isTimeValid) : '-' ?></td>
                  <td>RM <?= isset($types['Daging']) ? number_format($types['Daging']['Price'], 2) : '-' ?></td>
                  <td><?= isset($types['Daging']) ? prtQtt($types['Daging'], "food", $category, $isTimeValid) : '-' ?></td>
                  <td>RM <?= isset($types['Udang']) ? number_format($types['Udang']['Price'], 2) : '-' ?></td>
                  <td><?= isset($types['Udang']) ? prtQtt($types['Udang'], "food", $category, $isTimeValid) : '-' ?></td>
                  <td>RM <?= isset($types['Sotong']) ? number_format($types['Sotong']['Price'], 2) : '-' ?></td>
                  <td><?= isset($types['Sotong']) ? prtQtt($types['Sotong'], "food", $category, $isTimeValid) : '-' ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <?php foreach ($foods as $food): ?>
                <?php if ($food['Section'] === $section): ?>
                  <tr>
                    <td><?= htmlspecialchars($food['Name']) ?></td>
                    <td>RM <?= number_format($food['Price'], 2) ?></td>
                    <td><?= prtQtt($food, "food", $category, $isTimeValid) ?></td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif;  ?>
          <?php endif; ?>
        </table>
      </td>
    </tr>
  <?php endfor; ?>
  </table>
  <div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back</a>
    <?php if ($category === "Ala-Carte" || $category === "Ala-Carte 2" || $category === "Breakfast" || $category === "Signature" || $category === "Western"): ?>
      <input type="submit" name="submit_step1" value="Next »" class="navButton">
    <?php else: ?>
      <input type="submit" name="submit_step2" value="Next »" class="navButton">
    <?php endif; ?>
  </div>
</form>
</body>
</html>