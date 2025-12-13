// sample menu, later will modify to php
    const menuItems = {
        'M01': { name: 'Sup Gearbox Kambing', price: 19.00 },
        'M02': { name: 'Sup Kambing', price: 20.00 },
        'M03': { name: 'Sup Daging', price: 8.00 },
        'M04': { name: 'Teh O Ais', price: 2.50 },
        'M05': { name: 'Sup ayam', price: 7.00 }
    };

//sample runner
    const runners = [
        { id: 'RUN-01', name: 'Ali (Grab)', platform: 'GrabFood', phone: '012-1112222' },
        { id: 'RUN-02', name: 'Muthu (Panda)', platform: 'FoodPanda', phone: '016-3334444' },
        { id: 'RUN-03', name: 'Chong (Shopee)', platform: 'ShopeeFood', phone: '019-5556666' }
    ];

    const now = new Date();

// sample order,later modify to php
    let orders = [
        {
            orderId: 'ORD-1001',
            customerName: 'Ahmad Albab',
            type: 'walk-in',
            memberId: 'MEM-001',
            runnerID: null,
            items: [
                { itemID: 'M01', quantity: 2 },
                { itemID: 'M02', quantity: 2 }
            ],
            status: 'placed', 
            dates: {
                ordered: new Date(now.getFullYear(), now.getMonth(), now.getDate(), 14, 30), 
                ready: null,
                picked: null,
                delivered: null
            }
        },
        {
            orderId: 'ORD-1002',
            customerName: 'Sarah Lee',
            type: 'online', 
            memberId: null, 
            runnerID: null, 
            items: [
                { itemID: 'M05', quantity: 1 },
                { itemID: 'M04', quantity: 1 }
            ],
            status: 'placed',
            dates: {
                ordered: new Date(now.getFullYear(), now.getMonth(), now.getDate(), 10, 15), 
                ready: null,
                picked: null,
                delivered: null
            }
        },
        {
            orderId: 'ORD-1003',
            customerName: 'Uncle Bob',
            type: 'walk-in',
            memberId: null,
            runnerID: null,
            items: [{ itemID: 'M01', quantity: 1 }],
            status: 'placed', 
            dates: {
                ordered: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1, 20, 0),
                ready: null, 
                picked: null,
                delivered: null
            }
        }
    ];

