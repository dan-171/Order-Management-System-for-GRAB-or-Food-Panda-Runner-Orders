<?php
// staffMenu.php
include '../../config.php'; // 确保路径正确
session_start();

// 1. 初始化数组结构
$menuStructure = [];

// 2. 获取 Food 数据
// 假设表结构: foodID, Name, Price, Availability, Category, Section
$foodStmt = $pdo->prepare("SELECT * FROM food ORDER BY Category, Section, CAST(SUBSTRING(foodID,2) AS UNSIGNED)");
$foodStmt->execute();
$foods = $foodStmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($foods as $row) {
    $cat = $row['Category']; // e.g., "Sarapan"
    $sec = $row['Section'];  // e.g., "Masakan Panas"
    
    // 初始化 Category
    if (!isset($menuStructure[$cat])) {
        $menuStructure[$cat] = ['category' => $cat, 'subcategories' => [], 'items' => []];
    }

    // 构建 Item 对象
    $item = [
        'id' => $row['foodID'],
        'name' => $row['Name'],
        'price' => (float)$row['Price'],
        'available' => ($row['Availability'] === 'Available'), // 转换数据库字符串为 Boolean
        'type' => 'food'
    ];

    // 分类逻辑：如果 Section 和 Category 名字相似，或者是特定单类目，直接放 items，否则放 subcategories
    // 这里我们做一个通用逻辑：如果有 Section 且 Section != Category，放入 subcategory
    if (!empty($sec) && $sec !== $cat) {
        if (!isset($menuStructure[$cat]['subcategories'][$sec])) {
            $menuStructure[$cat]['subcategories'][$sec] = ['name' => $sec, 'items' => []];
        }
        $menuStructure[$cat]['subcategories'][$sec]['items'][] = $item;
    } else {
        $menuStructure[$cat]['items'][] = $item;
    }
}

// 3. 获取 Drinks 数据
// 假设表结构: drinkID, Name, hotPrice, coldPrice, Availability, Section (通常Drinks都在Drinks分类下)
$drinkStmt = $pdo->prepare("SELECT * FROM drinks ORDER BY Section, CAST(SUBSTRING(drinkID,2) AS UNSIGNED)");
$drinkStmt->execute();
$drinks = $drinkStmt->fetchAll(PDO::FETCH_ASSOC);

// 如果你希望 Drinks 单独作为一个大类
$drinkCat = "Drinks";
if (!isset($menuStructure[$drinkCat])) {
    $menuStructure[$drinkCat] = ['category' => $drinkCat, 'subcategories' => [], 'items' => []];
}

foreach ($drinks as $row) {
    $sec = $row['Section']; // e.g., "Coffee", "Juice"
    
    $item = [
        'id' => $row['drinkID'],
        'name' => $row['Name'],
        'priceHot' => (float)$row['hotPrice'],
        'priceCold' => (float)$row['coldPrice'],
        'available' => ($row['Availability'] === 'Available'),
        'type' => 'drink_dual' // 饮料通常有冷热价
    ];

    // 对于简单的饮料（如 Cendol），只有单一价格，可能需要根据数据判断
    // 假设如果 hotPrice 为 null 或 0，则视为普通 food 类型显示 (例如 Cold Dessert)
    if ($row['hotPrice'] == 0 || $row['hotPrice'] == null) {
        $item['type'] = 'food'; // 复用 food 的单价显示模板
        $item['price'] = (float)$row['coldPrice']; // 使用 coldPrice 作为主价格
    }

    if (!empty($sec)) {
        if (!isset($menuStructure[$drinkCat]['subcategories'][$sec])) {
            $menuStructure[$drinkCat]['subcategories'][$sec] = ['name' => $sec, 'items' => []];
        }
        $menuStructure[$drinkCat]['subcategories'][$sec]['items'][] = $item;
    } else {
        $menuStructure[$drinkCat]['items'][] = $item;
    }
}

