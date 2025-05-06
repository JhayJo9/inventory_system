<?php 

    // REDIRECT TO LOGIN IF USER IS NOT LOGGED IN
    if(!isset($_SESSION['username']) && !isset($_SESSION['password'])){
            header('Location: login.php');
            exit;
    }

?>