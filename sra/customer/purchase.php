<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['username_unique'])) { header("Location: login.php"); exit(); }

$user = $_SESSION['username_unique'];
$grandTotal = 0;
$order_id = null;

// 1. FETCH USER'S SAVED ADDRESS
$stmt = $conn->prepare("SELECT address FROM customer WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$resUser = $stmt->get_result();
$rowUser = $resUser->fetch_assoc();
$currentAddress = $rowUser['address'] ?? ""; 
$stmt->close();

// 2. FETCH CART TOTAL (New Logic for 2 Tables)
$sqlCart = "SELECT o.order_id, SUM(oi.quantity * f.price) as total 
            FROM orders o
            JOIN order_items oi ON o.order_id = oi.order_id
            JOIN food f ON oi.food_code = f.food_code
            WHERE o.username = ? AND o.status = 'cart'";

$stmtCart = $conn->prepare($sqlCart);
$stmtCart->bind_param("s", $user);
$stmtCart->execute();
$resCart = $stmtCart->get_result();

if ($rowCart = $resCart->fetch_assoc()) {
    $grandTotal = $rowCart['total'] ?? 0;
    $order_id = $rowCart['order_id'];
}

// Redirect if cart is empty
if ($grandTotal == 0) {
    echo "<script>alert('Your cart is empty!'); window.location.href='cart.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Purchase</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="width: 400px; margin: 50px auto; text-align:center; background:white; padding:30px; border-radius:8px;">
        <h2>Confirm Purchase</h2>
        <p>Total Amount: <strong>RM <?php echo number_format($grandTotal, 2); ?></strong></p>
        
        <form method="POST" action="processPurchase.php">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            
            <label style="display:block; text-align:left; margin:10px 0; font-weight:bold;">Delivery Address:</label>
            <textarea name="delivery_address" 
                      required 
                      rows="4" 
                      placeholder="Please enter your delivery address here..." 
                      style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px; resize:vertical;"><?php echo htmlspecialchars($currentAddress); ?></textarea>

            <label style="display:block; text-align:left; margin:10px 0; font-weight:bold;">Payment Method:</label>
            <select name="payment_method" style="width:100%; padding:10px; margin-bottom:20px; border:1px solid #ccc; border-radius:5px;">
                <option value="cash">Cash on Delivery</option>
                <option value="online">Online Banking</option>
                <option value="ewallet">E-Wallet</option>
            </select>
            
            <input type="submit" name="confirm_purchase" value="Confirm Payment" class="navButton" style="width:100%;">
            <br><br>
            <a href="cart.php" style="color:#8b0000; text-decoration:none;">Cancel</a>
        </form>
    </div>
</body>
</html>