// 4. 清理数组索引 (PHP关联数组转JSON对象，JS需要数组格式)
$finalMenuData = [];
foreach ($menuStructure as $catData) {
    // 将 subcategories 关联数组转为索引数组
    if (!empty($catData['subcategories'])) {
        $catData['subcategories'] = array_values($catData['subcategories']);
    } else {
        unset($catData['subcategories']); // 如果空，移除该键
    }
    
    if (empty($catData['items'])) {
        unset($catData['items']);
    }

    $finalMenuData[] = $catData;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Staff Menu Management</title>
  <link rel="stylesheet" type="text/css" href="css/staffMenuStyle.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>

  <header>
    <div class="titleWithLogo">
        <h1>SUP TULANG ZZ (STAFF)</h1>
    </div>
    <nav>
      <a href="staffMain.php">DASHBOARD</a>
      <a href="#" class="active">MENU MANAGEMENT</a>
    </nav>
  </header>

  <div class="layout-wrapper">
      <aside class="sidebar">
          <h3>Categories</h3>
          <ul id="sidebarList"></ul>
      </aside>

      <main class="main-content" id="menuContainer">
          </main>
  </div>

<script>
    // 关键点：将 PHP 生成的数据注入到 JS 变量
    const menuData = <?php echo json_encode($finalMenuData); ?>;

    // --- 这里放入原来的 renderMenu 和 createItemCard 函数 (无需修改) ---
    
    function renderMenu() {
        const container = document.getElementById('menuContainer');
        const sidebar = document.getElementById('sidebarList');
        
        container.innerHTML = '';
        sidebar.innerHTML = '';

        menuData.forEach((section, index) => {
            const anchorId = 'cat-' + index;
            const li = document.createElement('li');
            li.innerHTML = `<a href="#${anchorId}">${section.category}</a>`;
            sidebar.appendChild(li);

            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'category-section';
            sectionDiv.id = anchorId; 
            sectionDiv.innerHTML = `<div class="main-category-title">${section.category}</div>`;

            if (section.subcategories) {
                section.subcategories.forEach(sub => {
                    const subTitle = document.createElement('div');
                    subTitle.className = 'sub-category-title';
                    subTitle.innerText = sub.name;
                    sectionDiv.appendChild(subTitle);

                    const grid = document.createElement('div');
                    grid.className = 'menu-grid';
                    sub.items.forEach(item => {
                        grid.appendChild(createItemCard(item));
                    });
                    sectionDiv.appendChild(grid);
                });
            } else {
                const grid = document.createElement('div');
                grid.className = 'menu-grid';
                if (section.items) {
                    section.items.forEach(item => {
                        grid.appendChild(createItemCard(item));
                    });
                }
                sectionDiv.appendChild(grid);
            }
            container.appendChild(sectionDiv);
        });
    }

    function createItemCard(item) {
        const card = document.createElement('div');
        card.className = `menu-card ${item.available ? '' : 'unavailable'}`;
        card.id = `card-${item.id}`;

        let priceHtml = '';
        const badgeClass = item.id.startsWith('B') ? 'id-drink' : 'id-food';

        if (item.type === 'drink_dual') {
            priceHtml = `
                <div class="price-container">
                    <div class="price-row">
                        <span class="price-label">HOT</span>
                        <div class="input-wrapper">
                            <span class="currency">RM</span>
                            <input type="number" step="0.10" value="${item.priceHot.toFixed(2)}" 
                                onchange="updatePrice('${item.id}', 'hot', this.value)">
                        </div>
                    </div>
                    <div class="price-row">
                        <span class="price-label">COLD</span>
                        <div class="input-wrapper">
                            <span class="currency">RM</span>
                            <input type="number" step="0.10" value="${item.priceCold.toFixed(2)}" 
                                onchange="updatePrice('${item.id}', 'cold', this.value)">
                        </div>
                    </div>
                </div>`;
        } else {
            priceHtml = `
                <div class="price-container">
                    <div class="price-row">
                        <span class="price-label">Price</span>
                        <div class="input-wrapper">
                            <span class="currency">RM</span>
                            <input type="number" step="0.10" value="${item.price.toFixed(2)}" 
                                onchange="updatePrice('${item.id}', 'single', this.value)">
                        </div>
                    </div>
                </div>`;
        }

        card.innerHTML = `
            <div class="card-top">
                <span class="item-id ${badgeClass}">${item.id}</span>
                <label class="toggle-switch">
                    <input type="checkbox" ${item.available ? 'checked' : ''} onchange="toggleAvailability('${item.id}', this)">
                    <span class="slider"></span>
                </label>
            </div>
            <div class="item-name">${item.name}</div>
            ${priceHtml}
        `;
        return card;
    }

    // --- 修改后的 ACTION 函数 (连接 PHP 数据库) ---

    function updatePrice(id, type, val) {
        const value = parseFloat(val);
        
        // 1. 发送 Fetch 请求
        const formData = new FormData();
        formData.append('action', 'update_price');
        formData.append('id', id);
        formData.append('price_type', type); // 'hot', 'cold', 'single'
        formData.append('value', value);

        fetch('updateMenuItem.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            if(result.trim() === 'success') {
                // 可选：添加视觉反馈，比如边框变绿一下
                console.log(`Updated ${id}: ${type} -> ${value}`);
            } else {
                alert("Error updating price: " + result);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function toggleAvailability(id, checkboxElem) {
        const isChecked = checkboxElem.checked;
        const card = document.getElementById(`card-${id}`);

        // 1. 发送 Fetch 请求
        const formData = new FormData();
        formData.append('action', 'toggle_availability');
        formData.append('id', id);
        formData.append('value', isChecked ? 1 : 0);

        fetch('updateMenuItem.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            if(result.trim() === 'success') {
                // 2. 更新 UI
                if (isChecked) {
                    card.classList.remove('unavailable');
                } else {
                    card.classList.add('unavailable');
                }
                console.log(`Toggled ${id}: ${isChecked}`);
            } else {
                // 如果失败，把 checkbox 变回去
                checkboxElem.checked = !isChecked; 
                alert("Error updating status: " + result);
            }
        })
        .catch(error => {
            checkboxElem.checked = !isChecked;
            console.error('Error:', error);
        });
    }

    // 初始化渲染
    renderMenu();

</script>
</body>
</html>