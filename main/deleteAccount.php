<?php
// Start session
session_start();

// IMPORT REQUIRED CONNECTION AND ACCESS CONTROL FILES
require_once('check_session.php');
include("../config.php");
include("restrictAccess.php");

// RESTRICT ACCESS TO ADMIN ONLY
restrictAccess(['Admin']);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if account ID is provided
if(isset($_GET['id'])) {
    $accountId = $_GET['id'];
    
    // Check if user is trying to delete their own account
    if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $accountId) {
        $_SESSION['error_message'] = "You cannot delete your own account while logged in.";
        header("Location: account.php");
        exit();
    }
    
    // Check if this is the last admin account
    $checkAdminQuery = "SELECT COUNT(*) as adminCount FROM users u 
                        JOIN roles r ON u.role_id = r.role_id 
                        WHERE r.role_name = 'Admin'";
    $adminResult = $conn->query($checkAdminQuery);
    $adminRow = $adminResult->fetch_assoc();
    $adminCount = $adminRow['adminCount'];
    
    // Get the role of the user to be deleted
    $roleQuery = "SELECT r.role_name FROM users u 
                 JOIN roles r ON u.role_id = r.role_id 
                 WHERE u.user_id = ?";
    $roleStmt = $conn->prepare($roleQuery);
    $roleStmt->bind_param("i", $accountId);
    $roleStmt->execute();
    $roleResult = $roleStmt->get_result();
    
    // Check if the user exists
    if ($roleResult->num_rows === 0) {
        $_SESSION['error_message'] = "Account not found.";
        header("Location: account.php");
        exit();
    }
    
    $roleRow = $roleResult->fetch_assoc();
    $userRole = $roleRow['role_name'];
    
    if($adminCount <= 1 && $userRole == 'Admin') {
        $_SESSION['error_message'] = "Cannot delete the last admin account.";
        header("Location: account.php");
        exit();
    } else {
        // Delete the account from the database
        $deleteQuery = "DELETE FROM users WHERE user_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $accountId);
        
        if($deleteStmt->execute()) {
            if ($deleteStmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Account deleted successfully!";
            } else {
                $_SESSION['error_message'] = "No account was deleted. It may have been removed already.";
            }
        } else {
            $_SESSION['error_message'] = "Failed to delete account: " . $conn->error;
        }
        
        $deleteStmt->close();
    }
    
    $roleStmt->close();
} else {
    $_SESSION['error_message'] = "Account ID not provided.";
}

// Redirect back to the accounts page
header("Location: account.php");
exit();
?>