//selected order

    let selectedOrderId = null;

    function calculateTotal(order) {
        let subtotal = 0;
        order.items.forEach(item => {
            const menu = menuItems[item.itemID];
            if(menu) subtotal += menu.price * item.quantity;
        });

        // check member Id
        let discount = 0;
        if (order.memberId) {
            discount = subtotal * 0.10; // 10% Discount for members
        }

        return {
            subtotal: subtotal.toFixed(2),
            discount: discount.toFixed(2),
            finalTotal: (subtotal - discount).toFixed(2),
            isMember: !!order.memberId
        };
    }

    // time format
    function formatTime(dateObj) {
        if (!dateObj) return '-';
        return new Date(dateObj).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function formatDateTime(dateObj) {
        if (!dateObj) return '-';
        const d = new Date(dateObj);
        return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' }) + ', ' + 
               d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // notification 
    function notify(msg) {
        const bar = document.getElementById('notificationBar');
        bar.innerText = msg;
        bar.style.backgroundColor = '#333';
        setTimeout(() => { bar.innerText = "System Ready."; bar.style.backgroundColor = 'black'; }, 3000);
    }

// render left panel
function renderOrderList() {
        const listContainer = document.getElementById('orderList');
        const searchVal = document.getElementById('searchInput').value.toLowerCase();
        listContainer.innerHTML = '';

        let visibleCount = 0;

        orders.forEach(order => {
            if (searchVal && !order.orderId.toLowerCase().includes(searchVal) && !order.customerName.toLowerCase().includes(searchVal)) {
                return;
            }

            let statusClass = 'st-placed';
            if (order.status.includes('readying')) statusClass = 'st-readying';
            else if (order.status.includes('ready to')) statusClass = 'st-ready';
            else if (order.status === 'picked up') statusClass = 'st-picked';
            else if (order.status === 'delivered') statusClass = 'st-delivered';

            const div = document.createElement('div');
            div.className = `order-card ${selectedOrderId === order.orderId ? 'selected' : ''}`;
            div.onclick = () => selectOrder(order.orderId);

            const orderedTimeStr = formatDateTime(order.dates.ordered);

            div.innerHTML = `
                <div class="info-row">
                    <strong>${order.orderId}</strong>
                    <span>RM ${calculateTotal(order).finalTotal}</span>
                </div>
                
                <div style="font-size:11px; color:#888; margin-bottom:4px;">
                    📅 ${orderedTimeStr}
                </div>

                <div style="font-size:13px; color:#555; margin-bottom:5px;">${order.customerName} (${order.type})</div>
                <span class="status-badge ${statusClass}">${order.status.toUpperCase()}</span>
            `;
            listContainer.appendChild(div);
            visibleCount++;
        });

        document.getElementById('orderCount').innerText = visibleCount;
    }

    function selectOrder(id) {
        selectedOrderId = id;
        renderOrderList(); // 刷新列表高亮
        renderDetails();   // 刷新右侧详情
    }

    // render right panel
    function renderDetails() {
        const order = orders.find(o => o.orderId === selectedOrderId);
        if (!order) return;

        const summaryPanel = document.getElementById('summaryContent');
        const deliveryPanel = document.getElementById('deliveryPanel');
        const actionPanel = document.getElementById('actionPanel');
        const actionTitle = document.getElementById('actionTitle');
        const actionButtons = document.getElementById('actionButtons');

        const financial = calculateTotal(order);
        const orderedTimeStr = formatDateTime(order.dates.ordered);

        let itemsHtml = order.items.map(item => {
            const menu = menuItems[item.itemID];
            return `<div class="info-row"><span>${item.quantity}x ${menu.name}</span> <span>RM ${(menu.price * item.quantity).toFixed(2)}</span></div>`;
        }).join('');

        let timeStampsHtml = '';
        if (order.type === 'walk-in') {
             timeStampsHtml = `
                <hr style="margin:10px 0;">
                <div class="info-row"><small>Readying At:</small> <small>${formatTime(order.dates.ready)}</small></div>
                <div class="info-row"><small>Delivered At:</small> <small>${formatTime(order.dates.delivered)}</small></div>
             `;
        } else {
             timeStampsHtml = `
                <hr style="margin:10px 0;">
                <div class="info-row"><small>Readying At:</small> <small>${formatTime(order.dates.ready)}</small></div>
                <div class="info-row"><small>Picked Up At:</small> <small>${formatTime(order.dates.picked)}</small></div>
                <div class="info-row"><small>Delivered At:</small> <small>${formatTime(order.dates.delivered)}</small></div>
             `;
        }

        summaryPanel.innerHTML = `
            <h3>${order.customerName} ${financial.isMember ? '<span style="color:gold; font-size:12px;">★ MEMBER</span>' : ''}</h3>
            <div style="margin-bottom:12px; padding-bottom:8px; border-bottom:1px solid #ddd; font-size:12px; color:#444;">
                <p>Order ID: <strong>${order.orderId}</strong></p>
                <p>Type: <strong>${order.type.toUpperCase()}</strong></p>
                <p>Order Date: <strong>${orderedTimeStr}</strong></p>
            </div>
            <div style="background:white; padding:10px; border:1px solid #ddd; border-radius:6px;">
                ${itemsHtml}
                ${financial.isMember ? `<div class="discount-row"><span>Member Discount (10%)</span> <span>-RM ${financial.discount}</span></div>` : ''}
                <div class="total-row"><span>TOTAL</span> <span>RM ${financial.finalTotal}</span></div>
            </div>
            ${timeStampsHtml}
        `;

        actionPanel.classList.remove('hidden');

        const s = order.status; 

        // for walk-in panel
        if (order.type === 'walk-in') {            
            deliveryPanel.classList.add('hidden');
            actionTitle.innerText = "Walk-in Customer Flow";
            
            const disableReadying = s !== 'placed'; 
            const disableDelivered = s !== 'readying order';

            actionButtons.innerHTML = `
                <button onclick="updateStatus('readying order')" ${disableReadying ? 'disabled' : ''}>
                    1. Readying Order
                </button>
                <button class="primary" onclick="updateStatus('delivered')" ${disableDelivered ? 'disabled' : ''}>
                    2. Delivered (Complete)
                </button>
            `;

        } else {
            deliveryPanel.classList.remove('hidden');
            renderDeliveryPanel(order);
            actionTitle.innerText = "Online Order Flow";

            let d_readying = true;
            let d_readyToPick = true;
            let d_pickedUp = true;
            let d_delivered = true;

            if (s === 'placed') {
                d_readying = false;
            } 
            else if (s === 'readying order') {
                d_readyToPick = false; //
            }
            else if (s === 'ready to picked up') {
                if (order.runnerID) {
                    d_pickedUp = false; 
                } else {
                    d_pickedUp = true; 
                }
            }
            else if (s === 'picked up') {
                d_delivered = false;
            }

            actionButtons.innerHTML = `
                <button onclick="updateStatus('readying order')" ${d_readying ? 'disabled' : ''}>
                    1. Start Cooking
                </button>
                
                <button onclick="updateStatus('ready to picked up')" ${d_readyToPick ? 'disabled' : ''}>
                    2. Ready for Pickup
                </button>
                
                <button onclick="updateStatus('picked up')" ${d_pickedUp ? 'disabled' : ''}>
                    3. Picked Up ${!order.runnerID && s === 'ready to picked up' ? '(Assign Runner First!)' : ''}
                </button>
                
                <button class="primary" onclick="updateStatus('delivered')" ${d_delivered ? 'disabled' : ''}>
                    4. Mark Delivered
                </button>
            `;
        }
    }

    // render delivery panel
    function renderDeliveryPanel(order) {
        const container = document.getElementById('deliveryContent');

        if (order.runnerID) {
            const runner = runners.find(r => r.id === order.runnerID);
            container.innerHTML = `
                <div style="background:white; padding:10px; border:1px solid green; border-radius:6px; border-left: 5px solid green;">
                    <div style="font-weight:bold; color:green;">✓ Runner Assigned</div>
                    <div>Name: <strong>${runner.name}</strong></div>
                    <div>Platform: ${runner.platform}</div>
                    <div>Phone: ${runner.phone}</div>
                    <button class="small" style="margin-top:8px; width:100%;" onclick="enableReassign()">Re-assign Runner</button>
                </div>
            `;
        } else {
            let options = `<option value="auto">Auto-Assign (Random)</option>`;
            runners.forEach(r => {
                options += `<option value="${r.id}">${r.name} (${r.platform})</option>`;
            });

            container.innerHTML = `
                <p style="margin-bottom:5px; font-size:14px;">Select a runner for this order:</p>
                <div style="display:flex; gap:10px;">
                    <select id="runnerSelect" style="flex:1; padding:8px; border:2px solid black; border-radius:6px;">
                        ${options}
                    </select>
                    <button class="primary" onclick="assignRunner()">Assign</button>
                </div>
            `;
        }
    }

    // update orders' status
    function updateStatus(newStatus) {
        const order = orders.find(o => o.orderId === selectedOrderId);
        if (!order) return;

        order.status = newStatus;

        const nowTime = new Date();
        if (newStatus === 'readying order') order.dates.ready = nowTime; // Start cooking
        if (newStatus === 'ready to picked up') order.dates.ready = nowTime; // Finished cooking
        if (newStatus === 'picked up') order.dates.picked = nowTime;
        if (newStatus === 'delivered') order.dates.delivered = nowTime;

        renderOrderList();
        renderDetails();
        notify(`Order ${order.orderId} updated to: ${newStatus.toUpperCase()}`);
    }

    // assign runner
    function assignRunner() {
        const select = document.getElementById('runnerSelect');
        const val = select.value;
        const order = orders.find(o => o.orderId === selectedOrderId);

        let assignedRunnerId = val;

        // random assign
        if (val === 'auto') {
            const random = Math.floor(Math.random() * runners.length);
            assignedRunnerId = runners[random].id;
        }

        order.runnerID = assignedRunnerId;
        
        renderDetails();
        notify(`Runner assigned successfully!`);
    }

    function enableReassign() {
        const order = orders.find(o => o.orderId === selectedOrderId);
        order.runnerID = null;
        renderDetails();
    }

    renderOrderList();