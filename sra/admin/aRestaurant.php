<div id="restaurant-panel">
  <div id="restaurant-div">
    <div id="items-panel">
      <div class="title"><h2>Items</h2></div>
      <div id="items-panel-content">
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
          <div id="item-type-div">
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
          <div id="item-div">
            <div id="item-table-wrapper">
              <table id="item-table">
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
                      <td><?= htmlspecialchars($item['Type']) ?></td>
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
                          <td><?= htmlspecialchars($itemByCat['Type']) ?></td>
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
                            <td><input typet=text name="pricesHot[<?= $itemByCat['ID'] ?>]" value="<?= htmlspecialchars($itemByCat['Price (RM)[Hot]']) ?>"
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
      </div>
    </div>
    <div id="orders-panel">
      <div class="title"><h2>Orders</h2></div>
      <div id="order-panel-content">

      </div>
    </div>
    <div id="revenue-panel">
      <div class="title"><h2>Revenue</h2></div>
      <div id="revenue-panel-content">

      </div>
    </div>
  </div>
</div>

<script>
</script>