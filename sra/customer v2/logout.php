<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<body>
    <script>
        // Clear client-side storage
        sessionStorage.removeItem("isLoggedIn");
        // Redirect back to customer page
        window.location.href = "customerpage.php";
    </script>
</body>
</html>