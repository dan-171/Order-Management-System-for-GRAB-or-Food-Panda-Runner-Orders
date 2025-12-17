<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['username_unique'])) { header("Location: login.php"); exit(); }

// The 'id' here refers to the ID in the 'order_items' table
$id = $_GET['id'] ?? null;

// 1. Fetch current item details
if ($id) {
    // JOIN order_items -> orders -> food
    $sqlFetch = "SELECT f.name, oi.quantity, o.status 
                 FROM order_items oi
                 JOIN orders o ON oi.order_id = o.order_id
                 JOIN food f ON oi.food_code = f.food_code 
                 WHERE oi.id = ?";
                 
    $stmt = $conn->prepare($sqlFetch);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $item = $res->fetch_assoc();

    // Safety Check: Ensure the order is still in 'cart' status
    if (!$item || $item['status'] !== 'cart') {
        echo "<script>alert('Cannot edit this item (Order already purchased or invalid).'); window.location.href='cart.php';</script>";
        exit();
    }
}

// 2. Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newQty = $_POST['quantity'];
    $itemID = $_POST['item_id']; // This is the ID from order_items table

    if ($newQty > 0) {
        // Update quantity in order_items table
        $update = $conn->prepare("UPDATE order_items SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $newQty, $itemID);
        $update->execute();
    } else {
        // If qty is 0, delete the row from order_items
        $del = $conn->prepare("DELETE FROM order_items WHERE id = ?");
        $del->bind_param("i", $itemID);
        $del->execute();
    }
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Item</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="width:300px; margin:50px auto; background:white; padding:20px; text-align:center; border-radius:8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-top:0;">Edit Quantity</h2>
        
        <?php if ($item): ?>
            <h3 style="color:#555;"><?php echo htmlspecialchars($item['name']); ?></h3>
            
            <form method="POST" action="">
                <input type="hidden" name="item_id" value="<?php echo $id; ?>">
                
                <label style="font-weight:bold;">Quantity:</label>
                <br>
                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0" max="10" style="width:80px; padding:8px; margin:10px 0; font-size:16px; text-align:center;">
                
                <br><br>
                
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <input type="submit" value="Update" class="navButton" style="margin:0; flex:1;">
                    <a href="cart.php" class="navButton" style="margin:0; flex:1; background-color: #555; text-decoration:none; line-height: 20px;">Cancel</a>
                </div>
            </form>
            
            <br>
            <form method="POST" action="" onsubmit="return confirm('Remove this item?');">
                 <input type="hidden" name="item_id" value="<?php echo $id; ?>">
                 <input type="hidden" name="quantity" value="0"> <input type="submit" value="Remove Item" style="background:none; border:none; color:red; text-decoration:underline; cursor:pointer;">
            </form>
            
        <?php else: ?>
            <p>Item not found.</p>
            <a href="cart.php">Back</a>
        <?php endif; ?>
    </div>
</body>
</html>