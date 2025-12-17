<?php
include 'db_connect.php';
session_start();

// ==========================================
// ROUTER LOGIC
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_step1'])) {
    
    // 1. Save data to temp_order
    $_SESSION['temp_order'] = [];
    foreach ($_POST as $key => $val) {
        if ($val > 0 && strpos($key, 'acqty') === 0) {
            $_SESSION['temp_order'][$key] = $val;
        }
    }

    // 2. Check if "Aneka Lauk Thai" items were ordered (acqty10 - acqty27)
    $hasThai = false;
    // We check ranges by simple loop or manual array. 
    // Loop is safer: check keys from acqty10 to acqty27
    for ($i = 10; $i <= 27; $i++) {
        $code = 'acqty' . $i;
        if (isset($_POST[$code]) && $_POST[$code] > 0) {
            $hasThai = true;
            break;
        }
    }

    // 3. DECISION
    if (!$hasThai) {
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

<h2>Ala-Carte Menu Add On</h2>
<img src="menuPics/Slide8.jpeg" alt="Ala-Carte Menu">

<div>
    <table class="bigTable" border="0">
        <form method="POST" action="result.php">
        <tr>
            <td>
                <h3>Add On (For Aneka Lauk Thai)</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Nasi Putih</td><td>RM 2.00</td><td><?php echo renderInput('acadd1', $food_data); ?></td></tr>
                    <tr><td>Nasi Goreng</td><td>RM 3.00</td><td><?php echo renderInput('acadd2', $food_data); ?></td></tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="buttonContainer">
    <a href="alaOrder1.php" class="navButton">« Back</a>
    <input type="submit" name="submit_step2" value="Next »" class="navButton">
</div>
</form>

</body>
</html>