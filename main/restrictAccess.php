<?php

function restrictAccess($allowedRoles) {
   //session_start();
   // Start session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
    //print(session_start());
    if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: login.php");
        exit;
    }
}
?>