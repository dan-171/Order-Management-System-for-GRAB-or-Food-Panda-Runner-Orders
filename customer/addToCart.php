<?php
include 'db_connect.php';
session_start();

// 1. Validation: Check login and if there are items to add
if (!isset($_SESSION['username_unique'])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['temp_order'])) {
    echo "<script>alert('No items to add!'); window.location.href='customerpage.php';</script>";
    exit();
}

$user = $_SESSION['username_unique'];
$items = $_SESSION['temp_order']; // This is the array of items from Signature Dish flow

// 2. GET OR CREATE THE ORDER HEADER (orders table)
// We need an active order_id before we can add items.
$checkCart = $conn->prepare("SELECT order_id FROM orders WHERE username = ? AND status = 'cart'");
$checkCart->bind_param("s", $user);
$checkCart->execute();
$resOrder = $checkCart->get_result();

if ($resOrder->num_rows > 0) {
    // Existing cart found
    $row = $resOrder->fetch_assoc();
    $order_id = $row['order_id'];
} else {
    // No active cart, create a new one
    $createOrder = $conn->prepare("INSERT INTO orders (username, status) VALUES (?, 'cart')");
    $createOrder->bind_param("s", $user);
    $createOrder->execute();
    $order_id = $conn->insert_id; // Get the ID of the newly created order
    $createOrder->close();
}
$checkCart->close();

// 3. INSERT ITEMS INTO ORDER_ITEMS TABLE
// Now we loop through the session items and save them to the correct table.
foreach ($items as $food_code => $qty) {
    
    // Check if this specific item is already in this order
    $checkItem = $conn->prepare("SELECT id FROM order_items WHERE order_id = ? AND food_code = ?");
    $checkItem->bind_param("is", $order_id, $food_code);
    $checkItem->execute();
    $resItem = $checkItem->get_result();

    if ($resItem->num_rows > 0) {
        // SCENARIO A: Item exists, UPDATE quantity
        $updateStmt = $conn->prepare("UPDATE order_items SET quantity = quantity + ? WHERE order_id = ? AND food_code = ?");
        $updateStmt->bind_param("iis", $qty, $order_id, $food_code);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // SCENARIO B: Item is new, INSERT row
        $insertStmt = $conn->prepare("INSERT INTO order_items (order_id, food_code, quantity) VALUES (?, ?, ?)");
        $insertStmt->bind_param("isi", $order_id, $food_code, $qty);
        $insertStmt->execute();
        $insertStmt->close();
    }
    $checkItem->close();
}

// 4. CLEANUP AND REDIRECT
unset($_SESSION['temp_order']); // Clear the temporary signature dish session
echo "<script>alert('Items added to cart successfully!'); window.location.href='cart.php';</script>";
?>