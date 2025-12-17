//simple menu, later will modify to php
    const menuItems = {
        'M01': { name: 'Sup Gearbox Kambing', price: 19.00 },
        'M02': { name: 'Sup Kambing', price: 20.00 },
        'M03': { name: 'Sup Daging', price: 8.00 },
        'M04': { name: 'Teh O Ais', price: 2.50 },
        'M05': { name: 'Sup ayam', price: 7.00 }
    };

//simple runner
    const runners = [
        { id: 'RUN-01', name: 'Ali', platform: 'GrabFood', phone: '012-1112222' },
        { id: 'RUN-02', name: 'Muthu', platform: 'FoodPanda', phone: '016-3334444' },
        { id: 'RUN-03', name: 'Chong', platform: 'GrabFood', phone: '019-5556666' }
    ];

    const now = new Date();

//simple order, later modify to php
    let orders = [
        {
            orderId: 'ORD-1002',
            customerName: 'Sarah Lee',
            memberId: 'MEM-001', 
            runnerID: null, 
            items: [
                { itemID: 'M05', quantity: 1 },
                { itemID: 'M04', quantity: 1 }
            ],
            status: 'ready to pick up',
            dates: {
                ordered: new Date(now.getFullYear(), now.getMonth(), now.getDate(), 10, 15), 
                ready: new Date(now.getFullYear(), now.getMonth(), now.getDate(), 10, 45),
                picked: null,
                delivered: null
            },
            address: " 160, Jln Bukit Tambun Perdana 3, 76100 Durian Tunggal, Melaka"
        },
        {
            orderId: 'ORD-1004',
            customerName: 'Yee Ho',
            memberId: 'MEM-002',
            runnerID: null,
            items: [
                { itemID: 'M01', quantity: 2 },
                { itemID: 'M05', quantity: 2 }
            ],
            status: 'ready to pick up', 
            dates: {
                ordered: new Date(now.getFullYear(), now.getMonth(), now.getDate(), 12, 30), 
                ready: new Date(now.getFullYear(), now.getMonth(), now.getDate(), 13, 2),
                picked: null,
                delivered: null
            },
            address: "47, Jalan Bukit Beruang Utama 4, Taman Bukit Beruang Utama, 75450 Ayer Keroh, Melaka"
        },
        {
            orderId: 'ORD-1005',
            customerName: 'Aunty Hoo',
            memberId: 'MEM-003',
            runnerID: null,
            items: [{ itemID: 'M01', quantity: 3 }],
            status: 'ready to pick up', 
            dates: {
                ordered: new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 16, 0),
                ready: new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 16, 43), 
                picked: null,
                delivered: null
            },
            address: "31, Jalan Pertama 2, Kampung Sungai Putat, 75350 Melaka"
        },
        {
        orderId: 'ORD-1006',
        customerName: 'John Tan',
        memberId: 'MEM-004',
        runnerID: 'RUN-03',
        items: [
            { itemID: 'M02', quantity: 1 },
            { itemID: 'M04', quantity: 2 }
        ],
        status: 'delivered',
        dates: {
            ordered: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1, 14, 30),
            ready: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1, 15, 10),
            picked: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1, 15, 25),
            delivered: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1, 15, 55)
        },
        address: "88, Jalan Tun Razak, 50400 Kuala Lumpur"
    },
    {
        orderId: 'ORD-1007',
        customerName: 'Lisa Wong',
        memberId: 'MEM-005',
        runnerID: 'RUN-01',
        items: [
            { itemID: 'M03', quantity: 2 },
            { itemID: 'M05', quantity: 1 },
            { itemID: 'M04', quantity: 1 }
        ],
        status: 'delivered',
        dates: {
            ordered: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 2, 18, 15),
            ready: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 2, 18, 45),
            picked: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 2, 19, 5),
            delivered: new Date(now.getFullYear(), now.getMonth(), now.getDate() - 2, 19, 30)
        },
        address: "45, Lorong Bukit Bintang, 55100 Kuala Lumpur"
    }
    ];


//Modify to php to detect which runner logging in later
const runner = runners.find(r => r.id === "RUN-03");

