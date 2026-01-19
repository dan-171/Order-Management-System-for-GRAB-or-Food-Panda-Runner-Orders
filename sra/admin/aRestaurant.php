<div id="restaurant-panel">
  <div id="admin-panel-banner-div">
    <img id="admin-panel-banner" src="../../images/banner.webp" alt="banner.webp" loading = "lazy"/>
  </div>
  <div id="restaurant-div">
    <div id="items-panel">
      <div class="title"><h2>Items</h2></div>
        <div id="create-menu-panel">
          <h3>Create Menu</h3>
          <div id = "cm-forms">
            <div id="cm-form-img-div">
                <img id="cm-form-img" src="../../images/fnd.png" alt="food_n_drinks_img" loading="lazy"/>
            </div>
            <div id="cm-form-left">
              <form id="cm-form-1" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
                <div class="cm-field" id="cm-type-wrapper">
                  <label class="cm-field-label">Item Type:</label>
                  <select id="cm-select" name="itemTypeCM" onchange="this.form.submit()">
                    <option value="food" <?= $itemTypeCM === 'food' ? 'selected' : '' ?>>Food</option>
                    <option value="drinks" <?= $itemTypeCM === 'drinks' ? 'selected' : '' ?>>Drinks</option>
                    <option value="addons" <?= $itemTypeCM === 'addons' ? 'selected' : '' ?>>Addons</option>
                  </select>
                </div>
              </form>
              <form id="cm-form-2" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
                <input type="hidden" name="itemTypeCM" value="<?= htmlspecialchars($itemTypeCM) ?>">
                <div class="cm-field" id="cm-cat-wrapper">
                  <label class="cm-field-label">Category:</label>
                  <select id="cm-select-cat" name="itemCatCM">
                    <?php foreach ($catListCM as $cat): ?>
                      <option value = "<?= htmlspecialchars($cat) ?>" <?= $cat === $itemCat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="cm-field" id="cm-section-wrapper">
                  <label class="cm-field-label">Section:</label>
                  <select id="cm-select-section" name="itemSectionCM">
                    <?php foreach ($sectionListCM as $section): ?>
                      <option value = "<?= htmlspecialchars($section) ?>"><?= htmlspecialchars($section) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="cm-field" id="cm-ftype-wrapper">
                  <label class="cm-field-label" name="foodTypeCM">Type:</label>
                  <select id="cm-select-type">
                    <?php foreach ($foodType as $ft): ?>
                      <option value = "<?= htmlspecialchars($ft) ?>"><?= htmlspecialchars($ft) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div id="cm-form-right">
                <div class="cm-field" id="cm-name-wrapper">
                  <label class="cm-field-label">Name:</label>
                  <input type="text" id="cm-name" name="nameCM">
                </div>
                <div class="cm-field" id="cm-price-wrapper">
                  <label class="cm-field-label">Price (RM):</label>
                  <input type="text" id="cm-price" name="priceCM">
                </div>
                <div class="cm-field" id="cm-hot-wrapper">
                  <label class="cm-field-label">Hot Price (RM):</label>
                  <input type="text" id="cm-hot-price" name="hotPriceCM">
                </div>
                <div class="cm-field" id="cm-cold-wrapper">
                  <label class="cm-field-label">Cold Price (RM):</label>
                  <input type="text" id="cm-cold-price" name="coldPriceCM">
                </div>
                <input id="cm-btn" type="submit" value="Create" name="createItemBtn">
              </div>
            </form>
          </div>
          <p id="item-create-msg"><?= $itemCreateMsg ?></p>
        </div>
      <div id="items-panel-content">
        <h3>Menu</h3>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
          <div id="items-type-div">
            <label for="item-type">Type: </label>
            <select id="item-type" name="itemType" onchange="this.form.submit()">
              <option value="food" <?= $itemType === 'food' ? 'selected' : '' ?>>Food</option>
              <option value="drinks" <?= $itemType === 'drinks' ? 'selected' : '' ?>>Drinks</option>
              <option value="addons" <?= $itemType === 'addons' ? 'selected' : '' ?>>Addons</option>
            </select>
          </div>
          <?php if ($catList !== []): ?>
            <div id="item-cat-div">
              <label for="item-cat">Category: </label>
              <select id="item-cat" name="itemCat" onchange="this.form.submit()">
              <?php foreach ($catList as $cat): ?>
                <option value = "<?= htmlspecialchars($cat) ?>" <?= $cat === $itemCat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
              <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>
        </form>
        <form id="update-items" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
          <input type="hidden" name="itemType" value="<?= htmlspecialchars($itemType) ?>">
          <input type="hidden" name="itemCat" value="<?= htmlspecialchars($itemCat) ?>">
          <div id="items-div">
            <div id="items-table-wrapper">
              <table id="items-table">
                <thead>
                  <tr>
                    <?php foreach($itemKeys as $itemKey): ?>
                      <th><?= htmlspecialchars($itemKey) ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($itemCat === "All"):
                        foreach($items as $item): ?>
                  <tr>
                    <td><?= htmlspecialchars($item['ID']) ?></td>
                    <?php if ($itemType !== "drinks"): ?>
                      <td><?= htmlspecialchars($item['Category']) ?></td>
                    <?php endif; ?>
                    <td><?= htmlspecialchars($item['Section']) ?></td>
                    <?php if ($itemType === "food"): ?>
                      <td><?= htmlspecialchars($item['Type'] ?? '-') ?></td>
                    <?php endif;?>
                    <td><?= htmlspecialchars($item['Name']) ?></td>
                    <?php if ($itemType !== "drinks"): ?>
                      <?php if ($item['Price (RM)'] !== null): ?>
                        <td><input type=text name="prices[<?= $item['ID'] ?>]" value="<?= htmlspecialchars($item['Price (RM)']) ?>" 
                        inputmode="decimal" pattern="^\d+(\.\d{1,2})?$" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"/></td>
                      <?php else: ?>
                        <td></td>
                      <?php endif; ?>
                    <?php else: ?>
                      <?php if ($item['Price (RM)[Hot]'] !== null): ?>
                        <td><input type=text name="pricesHot[<?= $item['ID'] ?>]" value="<?= htmlspecialchars($item['Price (RM)[Hot]']) ?>" 
                        inputmode="decimal" pattern="^\d+(\.\d{1,2})?$" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"/></td>
                      <?php else: ?>
                        <td></td>
                      <?php endif; ?>
                      <?php if ($item['Price (RM)[Cold]'] !== null): ?>
                        <td><input type=text name="pricesCold[<?= $item['ID'] ?>]" value="<?= htmlspecialchars($item['Price (RM)[Cold]']) ?>"
                        inputmode="decimal" pattern="^\d+(\.\d{1,2})?$" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"/></td>
                      <?php else: ?>
                        <td></td>
                      <?php endif; ?>
                    <?php endif; ?>
                    <td>
                      <div class="opt-delete">
                        <input class="del-item" type="submit" form="delete-item-form" data-id="<?= $item['ID']?>" value="⛔ Delete"/>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; 
                    else: 
                      foreach($itemsByCat as $itemByCat):?>
                      <tr>
                        <td><?= htmlspecialchars($itemByCat['ID']) ?></td>
                        <?php if ($itemType !== "drinks"): ?>
                          <td><?= htmlspecialchars($itemByCat['Category']) ?></td>
                        <?php endif; ?>
                        <td><?= htmlspecialchars($itemByCat['Section']) ?></td>
                        <?php if ($itemType === "food"): ?>
                          <td><?= htmlspecialchars($itemByCat['Type'] ?? '-') ?></td>
                        <?php endif;?>
                        <td><?= htmlspecialchars($itemByCat['Name']) ?></td>
                        <?php if ($itemType !== "drinks"): ?>
                          <?php if ($itemByCat['Price (RM)'] !== null): ?>
                            <td><input type=text name="prices[<?= $itemByCat['ID'] ?>]" value="<?= htmlspecialchars($itemByCat['Price (RM)']) ?>" 
                            inputmode="decimal" pattern="^\d+(\.\d{1,2})?$" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"/></td>
                          <?php else: ?>
                          <td></td>
                          <?php endif; ?>
                        <?php else: ?>
                          <?php if ($itemByCat['Price (RM)[Hot]'] !== null): ?>
                            <td><input type=text name="pricesHot[<?= $itemByCat['ID'] ?>]" value="<?= htmlspecialchars($itemByCat['Price (RM)[Hot]']) ?>"
                            inputmode="decimal" pattern="^\d+(\.\d{1,2})?$" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"/></td>
                          <?php else: ?>
                          <td></td>
                          <?php endif; ?>
                          <?php if ($itemByCat['Price (RM)[Cold]'] !== null): ?>
                            <td><input type=text name="pricesCold[<?= $itemByCat['ID'] ?>]" value="<?= htmlspecialchars($itemByCat['Price (RM)[Cold]']) ?>" 
                            inputmode="decimal" pattern="^\d+(\.\d{1,2})?$" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"/></td>
                          <?php else: ?>
                          <td></td>
                          <?php endif; ?>
                        <?php endif; ?>
                        <td>
                          <div class="opt-delete">
                            <input class="del-item" type="submit" form="delete-item-form" data-id="<?= $itemByCat['ID']?>" value="⛔ Delete"/>
                          </div>
                        </td>
                      </tr>
                  <?php endforeach;
                  endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div id="update-items-btn-div">
            <p><?=  htmlspecialchars($itemUpdateMsg) ?></p>
            <input id="update-items-btn" type="submit" name="updateItemsBtn" value="Update">
          </div>
        </form>
        <form id="delete-item-form" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
          <input type="hidden" name="itemType" value="<?= htmlspecialchars($itemType) ?>">
          <input type="hidden" name="itemIdToDel" id="itemIdToDel">
        </form>
      </div>
    </div>
    <div id="orders-panel">
      <div class="title"><h2>Orders</h2></div>
      <div id="orders-panel-content">
        <form id="order-filter" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
          <div id="order-status-div">
            <label for="order-status">Status: </label>
            <select id="order-status" name="orderStatus" onchange="this.form.submit()">
              <option value="All" <?= $orderStatus === 'All' ? 'selected' : '' ?>>All</option>
              <option value="Order Placed" <?= $orderStatus === 'Order Placed' ? 'selected' : '' ?>>Order Placed</option>
              <option value="Readying Order" <?= $orderStatus === 'Readying Order' ? 'selected' : '' ?>>Readying</option>
              <option value="In Transit" <?= $orderStatus === 'In Transit' ? 'selected' : '' ?>>In Transit</option>
              <option value="Delivered" <?= $orderStatus === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
              <option value="Completed" <?= $orderStatus === 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>
          </div>
        </form>
        <?php if(!empty($orders)) :?>
          <div id="orders-div">
            <div id="orders-table-wrapper">
              <table id="orders-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Member ID</th>
                    <th>Runner ID</th>
                    <th>Total Amount (RM)</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($orders as $order): ?>
                  <tr>
                    <td><?= htmlspecialchars($order['ID']) ?></td>
                    <td><?= htmlspecialchars($order['Type']) ?></td>
                    <td><?= htmlspecialchars($order['Member ID']) ?></td>
                    <td><?= htmlspecialchars($order['Runner ID'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($order['Total (RM)']) ?></td>
                    <td><?= htmlspecialchars($order['Status']) ?></td>
                    <td id="orders-table-action">
                      <div class="opt-view">
                        <form action=<?=  htmlspecialchars($_SERVER["PHP_SELF"]) ?> method="GET">
                          <input type="hidden" name="orderIdToView" value="<?= htmlspecialchars($order['ID'])?>"/>
                          <input type="submit" value="📝 View More"/>
                        </form>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>
        <?php if(!empty($orderMsg)): ?>
          <div id="order-msg"><?= htmlspecialchars($orderMsg) ?></div>
        <?php endif; ?>
        <?php if ($orderToView): ?>
          <div id="order-details-wrapper">
            <h3><u>Order Details</u></h3>
            <div id="order-details">
              <div id="order-details-1">
                <p><strong>ID:</strong> <?= htmlspecialchars($orderToView['Order']["ID"]) ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($orderToView['Order']["Order Type"]) ?></p>
                <p><strong>Member ID:</strong> <?= htmlspecialchars($orderToView['Order']["Member ID"]) ?></p>
                <p><strong>Runner ID:</strong> <?= htmlspecialchars($orderToView['Order']["Runner ID"] ?? '-') ?></p>
                <p><strong>Total (RM):</strong> <?= htmlspecialchars($orderToView['Order']["Total (RM)"]) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($orderToView['Order']["Status"]) ?></p>
                <p><strong>Order Date:</strong> <?= htmlspecialchars($orderToView['Order']["Order Date"]) ?></p>
                <p><strong>Ready Date:</strong> <?= htmlspecialchars($orderToView['Order']["Ready Date"] ?? '-') ?></p>
                <p><strong>Pickup Date:</strong> <?= htmlspecialchars($orderToView['Order']["Pickup Date"] ?? '-') ?></p>
                <p><strong>Delivered Date:</strong> <?= htmlspecialchars($orderToView['Order']["Delivered Date"] ?? '-') ?></p>
                <p><strong>Payment Method:</strong> <?= htmlspecialchars($orderToView['Order']["Payment Method"]) ?></p>
              </div>
              <?php if (!empty($orderToView['Items'])): ?>
                <div id="order-details-2">
                  <p><strong>Ordered Items: </strong></p>
                  <div id="order-items-table-wrapper">
                    <table id="order-items-table">
                      <thead>
                        <tr>
                          <th>Item Type</th>
                          <th>Item ID</th>
                          <th>Addon ID</th>
                          <th>Quantity</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($orderToView['Items'] as $item): ?>
                          <tr>
                            <td><?= htmlspecialchars($item['Item Type']) ?></td>
                            <td><?= htmlspecialchars($item['Food ID'] ??$item['Drink ID'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['Addon ID'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['Quantity']) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              <?php endif; ?>
              <div id="hide-order-details">
                <button type="button" id="hide-order-details-btn">Hide</button>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // create new menu item
  const cmSelect = document.getElementById("cm-select");
  const cmSelectCat = document.getElementById("cm-select-cat");
  const cmSelectSection = document.getElementById("cm-select-section");
  const cmSelectFType = document.getElementById("cm-select-type");
  const wrappers = {
    cat: document.getElementById("cm-cat-wrapper"),
    section: document.getElementById("cm-section-wrapper"),
    ftype: document.getElementById("cm-ftype-wrapper"),
    name: document.getElementById("cm-name-wrapper"),
    price: document.getElementById("cm-price-wrapper"),
    hot: document.getElementById("cm-hot-wrapper"),
    cold: document.getElementById("cm-cold-wrapper"),
  };

  function hideAll() {
    Object.values(wrappers).forEach(w => w.style.display = "none");
  }

  function showFood() {
    wrappers.cat.style.display = "block";
    wrappers.section.style.display = "block";
    wrappers.ftype.style.display = "block";
    wrappers.name.style.display = "block";
    wrappers.price.style.display = "block";
  }

  function showDrinks() {
    wrappers.section.style.display = "block";
    wrappers.name.style.display = "block";
    wrappers.hot.style.display = "block";
    wrappers.cold.style.display = "block";
  }

  function showAddon() {
    wrappers.cat.style.display = "block";
    wrappers.section.style.display = "block";
    wrappers.name.style.display = "block";
    wrappers.price.style.display = "block";
  }

  function updateForm() {
    hideAll();
    switch (cmSelect.value) {
      case "food": showFood(); break;
      case "drinks": showDrinks(); break;
      case "addons": showAddon(); break;
    }
  }
  updateForm();
  cmSelect.addEventListener("change", updateForm);

  // allow section selection based on cat selection & type selection based on section selection
  function setAllowedOptions(selectElement, allowedValues = []) {
    let selected = false;

    for (const option of selectElement.options) {
      if (allowedValues.includes(option.value)) {
        option.disabled = false;
        if (!selected) {
          option.selected = true;
          selected = true;
        }
      } else {
        option.disabled = true;
        option.selected = false;
      }
    }
    if (!selected)
      selectElement.selectedIndex = -1;
  }

  function enableSection() {
    const cat = cmSelectCat.value;
    if(cmSelect.value != "drinks"){
      if (cat === "Signature")
        setAllowedOptions(cmSelectSection, ["Sup ZZ", "Mee Rebus ZZ", "Add On", "Add On Set"]);
      else if (cat === "Breakfast")
        setAllowedOptions(cmSelectSection, ["Masakan Panas", "Roti Bakar", "Add On (For Roti)"]);
      else if (cat === "Lunch")
        setAllowedOptions(cmSelectSection, ["Masakan Panas", "Set Nasi & Lauk"]);
      else if (cat === "Roti")
        setAllowedOptions(cmSelectSection, ["Roti Menu"]);
      else if (cat === "Ikan")
        setAllowedOptions(cmSelectSection, ["Ikan Siakap", "Bakar-Bakar"]);
      else if (cat === "Ala-Carte")
        setAllowedOptions(cmSelectSection, ["Sayur", "Aneka Lauk Thai", "Goreng Tepung", "Add On (For Aneka Lauk Thai)"]);
      else if (cat === "Ala-Carte 2")
        setAllowedOptions(cmSelectSection, ["Sup Ala Thai", "Tomyam", "Mee Kuah", "Add On (For Ala Thai & Tomyam)"]);
      else if (cat === "Western")
        setAllowedOptions(cmSelectSection, ["Fried & Grill", "Spaghetti", "Burger", "Sides", "Add On (For Burger)"]);
      else if (cat === "Goreng-goreng")
        setAllowedOptions(cmSelectSection, ["Nasi Goreng", "Mee Goreng"]);
      else
        setAllowedOptions(cmSelectSection, []);
    }
  }
  console.log(cmSelect.value);
  enableSection();
  cmSelect.addEventListener("change", enableSection);
  cmSelectCat.addEventListener("change", enableSection);

  // allow type selection based on section selection
  function enableType() {
    if (cmSelectSection.value === "Mee Kuah")
      setAllowedOptions(cmSelectFType, ["Mee", "Mee Hoon", "Kuey Teow"]);
    else if (cmSelectSection.value === "Nasi Goreng" || cmSelectSection.value === "Mee Goreng")
      setAllowedOptions(cmSelectFType, ["Original", "Daging", "Udang", "Sotong"]);
    else
      setAllowedOptions(cmSelectFType, []);
  }
  enableType();
  cmSelectSection.addEventListener("change", enableType);

  // validation before adding to menu
  let createItemMsg = document.getElementById("item-create-msg");
  document.getElementById("cm-btn").addEventListener("click", (e) => {
    const type = cmSelect.value;
    const name = document.getElementById("cm-name").value.trim();
    const price = document.getElementById("cm-price").value.trim();
    const hot = document.getElementById("cm-hot-price").value.trim();
    const cold = document.getElementById("cm-cold-price").value.trim();

    if (name === "") {
      e.preventDefault();
      createItemMsg.textContent = "❌ Item name is required."
      return;
    }

    if ((type === "food" || type === "addons") && (price === "" || !/^\d+(\.\d{1,2})?$/.test(price))) {
      e.preventDefault();
      createItemMsg.textContent = "❌ Invalid Price."
      return;
    }

    if (type === "drinks") {
      if (hot === "" && cold === "") {
        e.preventDefault();
        createItemMsg.textContent = "❌ At least one of hot or cold price must be filled.";
      }
      if ((hot !== "" && !/^\d+(\.\d{1,2})?$/.test(hot)) || (cold !== "" && !/^\d+(\.\d{1,2})?$/.test(cold))) {
        e.preventDefault();
        createItemMsg.textContent = "❌ Invalid Price.";
      }
      return;
    }
  });

  // delete menu item
  document.querySelectorAll(".del-item").forEach(del => {
    del.addEventListener("click", (e) => {
      document.getElementById("itemIdToDel").value = del.getAttribute("data-id");
    });
  });

  // hide order details
  const hideOrderBtn = document.getElementById('hide-order-details-btn');
  const orderDetailsWrapper = document.getElementById('order-details-wrapper');
  if (hideOrderBtn && orderDetailsWrapper) {
    hideOrderBtn.addEventListener('click', () => {
      orderDetailsWrapper.style.display = 'none';
    });
  }
});
</script>