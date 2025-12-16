// sample menu, later modify to php 
    const menuData = [
        {
            category: "Sup ZZ",
            items: [
                { id: 'F001', name: 'Sup Gearbox Kambing', price: 19.00, available: true, type: 'food' },
                { id: 'F002', name: 'Sup Kambing', price: 20.00, available: true, type: 'food' }
            ]
        },
        {
            category: "Mee Rebus ZZ",
            items: [
                { id: 'F003', name: 'Mee Rebus Gearbox Kambing', price: 20.00, available: true, type: 'food' },
                { id: 'F004', name: 'Mee Rebus Daging', price: 9.50, available: true, type: 'food' }
            ]
        },
        {
            category: "Sarapan",
            subcategories: [
                {
                    name: "Masakan Panas",
                    items: [
                        { id: 'F005', name: 'Lontong Kuah', price: 7.50, available: true, type: 'food' },
                        { id: 'F006', name: 'Nasi Lemak Basmathi', price: 6.00, available: true, type: 'food' }
                    ]
                },
                {
                    name: "Roti Bakar",
                    items: [
                        { id: 'F007', name: 'Roti Bakar', price: 2.50, available: true, type: 'food' },
                        { id: 'F008', name: 'Roti Kaya', price: 3.50, available: false, type: 'food' }
                    ]
                }
            ]
        },
        {
            category: "Roti Canai",
            items: [
                { id: 'F009', name: 'Roti Kosong', price: 1.50, available: true, type: 'food' },
                { id: 'F010', name: 'Roti Telur', price: 2.80, available: true, type: 'food' }
            ]
        },
        {
            category: "Set Tengah Hari",
            subcategories: [
                {
                    name: "Set Nasi & Lauk",
                    items: [
                        { id: 'F011', name: 'Nasi Bawal Goreng Berlado', price: 9.00, available: true, type: 'food' }
                    ]
                },
                {
                    name: "Masakan Panas",
                    items: [
                        { id: 'F012', name: 'Bubur Ayam', price: 6.50, available: true, type: 'food' }
                    ]
                }
            ]
        },
        {
            category: "Menu Ikan",
            subcategories: [
                {
                    name: "Ikan Siakap",
                    items: [
                        { id: 'F013', name: 'Tiga Rasa', price: 35.00, available: true, type: 'food' },
                        { id: 'F014', name: 'Steam Lemon', price: 35.00, available: true, type: 'food' }
                    ]
                },
                {
                    name: "Bakar-bakar",
                    items: [
                        { id: 'F015', name: 'Siakap Bakar', price: 35.00, available: true, type: 'food' }
                    ]
                }
            ]
        },
        {
            category: "Ala-Carte Menu",
            subcategories: [
                {
                    name: "Goreng Tepung",
                    items: [{ id: 'F016', name: 'Sotong', price: 10.50, available: true, type: 'food' }]
                },
                {
                    name: "Sayur",
                    items: [{ id: 'F017', name: 'Kailan Ikan Masin', price: 7.00, available: true, type: 'food' }]
                },
                {
                    name: "Aneka Lauk Thai",
                    items: [{ id: 'F018', name: 'Daging Merah', price: 8.50, available: true, type: 'food' }]
                },
                {
                    name: "Mee Kuah",
                    items: [{ id: 'F019', name: 'Mee Bandung', price: 10.50, available: true, type: 'food' }]
                },
                {
                    name: "Sup Ala Thai & Tomyam",
                    items: [{ id: 'F020', name: 'Tomyam Campur', price: 13.00, available: true, type: 'food' }]
                }
            ]
        },
        {
            category: "Western Food",
            subcategories: [
                {
                    name: "Fried & Grill",
                    items: [{ id: 'F021', name: 'Chicken Chop', price: 18.50, available: true, type: 'food' }]
                },
                {
                    name: "Spaghetti",
                    items: [{ id: 'F022', name: 'Carbonara Chicken', price: 14.00, available: true, type: 'food' }]
                },
                {
                    name: "Burger & Sides",
                    items: [{ id: 'F023', name: 'Crispy Chicken Burger', price: 7.50, available: true, type: 'food' }]
                }
            ]
        },
        {
            category: "Goreng-goreng",
            subcategories: [
                { name: "Nasi Goreng", items: [{ id: 'F024', name: 'Nasi Goreng Kampung', price: 8.00, available: true, type: 'food' }] },
                { name: "Mee Goreng", items: [{ id: 'F025', name: 'Mee Goreng', price: 7.50, available: true, type: 'food' }] }
            ]
        },
        {
            category: "Drinks",
            subcategories: [
                {
                    name: "Coffee",
                    items: [
                        { id: 'B001', name: 'Indo Cafe O', priceHot: 4.70, priceCold: 5.00, available: true, type: 'drink_dual' },
                        { id: 'B002', name: 'Indo Cafe Susu', priceHot: 4.70, priceCold: 5.00, available: true, type: 'drink_dual' }
                    ]
                },
                {
                    name: "Non-coffee",
                    items: [
                        { id: 'B003', name: 'Teh Tarik', priceHot: 2.50, priceCold: 3.00, available: true, type: 'drink_dual' },
                        { id: 'B004', name: 'Teh O', priceHot: 2.30, priceCold: 2.50, available: true, type: 'drink_dual' }
                    ]
                },
                {
                    name: "Jus",
                    items: [
                        { id: 'B005', name: 'Jus Orange', priceHot: 4.70, priceCold: 5.00, available: true, type: 'drink_dual' },
                        { id: 'B006', name: 'Jus Apple', priceHot: 4.70, priceCold: 5.00, available: true, type: 'drink_dual' }
                    ]
                },
                {
                    name: "Cold Dessert",
                    items: [
                        { id: 'B007', name: 'Cendol', price: 6.00, available: true, type: 'food' }, 
                    ]
                }
            ]
        }
    ];


