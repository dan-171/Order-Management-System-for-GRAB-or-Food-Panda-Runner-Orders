<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username_unique'])) {
    $user = $_SESSION['username_unique'];
    
    // 1. Capture Form Data
    $address = $_POST['delivery_address'];
    $paymentMethod = $_POST['payment_method'];
    $order_id = $_POST['order_id']; // Passed from purchase.php

    // 2. Update Customer Profile (Save address for next time)
    $updateStmt = $conn->prepare("UPDATE customer SET address = ? WHERE username = ?");
    $updateStmt->bind_param("ss", $address, $user);
    $updateStmt->execute();
    $updateStmt->close();

    // 3. FINALIZE ORDER
    // A. Calculate the Final Total Price from the database
    $calcSql = "SELECT SUM(oi.quantity * f.price) as grand_total 
                FROM order_items oi
                JOIN food f ON oi.food_code = f.food_code
                WHERE oi.order_id = ?";
    
    $stmtCalc = $conn->prepare($calcSql);
    $stmtCalc->bind_param("i", $order_id);
    $stmtCalc->execute();
    $resCalc = $stmtCalc->get_result();
    $rowCalc = $resCalc->fetch_assoc();
    $finalTotal = $rowCalc['grand_total'] ?? 0;
    $stmtCalc->close();

    // B. Update the Orders Table
    // Set status to 'purchased', save the calculated total, address, and payment method
    $sql = "UPDATE orders 
            SET status = 'purchased', 
                total_amount = ?, 
                delivery_address = ?, 
                payment_method = ?,
                order_date = CURRENT_TIMESTAMP
            WHERE order_id = ? AND username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dssis", $finalTotal, $address, $paymentMethod, $order_id, $user);
    
    if ($stmt->execute()) {
        header("Location: cart.php?msg=success");
    } else {
        echo "Error processing order: " . $conn->error;
    }
    $stmt->close();
} else {
    header("Location: cart.php");
}
?>