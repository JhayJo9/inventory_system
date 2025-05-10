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

// Check if category ID is provided
if(isset($_GET['id'])) {
    $categoryId = $_GET['id'];
    
    // Check if there are items associated with this category
    $checkQuery = "SELECT COUNT(*) as itemCount FROM items WHERE category_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $categoryId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();
    $itemCount = $row['itemCount'];
    
    if($itemCount > 0) {
        $_SESSION['error_message'] = "Cannot delete category. It has associated items.";
    } else {
        // Check if the category exists first
        $checkCategoryQuery = "SELECT category_id FROM category WHERE category_id = ?";
        $checkCategoryStmt = $conn->prepare($checkCategoryQuery);
        $checkCategoryStmt->bind_param("i", $categoryId);
        $checkCategoryStmt->execute();
        $categoryResult = $checkCategoryStmt->get_result();
        
        if ($categoryResult->num_rows === 0) {
            $_SESSION['error_message'] = "Category not found.";
        } else {
            // Delete the category from the database
            $deleteQuery = "DELETE FROM category WHERE category_id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $categoryId);
            
            if($deleteStmt->execute()) {
                if ($deleteStmt->affected_rows > 0) {
                    $_SESSION['success_message'] = "Category deleted successfully!";
                } else {
                    $_SESSION['error_message'] = "No category was deleted. It may have been removed already.";
                }
            } else {
                $_SESSION['error_message'] = "Failed to delete category: " . $conn->error;
            }
            
            $deleteStmt->close();
        }
        
        if (isset($checkCategoryStmt)) {
            $checkCategoryStmt->close();
        }
    }
    
    $checkStmt->close();
} else {
    $_SESSION['error_message'] = "Category ID not provided.";
}

// Redirect back to the category page
header("Location: category.php");
exit();
?>