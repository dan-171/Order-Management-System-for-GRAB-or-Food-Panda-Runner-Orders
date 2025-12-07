<?php
session_start();
session_unset();
session_destroy();

$role = isset($_GET['role']) ? $_GET['role'] : '';

header("Location: ../sra/$role/login.php");
exit;
?>