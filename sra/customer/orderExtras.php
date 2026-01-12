<?php
include '../../config.php';
session_start();

$isLoggedIn = isset($_SESSION['username']) ? true : false;

$category = $_GET["category"] ?? "Breakfast";

// fetch addon
$fetchAddons = $pdo->prepare("SELECT * FROM addons WHERE category = ? ORDER BY CAST(SUBSTRING(addonID,2) AS UNSIGNED)");
$fetchAddons->execute([$category]);
$addons = $fetchAddons->fetchAll(PDO::FETCH_ASSOC); 

// fetch menu img for each category
$menuImages = [
  "Signature" => "menuPics/Signature.jpeg",
  "Breakfast" => "menuPics/Breakfast.jpeg",
  "Ala-Carte" => "menuPics/Ala-Carte.jpeg",
  "Ala-Carte 2" => "menuPics/Ala-Carte2.jpeg",
  "Western" => "menuPics/Western.jpeg",
];
// table captions for each category
$cap = [
  "Signature" => ["Extra" => ["Add On", "Add On Set"]],
  "Breakfast" => ["Extra" => ["Add On (For Roti)"]],
  "Ala-Carte" => ["Extra" => ["Add On (For Aneka Lauk Thai)"]],
  "Ala-Carte 2" => ["Extra" => ["Add On (For Ala Thai & Tomyam)"]],
  "Western" => ["Extra" => ["Add On (For Burger)"]]
];

// on submission
if (!isset($_SESSION['temp_order']))
    $_SESSION['temp_order'] = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_step2'])) {
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
  header("Location: review.php");
  exit();
}

function prtQtt(array $item, string $category){
  if ($item["Availability"] === "Unavailable")
    return "<input type='text' value='Sold Out' disabled style='background-color:#ccc; color:red;'>";
  return "<input type='number' name='item[{$item['addonID']}]' value='0' min='0' max='10' class='qty-input' onfocus='checkLogin(this)'>
          <input type='hidden' name='table[{$item['addonID']}]' value='addons'>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title><?=  $category ?> Add-On Menu</title>
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
    return true;
  }
</script>

<h2><?=  $category ?> Add-On Menu</h2>
<img src= "<?= $menuImages[$category] ?>" alt = "<?=  $category ?> Menu"/>

<!-- order form -->
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="return validateForm()">
<div>
  <table class="bigTable" border="0">
  <?php 
    $sections = $cap[$category]['Extra'] ?? [];
    for ($i = 0; $i < count($sections); $i++):
      $section = $sections[$i];
  ?>
    <tr>
      <td>
        <h3><?=  htmlspecialchars($section) ?></h3>
          <table class="menuTable">
          <!-- header row -->
          <tr>
            <th>Items</th>
            <th>Price</th>
            <th>Quantity</th>
          </tr>
          <!-- data rows -->
          <?php foreach ($addons as $addon): ?>
            <?php if ($addon['Section'] === $section): ?>
              <tr>
                <td><?= htmlspecialchars($addon['Name']) ?></td>
                <td>RM <?= number_format($addon['Price'], 2) ?></td>
                <td><?= prtQtt($addon, $category) ?></td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        </table>
      </td>
    </tr>
  <?php endfor; ?>
  </table>
  <div class="buttonContainer">
    <a href="order.php?category=<?= $category ?>" class="navButton">« Back</a>
    <input type="submit" name="submit_step2" value="Next »" class="navButton">
  </div>
</form>
</body>
</html>