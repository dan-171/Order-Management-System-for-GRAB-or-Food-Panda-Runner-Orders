<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['username_unique'])) { header("Location: login.php"); exit(); }
$user = $_SESSION['username_unique'];

// FETCH ACTIVE CART ONLY (Status = 'cart')
$cartTotal = 0;

// NEW QUERY: Joins orders -> order_items -> food
// We select 'oi.id' (the specific item ID) so we can delete/edit it later
$sqlCart = "SELECT oi.id, f.name, f.price, oi.quantity 
            FROM order_items oi 
            JOIN orders o ON oi.order_id = o.order_id
            JOIN food f ON oi.food_code = f.food_code 
            WHERE o.username = ? AND o.status = 'cart'";

$stmtCart = $conn->prepare($sqlCart);
$stmtCart->bind_param("s", $user);
$stmtCart->execute();
$resCart = $stmtCart->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Your Cart</title><link rel="stylesheet" href="style.css">
</head>
<body>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
    <h3 style="color:green; text-align:center;">Purchase Successful! Check "Order Status" to track your delivery.</h3>
<?php endif; ?>

<h2>Your Active Cart</h2>
<table class="menuTable">
    <tr><th>Item</th><th>Quantity</th><th>Price (RM)</th><th>Action</th></tr>
    <?php
    if ($resCart->num_rows > 0) {
        while($row = $resCart->fetch_assoc()) {
            $cost = $row['price'] * $row['quantity'];
            $cartTotal += $cost;
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>" . number_format($cost, 2) . "</td>
                    <td><a href='editCartItem.php?id={$row['id']}' class='navButton' style='padding:5px 10px; font-size:12px;'>Edit/Remove</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Cart is empty.</td></tr>";
    }
    ?>
    <tr><th colspan="2">Total</th><th colspan="2">RM <?php echo number_format($cartTotal, 2); ?></th></tr>
</table>

<div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back to Menu</a>
    <?php if ($cartTotal > 0): ?>
        <a href="purchase.php" class="navButton">Purchase</a>
    <?php endif; ?>
</div>

</body>
</html>