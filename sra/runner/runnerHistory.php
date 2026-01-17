<?php include 'runnerHead.php'?>
<script>
    function renderOrderList() {
        const listContainer = document.getElementById('orderList');
        const searchVal = document.getElementById('searchInput').value.toLowerCase();
        listContainer.innerHTML = '';
        let visibleCount = 0;

        orders.forEach(order => {
            // Search filter
            if (searchVal && !order.orderId .toString().includes(searchVal) && !order.customerName.toLowerCase().includes(searchVal))
              return;

            // Display logic
            if (order.Status === "Delivered" || order.Status === "Completed") {
                const statusClass = order.Status === 'Completed' ? 'st-completed' : 'st-delivered';
                const div = document.createElement('div');
                div.className = `order-card ${selectedOrderId === order.orderId ? 'selected' : ''}`;
                div.onclick = () => { selectedOrderId = order.orderId; renderOrderList(); renderDetails(); };

                div.innerHTML = `
                    <div class="info-row">
                        <strong>#${order.orderId}</strong>
                        <span>RM ${order.totalAmount}</span>
                    </div>
                    <div class="preview-row">
                        👤 ${order.customerName}<br>
                        📅 ${formatDateTime(order['dates.ordered'])}
                    </div>
                    <span class="status-badge ${statusClass}">${order.Status.toUpperCase()}</span>
                `;
                listContainer.appendChild(div);
                visibleCount++;
            }
        });
        document.getElementById('orderCount').innerText = visibleCount;
    }

    function renderDetails() {
        const order = orders.find(o => o.orderId === selectedOrderId);
        if (!order) return;

        const summaryPanel = document.getElementById('summaryContent');
        const actionPanel = document.getElementById('actionPanel');

        // Map through items using database aliases fName/dName
        let itemsHtml = order.items.map(item => {
            const name = item.fName || item.dName || "Item";
            return `<div class="info-row">
            <span>${item.Quantity}x ${name}</span> <span>RM ${item.Subtotal}</span></div>`;
        }).join('');

        summaryPanel.innerHTML = `
            <h2>${order.customerName} ${order.memberId ? '<span style="color:  #ddd200ff; font-size:12px;">  ★ MEMBER</span>' : ''}</h2>
            
            <div style="margin-bottom:12px; padding-bottom:8px; border-bottom:1px solid #ddd; font-size:12px; color:#444;">
                <p>Order ID: <strong>#${order.orderId}</strong></p>
                <p>Address: <strong>${order.address}</strong></p>
                <p>Payment Method: <strong>${order.paymentMethod}</strong></p>
            </div>

          <div style="background:white; padding:10px; border:1px solid #ddd; border-radius:6px;">
                ${itemsHtml}
                <div class="discount-row"> 
                <span>Member Discount (10%)</span> 
                <span>-RM ${(order.totalAmount/9).toFixed(2)}</span>
                </div>
                <div class="total-row"><span>TOTAL</span> <span>RM ${Number(order.totalAmount).toFixed(2)}</span></div>
            </div>
            <div style="font-size:11px; margin-top:10px;">
                <div class="info-row"><span>Ordered:</span> <span>${formatDateTime(order['dates.ordered'])}</span></div>
                <div class="info-row"><span>Ready:</span> <span>${formatDateTime(order['dates.ready'])}</span></div>
                <div class="info-row"><span>Picked Up :</span> <span>${formatDateTime(order['dates.picked'])}</span></div>
                <div class="info-row"><span>Delivered:</span> <span>${formatDateTime(order['dates.delivered'])}</span></div>
            </div>
        `;

       
    }

    renderOrderList();

    </script>
</body>
</html>