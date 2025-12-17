<?php
include 'db_connect.php';
session_start();

// 1. HANDLE CANCELLATION (New Code)
if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
    // Clear the temporary order
    unset($_SESSION['temp_order']);
    // Redirect to customer page
    header("Location: customerpage.php");
    exit();
}

// 2. HANDLE INCOMING ORDER SUBMISSION
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_step2'])) {
    foreach ($_POST as $key => $val) {
        // Just check if it's not the submit button and has a value
        if ($key != 'submit_step2' && $val > 0) {
            $_SESSION['temp_order'][$key] = $val;
        }
    }
}

$total = 0;
$display_rows = "";
$cart_items = isset($_SESSION['temp_order']) ? $_SESSION['temp_order'] : [];

foreach ($cart_items as $code => $qty) {
    $stmt = $conn->prepare("SELECT name, price FROM food WHERE food_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $cost = $row['price'] * $qty;
        $total += $cost;
        
        $display_rows .= "<tr>
                            <td>{$row['name']}</td>
                            <td>{$qty}</td>
                            <td>RM " . number_format($cost, 2) . "</td>
                            <td>
                                <a href='editSessionItem.php?code=$code' class='navButton' style='padding:5px 10px; font-size:12px;'>Edit</a>
                            </td>
                          </tr>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Summary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Order Summary</h2>
<div id="result">
    <table class='menuTable'>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        
        <?php echo $display_rows; ?>
        
        <tr>
            <th colspan="2">Total</th>
            <th colspan="2">RM <?php echo number_format($total, 2); ?></th>
        </tr>
    </table>
</div>

<div class="buttonContainer">
    <a href="result.php?action=cancel" class="navButton" style="background-color: #555;">Cancel</a>
    
    <a href="addToCart.php" class="navButton">Add to Cart</a>
</div>

</body>
</html>