<?php
include '../../config.php';
session_start();

// post request handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? '';
    $value = $_POST['value'] ?? '';
    
    // identify table and id column based on ID prefix  
    $table = '';
    $idCol = '';
    
    // identify item type and ID based on ID prefix
    if (strpos($id, 'F') === 0) {
        $table = 'food';
        $idCol = 'foodID';
    } elseif (strpos($id, 'B') === 0) {
        $table = 'drinks';
        $idCol = 'drinkID';
    } elseif (strpos($id, 'A') === 0) { // 新增 Addon 判断
        $table = 'addons';
        $idCol = 'addonID';
    } else {
        // error: invalid ID format
        die("Invalid ID format");
    }

    // toggle availability
    if ($action === 'toggle_availability') {
        $status = ($value == 1) ? 'Available' : 'Unavailable';
        
        // update availability in DB
        $stmt = $pdo->prepare("UPDATE $table SET Availability = ? WHERE $idCol = ?");
        if ($stmt->execute([$status, $id])) {
            echo "success";
        } else {
            echo "db_error";
        }
    }

    // update price
    elseif ($action === 'update_price') {
        $type = $_POST['price_type']; // 'single', 'hot', 'cold', based on item
        
        if ($table === 'food' || $table === 'addons') {
            // Food and Addon only have single Price
            $stmt = $pdo->prepare("UPDATE $table SET Price = ? WHERE $idCol = ?");
            $stmt->execute([$value, $id]);
        } elseif ($table === 'drinks') {
            // Drinks may have hotPrice and/or coldPrice
            if ($type === 'hot') {
                $stmt = $pdo->prepare("UPDATE drinks SET hotPrice = ? WHERE drinkID = ?");
                $stmt->execute([$value, $id]);
            } else {
                // cold price
                $stmt = $pdo->prepare("UPDATE drinks SET coldPrice = ? WHERE drinkID = ?");
                $stmt->execute([$value, $id]);
            }
        }
        echo "success";
    }
}
?>