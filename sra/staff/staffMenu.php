<?php
include '../../config.php';
session_start();

// set up menu structure array
$menuStructure = [];

// get Food, Addons, Drinks data
// food
$stmt = $pdo->prepare("SELECT * FROM food ORDER BY Category, Section, CAST(SUBSTRING(foodID,2) AS UNSIGNED)");
$stmt->execute();
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

// organize food into categories and sections
foreach ($foods as $row) {
    // 
    $cat = $row['Category'] ?: 'Uncategorized';
    $sec = $row['Section'];
    
    if (!isset($menuStructure[$cat])) {
        $menuStructure[$cat] = ['subcategories' => [], 'items' => []];
    }

    // section name != category name ==> subcategory
    if (!empty($sec) && $sec !== $cat) {
        $menuStructure[$cat]['subcategories'][$sec][] = $row;
    } else {
        $menuStructure[$cat]['items'][] = $row;
    }
}

// addons
$stmtAddon = $pdo->prepare("SELECT * FROM addons ORDER BY Category, Section, CAST(SUBSTRING(addonID,2) AS UNSIGNED)");
$stmtAddon->execute();
$addons = $stmtAddon->fetchAll(PDO::FETCH_ASSOC);

foreach ($addons as $row) {
    // addon will be grouped based on Category and Section like Food
    $cat = $row['Category'] ?: 'Add-Ons'; 
    $sec = $row['Section'];

    if (!isset($menuStructure[$cat])) {
        $menuStructure[$cat] = ['subcategories' => [], 'items' => []];
    }

    // addon grouping
    if (!empty($sec) && $sec !== $cat) {
        $menuStructure[$cat]['subcategories'][$sec][] = $row;
    } else {
        $menuStructure[$cat]['items'][] = $row;
    }
}

// drinks
$stmt = $pdo->prepare("SELECT * FROM drinks ORDER BY Section, CAST(SUBSTRING(drinkID,2) AS UNSIGNED)");
$stmt->execute();
$drinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
$drinkCat = "Drinks";

if (!isset($menuStructure[$drinkCat])) {
    $menuStructure[$drinkCat] = ['subcategories' => [], 'items' => []];
}

foreach ($drinks as $row) {
    $sec = $row['Section'];
    if (!empty($sec)) {
        $menuStructure[$drinkCat]['subcategories'][$sec][] = $row;
    } else {
        $menuStructure[$drinkCat]['items'][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff</title>
    <link rel="stylesheet" type="text/css" href="css/staffMenuStyle.css">
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
        <a href="#" class="active">MENU</a>
    </nav>
</header>

<div class="layout-wrapper">
      
    <aside class="sidebar">
        <h3>Categories</h3>
        <ul id="sidebarList">
        <?php 
            $index = 0;
            foreach ($menuStructure as $category => $data): 
                // generate anchor ID, give it a unique id
                $anchorId = 'cat-' . $index;
        ?>
        <li><a href="#<?= $anchorId ?>"><?= htmlspecialchars($category) ?></a></li>
        <?php 
                $index++; 
            endforeach; 
        ?>
        </ul>
    </aside>

    <!-- display menu items -->
    <main class="main-content" id="menuContainer">
        <?php 
            // generate each category section
            $index = 0;
            foreach ($menuStructure as $category => $data): 
            $anchorId = 'cat-' . $index;
            $isDrinksCategory = ($category === 'Drinks'); // special handling for Drinks bcoz of hot/cold prices
        ?>
        <div class="category-section" id="<?= $anchorId ?>">
            <!-- Category Section -->
            <div class="main-category-title"><?= htmlspecialchars($category) ?></div>

            <?php if (!empty($data['items'])): ?>
                <div class="menu-grid">
                    <?php foreach ($data['items'] as $item): 
                        renderItemCard($item, $isDrinksCategory); 
                        endforeach; 
                    ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['subcategories'])): ?>
                <?php foreach ($data['subcategories'] as $subName => $subItems): ?>
                    <div class="sub-category-title"><?= htmlspecialchars($subName) ?></div>
                    <div class="menu-grid">
                        <?php foreach ($subItems as $item): 
                            renderItemCard($item, $isDrinksCategory); 
                            endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php 
            $index++; 
            endforeach; 
        ?>
    </main>
</div>

<script>
    // update price or availability
    function updatePrice(id, type, val) {
        // create form data
        const formData = new FormData();
        formData.append('action', 'update_price');
        formData.append('id', id);
        formData.append('price_type', type);
        formData.append('value', val);
        
        // send POST request
        fetch('updateMenuItem.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if(data.trim() === 'success') {
                console.log('Price updated');
            } else {
                alert('Update failed: ' + data);
            }
        });
    }
    
    // toggle of setting availability
    function toggleAvailability(id, checkbox) {
        const isChecked = checkbox.checked ? 1 : 0;
        const card = document.getElementById('card-' + id);
        
        // create form data
        const formData = new FormData();
        formData.append('action', 'toggle_availability');
        formData.append('id', id);
        formData.append('value', isChecked);

        // send POST request
        fetch('updateMenuItem.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if(data.trim() === 'success') {
                if(isChecked) card.classList.remove('unavailable');
                else card.classList.add('unavailable');
            } else {
                checkbox.checked = !checkbox.checked;
                alert('Update failed: ' + data);
            }
        });
    }
