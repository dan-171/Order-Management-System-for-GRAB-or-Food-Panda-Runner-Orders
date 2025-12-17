<?php
include 'db_connect.php';
session_start();

// ==========================================
// ROUTER LOGIC
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_step1'])) {
    
    $_SESSION['temp_order'] = [];
    foreach ($_POST as $key => $val) {
        // Capture everything (aqty, tqty, mqty)
        if ($val > 0) {
            $_SESSION['temp_order'][$key] = $val;
        }
    }

    // CHECK IF ELIGIBLE FOR ADD-ONS (Sup Ala Thai OR Tomyam)
    $eligibleForAddon = false;
    foreach ($_SESSION['temp_order'] as $key => $val) {
        // If key starts with 'aqty' (Sup) or 'tqty' (Tomyam)
        if (strpos($key, 'aqty') === 0 || strpos($key, 'tqty') === 0) {
            $eligibleForAddon = true;
            break;
        }
    }

    // DECISION
    if (!$eligibleForAddon) {
        header("Location: result.php");
        exit();
    }
}

// Fetch availability
$food_data = [];
$result = $conn->query("SELECT food_code, is_available FROM food");
while($row = $result->fetch_assoc()) { $food_data[$row['food_code']] = $row['is_available']; }

function renderInput($id, $data) {
    if (isset($data[$id]) && $data[$id] == 0) return "<input type='text' value='Sold Out' disabled>";
    return "<input type='number' name='$id' value='0' min='0' max='10'>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ala-Carte Add On</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Sup Ala Thai & Tomyam Add-ons</h2>
<img src="menuPics/Slide9.jpeg" alt="Ala-Carte Menu2">

<div>
    <table class="bigTable" border="0">
        <form method="POST" action="result.php">
        <tr>
            <td>
                <h3>Add On</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Mee</td><td>RM 2.00</td><td><?php echo renderInput('ac2add1', $food_data); ?></td></tr>
                    <tr><td>Mee Hoon</td><td>RM 2.00</td><td><?php echo renderInput('ac2add2', $food_data); ?></td></tr>
                    <tr><td>Kuey Teow</td><td>RM 2.00</td><td><?php echo renderInput('ac2add3', $food_data); ?></td></tr>
                </table>
            </td>
        </tr> 
    </table>
</div>

<div class="buttonContainer">
    <a href="alaOrder2.php" class="navButton">« Back</a>
	<input type="submit" name="submit_step2" value="Next »" class="navButton">
</div>

</form>

</body>
</html>