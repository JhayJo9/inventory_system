<?php


$localhost = "localhost:3307";
$username = "Yohan";
$password = "Yohan";
$dbname = "inventory_system";

//$conn = null;

$conn = new mysqli($localhost, $username, $password, $dbname, 3307);

if($conn -> connect_error){
    die("ERROR");
} else{
    //echo "connected";
}


?>