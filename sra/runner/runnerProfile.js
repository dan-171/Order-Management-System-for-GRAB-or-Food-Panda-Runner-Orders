//simple runner
const runners = [
    { id: 'RUN-01', name: 'Ali', password: "123", platform: 'GrabFood', telephone: '0121112222', email: "haha213@gmail.com", status: "Disabled" },
    { id: 'RUN-02', name: 'Muthu', platform: 'FoodPanda', phone: '0163334444' },
    { id: 'RUN-03', name: 'Chong', platform: 'GrabFood', phone: '0195556666' }
];

//Modify to php to detect which runner logging in later
const runner = runners.find(r => r.id === "RUN-01");

let isEditing = false; 
let passwordVisible = false; 

// Notification function
function notify(msg) {
    const bar = document.getElementById('notificationBar');
    bar.innerText = msg;
    bar.style.backgroundColor = '#7bfb77ff';
    bar.classList.remove('hidden');
    setTimeout(() => { 
        bar.classList.add('hidden'); 
    }, 3000);
}


function togglePasswordVisibility() {
    passwordVisible = !passwordVisible;

    const editInput = document.getElementById('editPassword');
    const passwordDisplay = document.getElementById('passwordText');
    const toggleBtns = document.querySelectorAll('.toggle-password'); 

    // Update edit mode input 
    if (editInput) {
        editInput.type = passwordVisible ? 'text' : 'password';
    }

    // Update view mode display 
    if (passwordDisplay) {
        passwordDisplay.textContent = passwordVisible ? runner.password : '••••••••';
    }

    // Update all eye buttons 
    toggleBtns.forEach(btn => {
        btn.innerHTML = passwordVisible ? '👁️' : '⌣';
        btn.title = passwordVisible ? 'Hide password' : 'Show password';
    });
}



// Toggle between view and edit mode
function toggleEditProfile() {
    isEditing = !isEditing;
    passwordVisible = false; // Reset password visibility when switching modes
    renderDetails();
}

function validateEmail() {
    const email = document.getElementById('editEmail').value.trim();
    const emailField = document.getElementById('editEmail');

    if (email === "") {
        alert("Please provide your Email!");
        emailField.focus();
        return false;
    }

    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address (e.g., example@domain.com)");
        emailField.focus();
        return false;
    }

    return true;
}

function validateTelephone() {
    const telephone = document.getElementById('editTelephone').value.trim();
    const telField = document.getElementById('editTelephone');

    if (telephone === "") {
        alert("Please provide your telephone number!");
        telField.focus();
        return false;
    }

    const telPattern = /^01[0-9]\d{7,8}$/;
    
    if (!telPattern.test(telephone)) {
        alert("Please enter a valid Malaysian phone number (10-11 digits) (e.g., 01134567890 or 0191234567)");
        telField.focus();
        return false;
    }

    return true;
}

// Save profile changes
function saveProfile() {
    if (!validateEmail()) return;
    if (!validateTelephone()) return;

    // Get values from input fields
    const name = document.getElementById('editName').value;
    const password = document.getElementById('editPassword').value;
    const telephone = document.getElementById('editTelephone').value;
    const email = document.getElementById('editEmail').value;
    const platform = document.getElementById('editPlatform').value;
    const status = document.getElementById('editStatus').value;

    // Update runner object
    runner.name = name;
    runner.password = password;
    runner.telephone = telephone;
    runner.email = email;
    runner.platform = platform;
    runner.status = status;

    // Save to localStorage, change to sql ltr
    localStorage.setItem('runnerProfile', JSON.stringify(runner));

    // Show success message
    notify('Profile updated successfully!');

    // Switch back to view mode
    isEditing = false;
    passwordVisible = false;
    renderDetails();
}

// Cancel editing
function cancelEdit() {
    isEditing = false;
    passwordVisible = false;
    renderDetails();
    notify('Edit Profile cancelled successfully!');
}

// Load saved profile from localStorage
function loadSavedProfile() {
    const savedProfile = localStorage.getItem('runnerProfile');
    if (savedProfile) {
        const savedData = JSON.parse(savedProfile);
        // Only update if it's the same runner
        if (savedData.id === runner.id) {
            Object.assign(runner, savedData);
        }
    }
}