</script>
</body>
</html>

<?php
function renderItemCard($item, $isDrinkCategory) {
    // determine item type and ID
    if (isset($item['foodID'])) {
        $id = $item['foodID'];
        $badgeClass = 'id-food';
        $isDrinkItem = false;
    } elseif (isset($item['addonID'])) {
        $id = $item['addonID'];
        $badgeClass = 'id-addon';
        $isDrinkItem = false;
    } elseif (isset($item['drinkID'])) {
        $id = $item['drinkID'];
        $badgeClass = 'id-drink';
        $isDrinkItem = true;
    } else {
        $id = 'UNKNOWN';
        $badgeClass = '';
        $isDrinkItem = false;
    }
            
    $name = htmlspecialchars($item['Name']);
    $avail = ($item['Availability'] === 'Available' || $item['Availability'] == 1); 
    $unavailableClass = $avail ? '' : 'unavailable';
    $checked = $avail ? 'checked' : '';
    
    // check if dual price (hot/cold) applies
    $isDualPrice = $isDrinkItem && isset($item['hotPrice']) && $item['hotPrice'] > 0;

    echo "<div class='menu-card {$unavailableClass}' id='card-{$id}'>";
    
    // Card Top
    echo "
        <div class='card-top'>
            <span class='item-id {$badgeClass}'>{$id}</span>
            <label class='toggle-switch'>
                <input type='checkbox' {$checked} onchange=\"toggleAvailability('{$id}', this)\">
                <span class='slider'></span>
            </label>
        </div>
        <div class='item-name'>{$name}</div>
    ";

    // Price Section
    if ($isDualPrice) { 
        // dual price drink
        $hot = number_format($item['hotPrice'], 2);
        $cold = number_format($item['coldPrice'], 2);
        echo "
        <div class='price-container'>
            <div class='price-row'>
                <span class='price-label'>HOT</span>
                <div class='input-wrapper'>
                    <span class='currency'>RM</span>
                    <input type='number' step='0.10' value='{$hot}' onchange=\"updatePrice('{$id}', 'hot', this.value)\">
                </div>
            </div>
            <div class='price-row'>
                <span class='price-label'>COLD</span>
                <div class='input-wrapper'>
                    <span class='currency'>RM</span>
                    <input type='number' step='0.10' value='{$cold}' onchange=\"updatePrice('{$id}', 'cold', this.value)\">
                </div>
            </div>
        </div>";
    } else {
        // single price item (food, addon, or single-price drink)
        $priceVal = $isDrinkItem ? ($item['coldPrice'] ?? 0) : ($item['Price'] ?? 0);
        $priceFmt = number_format((float)$priceVal, 2);
        echo "
        <div class='price-container'>
            <div class='price-row'>
                <span class='price-label'>Price</span>
                <div class='input-wrapper'>
                    <span class='currency'>RM</span>
                    <input type='number' step='0.10' value='{$priceFmt}' onchange=\"updatePrice('{$id}', 'single', this.value)\">
                </div>
            </div>
        </div>";
    }

    echo "</div>"; 
}
?>