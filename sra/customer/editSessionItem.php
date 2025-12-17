<?php
include 'db_connect.php';
session_start();

$code = $_GET['code'] ?? null;

// If no code provided, go back
if (!$code) {
    header("Location: result.php");
    exit();
}

// 1. Fetch Item Name (Visual only)
$stmt = $conn->prepare("SELECT name FROM food WHERE food_code = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$res = $stmt->get_result();
$food = $res->fetch_assoc();
$foodName = $food ? $food['name'] : "Unknown Item";

// 2. Get Current Quantity from Session
$currentQty = isset($_SESSION['temp_order'][$code]) ? $_SESSION['temp_order'][$code] : 1;

// 3. Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newQty = intval($_POST['quantity']);

    if ($newQty > 0) {
        $_SESSION['temp_order'][$code] = $newQty;
    } else {
        unset($_SESSION['temp_order'][$code]); // Remove if 0
    }
    header("Location: result.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Summary</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div style="width:300px; margin:50px auto; background:white; padding:20px; text-align:center; border-radius:8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-top:0;">Edit Order</h2>
        <h3 style="color:#555;"><?php echo htmlspecialchars($foodName); ?></h3>
        
        <form method="POST" action="">
            <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>">
            
            <label style="font-weight:bold;">Quantity:</label>
            <br>
            <input type="number" name="quantity" value="<?php echo $currentQty; ?>" min="0" max="10" style="width:80px; padding:8px; margin:10px 0; font-size:16px; text-align:center;">
            
            <br><br>
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <input type="submit" value="Update" class="navButton" style="margin:0; flex:1;">
                
                <a href="result.php" class="navButton" style="margin:0; flex:1; background-color: #555; text-decoration:none; line-height: 20px;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>