// render menu
    function renderMenu() {
        const container = document.getElementById('menuContainer');
        const sidebar = document.getElementById('sidebarList');
        
        container.innerHTML = '';
        sidebar.innerHTML = '';

        menuData.forEach((section, index) => {
            // Generate Sidebar Link
            const anchorId = 'cat-' + index;
            
            const li = document.createElement('li');
            li.innerHTML = `<a href="#${anchorId}">${section.category}</a>`;
            sidebar.appendChild(li);

            // Generate Main Content
            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'category-section';
            sectionDiv.id = anchorId; 
            
            sectionDiv.innerHTML = `<div class="main-category-title">${section.category}</div>`;

            // Render Subcategories or Items
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

    // Create Single Card
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
                </div>
            `;
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
                </div>
            `;
        }

        card.innerHTML = `
            <div class="card-top">
                <span class="item-id ${badgeClass}">${item.id}</span>
                <label class="toggle-switch">
                    <input type="checkbox" ${item.available ? 'checked' : ''} onchange="toggleAvailability('${item.id}')">
                    <span class="slider"></span>
                </label>
            </div>
            <div class="item-name">${item.name}</div>
            ${priceHtml}
        `;
        return card;
    }

// action
    function updatePrice(id, type, val) {
        const value = parseFloat(val);
        const item = findItemById(id);

        if (item) {
            if (type === 'hot') item.priceHot = value;
            else if (type === 'cold') item.priceCold = value;
            else item.price = value;
            console.log(`Updated ${id}: ${type} price -> ${value}`);
        }
    }

    function toggleAvailability(id) {
        const item = findItemById(id);
        const card = document.getElementById(`card-${id}`);

        if (item && card) {
            item.available = !item.available;
            if (item.available) {
                card.classList.remove('unavailable');
            } else {
                card.classList.add('unavailable');
            }
        }
    }

    function findItemById(id) {
        for (let section of menuData) {
            if (section.items) {
                const found = section.items.find(i => i.id === id);
                if (found) return found;
            }
            if (section.subcategories) {
                for (let sub of section.subcategories) {
                    const found = sub.items.find(i => i.id === id);
                    if (found) return found;
                }
            }
        }
        return null;
    }

    // Init
    renderMenu();