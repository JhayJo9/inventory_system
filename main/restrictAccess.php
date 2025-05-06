<?php

function restrictAccess($allowedRoles) {
   // START SESSION IF NOT ALREADY STARTED
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
    // CHECK IF USER IS LOGGED IN AND HAS PROPER ROLE
    if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: login.php");
        exit;
    }
}
?>