<div id="restaurant-panel">
  <div id="admin-panel-banner-div">
    <img id="admin-panel-banner" src="../../images/banner.webp" alt="banner.webp" loading = "lazy"/>
  </div>
  <div id="restaurant-div">
    <div id="items-panel">
      <div class="title"><h2>Items</h2></div>
      <div id="items-panel-content">
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
  // hide order details
  document.addEventListener('DOMContentLoaded', () => {
    const hideOrderBtn = document.getElementById('hide-order-details-btn');
    const orderDetailsWrapper = document.getElementById('order-details-wrapper');
    if (hideOrderBtn && orderDetailsWrapper) {
      hideOrderBtn.addEventListener('click', () => {
        orderDetailsWrapper.style.display = 'none';
      });
    }
  });
</script>