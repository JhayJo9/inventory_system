<?php
function restrictAccess($allowedRoles) {
    session_start();
    if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: /system/login/login.php");
        exit;
    }
}
?>