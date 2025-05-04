<?php
session_start();

// IMPORT THE NEEDED FILES TO ACCESS
include("../config.php");
include("restrictAccess.php");

// IDENTIFYING THE USER WHO CAN ACCESS THIS PAGE
restrictAccess(['Admin']);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: account.php");
    exit;
}

$userId = $_GET['id'];

// Don't allow deletion of the currently logged-in user
if ($_SESSION['user_id'] == $userId) {
    $_SESSION['error_message'] = "You cannot delete your own account";
    header("Location: account.php");
    exit;
}

// Delete the account
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Account deleted successfully";
} else {
    $_SESSION['error_message'] = "Error deleting account: " . $conn->error;
}

header("Location: account.php");
exit;
?>