//display text at top
if (runner.platform === 'GrabFood')
{
    topText.innerHTML = `
        <h2 id = "topHeader">Welcome, ${runner.name} from</h2>
        <img src="../../images/grab-logo.webp" 
        class = "logo" 
        id = "logoRunner"
        alt = "grab-logo.webp">
    `;
}
else if (runner.platform === 'FoodPanda')
{
    topText.innerHTML = `
        <h2 id = "topHeader">Welcome, ${runner.name} from</h2>
            <img src="../../images/foodpanda-logo.webp" 
            class = "logo" 
            id = "logoRunner"
            alt = "foodpanda-logo.webp"
            >
    `;
}

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
        bar.style.backgroundColor = '#7bfb77ff';
        setTimeout(() => { bar.classList.add('hidden');; }, 3000);

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

        if (order.status === "delivered" && order.runnerID === runner.id){
            const div = document.createElement('div');
            div.className = `order-card ${selectedOrderId === order.orderId ? 'selected' : ''}`;
            div.onclick = () => selectOrder(order.orderId);

            div.innerHTML = `
                <div class="info-row">
                    <strong>${order.orderId}</strong>
                    <span>RM ${calculateTotal(order).finalTotal}</span>
                </div>

                <div style="font-size:11px; color:#888; margin-bottom:4px;">
                    👤 ${order.customerName}<br>
                    📅 ${formatDateTime(order.dates.ordered)}
                </div>

                <span class="status-badge st-delivered">
                    DELIVERED
                </span>
            `;

            listContainer.appendChild(div);
            visibleCount++;
    }
    });

    document.getElementById('orderCount').innerText = visibleCount;
}


function selectOrder(id) {
    selectedOrderId = id;
    renderOrderList(); 
    renderDetails();   
}

    // render right panel
function renderDetails() {
    const order = orders.find(o => o.orderId === selectedOrderId);
    if (!order) return;

    const summaryPanel = document.getElementById('summaryContent');
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
    timeStampsHtml = `
            <hr style="margin:10px 0;">
            <div class="info-row"><span>Readying At:</span> <span>${formatTime(order.dates.ready)}</span></div>
            <div class="info-row"><span>Picked Up At:</span> <span>${formatTime(order.dates.picked)}</span></div>
            <div class="info-row"><span>Delivered At:</span> <span>${formatTime(order.dates.delivered)}</span></div>
            `;

    summaryPanel.innerHTML = `
        <h2>${order.customerName} ${financial.isMember ? '<span style="color:  #ddd200ff; font-size:12px;">★ MEMBER</span>' : ''}</h2>
        <div style="margin-bottom:12px; padding-bottom:8px; border-bottom:1px solid #ddd; font-size:12px; color:#444;">
            <p>Order ID: <strong>${order.orderId}</strong></p>
            <p>Order Date: <strong>${orderedTimeStr}</strong></p>
            <p>Address: <strong>${order.address}</strong></p>
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

    actionTitle.innerText = "Order flow";

    let d_pickedUp = true;
    let d_delivered = true;

    if (s === 'ready to pick up') {
         d_pickedUp = false; 
    }
   
     else if (s === 'in transit') {
        d_delivered = false;
    }

    actionButtons.innerHTML = `     
        <button class= "status" onclick="updateStatus('in transit')"  ${d_pickedUp ? 'disabled' : ''}>
            1. Picked Up
        </button>

        <button class= "status" onclick="updateStatus('delivered')" ${d_delivered ? 'disabled' : ''}>
            2. Delivered
        </button>
    `;

    }


    // update orders' status
    function updateStatus(newStatus) {
        const order = orders.find(o => o.orderId === selectedOrderId);
        if (!order) return;

        order.status = newStatus;

        const nowTime = new Date();
        if (newStatus === 'in transit') order.dates.picked = nowTime;
        if (newStatus === 'delivered') order.dates.delivered = nowTime;

        renderOrderList();
        renderDetails();

        notificationBar.classList.remove('hidden');

        notify(`Order ${order.orderId} updated to: ${newStatus.toUpperCase()}`);

        
    }


    renderOrderList();