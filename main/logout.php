<?php
//session_start();
include("items.php");

if(isset($_POST['logout'])){
    session_start();
    session_unset();      // Remove all session variables
    session_destroy();    // Destroy the session
    header("Location: login.php"); // Redirect to login page
}else {
    echo "<script>alert('gfgd')";
}



exit;