// Render profile details (both view and edit modes)
function renderDetails() {
    const personalPanel = document.getElementById('personalDetails');

    if (isEditing) {
        // EDIT MODE
        personalPanel.innerHTML = `
            <div class="name-logo-container">
                <input type="text" 
                       id="editName" 
                       value="${runner.name}" 
                       class="edit-input" 
                       placeholder="Name">
                <p>from</p>
                ${runner.platform === 'GrabFood' ? 
                    `<img src="../../images/grab-logo.webp" class="runner-logo" alt="grab-logo">` : 
                runner.platform === 'FoodPanda' ? 
                    `<img src="../../images/foodpanda-logo.webp" class="runner-logo" alt="foodpanda-logo">` :
                    ''
                }
            </div>
            
            <div class="personal edit-mode">
                <div class="form-group">
                    <label>Runner ID:</label>
                    <div class="readonly-field">${runner.id}</div>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <div class="password-container">
                        <input type="password" 
                               id="editPassword" 
                               value="${runner.password}" 
                               class="edit-input password-input" 
                               placeholder="Password">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" title="Show password">⌣</button>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Telephone:</label>
                    <input type="tel" 
                           id="editTelephone" 
                           value="${runner.telephone || runner.phone || ''}" 
                           class="edit-input" 
                           placeholder="Telephone">
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" 
                           id="editEmail" 
                           value="${runner.email || ''}" 
                           class="edit-input" 
                           placeholder="Email">
                </div>
                
                <div class="form-group">
                    <label>Platform:</label>
                    <select id="editPlatform" class="edit-select">
                        <option value="GrabFood" ${runner.platform === 'GrabFood' ? 'selected' : ''}>GrabFood</option>
                        <option value="FoodPanda" ${runner.platform === 'FoodPanda' ? 'selected' : ''}>FoodPanda</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Status:</label>
                    <select id="editStatus" class="edit-select">
                        <option value="Active" ${runner.status === 'Active' ? 'selected' : ''}>🟢 Active</option>
                        <option value="Disabled" ${runner.status === 'Disabled' ? 'selected' : ''}>🔴 Disabled</option>
                    </select>
                </div>
                
                <div class="button-group">
                    <button class="save-btn" onclick="saveProfile()">💾 Save Changes</button>
                    <button class="cancel-btn" onclick="cancelEdit()">❌ Cancel</button>
                </div>
            </div>
        `;
    } else {
        // VIEW MODE
        personalPanel.innerHTML = `
            <div class="name-logo-container">
                <h1 class="runnerName">${runner.name} from</h1>
                ${runner.platform === 'GrabFood' ? 
                    `<img src="../../images/grab-logo.webp" class="runner-logo" alt="grab-logo">` : 
                runner.platform === 'FoodPanda' ? 
                    `<img src="../../images/foodpanda-logo.webp" class="runner-logo" alt="foodpanda-logo">` :
                    ''
                }
            </div>
            
            <div class="personal">
                <div class="detail-row">
                    <span class="detail-label">Runner ID:</span>
                    <span class="detail-value">${runner.id}</span>
                </div>
                
                <div class="detail-row password-row">
                    <span class="detail-label">Password:</span>
                    <div class="password-container">
                        <span id="passwordText" class="detail-value ">
                            ${passwordVisible ? runner.password : '••••••••'}
                        </span>
                        <button type="button" 
                                class="toggle-password" 
                                onclick="togglePasswordVisibility()" 
                                title="${passwordVisible ? 'Hide password' : 'Show password'}">
                            ${passwordVisible ? '👁️' : '⌣'}
                        </button>
                    </div>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Telephone:</span>
                    <span class="detail-value">${runner.telephone}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">${runner.email}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Platform:</span>
                    <span class="detail-value">${runner.platform}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value status-value ${runner.status === 'Active' ? 'active' : 'disabled'}">
                        ${runner.status === 'Active' ? '🟢 Active' : '🔴 Disabled'}
                    </span>
                </div>
            </div>
            
            <div class="button-group">
                <button class="edit-btn" onclick="toggleEditProfile()">✏️ Edit Profile</button>
            </div>
        `;
    }
}



// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadSavedProfile();
    renderDetails();
});