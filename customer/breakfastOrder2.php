<?php
include 'db_connect.php';
session_start();

// ==========================================
// ROUTER LOGIC
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_step1'])) {
    
    // 1. Save new data to temp_order
    $_SESSION['temp_order'] = [];
    foreach ($_POST as $key => $val) {
        if ($val > 0 && strpos($key, 'bqty') === 0) {
            $_SESSION['temp_order'][$key] = $val;
        }
    }

    // 2. Check if any "Roti Bakar" items were ordered (bqty20, bqty21, bqty22)
    $hasRoti = false;
    $rotiItems = ['bqty20', 'bqty21', 'bqty22'];
    
    foreach ($rotiItems as $item) {
        if (isset($_POST[$item]) && $_POST[$item] > 0) {
            $hasRoti = true;
            break;
        }
    }

    // 3. DECISION:
    // If NO Roti items -> Go straight to Result (Skip Add-on)
    if (!$hasRoti) {
        header("Location: result.php");
        exit();
    }
    // If HAS Roti -> Continue to show this page
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
<title>Breakfast Add On</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Breakfast Add-on</h2>
<img src="menuPics/Slide4.jpeg" alt="Breakfast">

<div>
    <table class="bigTable" border="0">
        <form method="POST" action="result.php">
        <tr>
            <td>
                <h3>Add On (For Roti)</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr>
                        <td>Telur 1/2 masak</td>
                        <td>RM 3.50</td>
                        <td><?php echo renderInput('badd1', $food_data); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="buttonContainer">
    <a href="breakfastOrder.php" class="navButton">« Back</a>
    <input type="submit" name="submit_step2" value="Next »" class="navButton">
</div>
</form>

</body>
</html>