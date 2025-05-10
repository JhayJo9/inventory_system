<?php
// Include database connection
include 'connection.php';

// Check if item ID is provided
if(isset($_GET['itemId'])) {
    $itemId = $_GET['itemId'];
    
    // Delete the item from the database
    $deleteQuery = "DELETE FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $itemId);
    
    if($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!'); window.location.href='itemManagement.php';</script>";
    } else {
        echo "<script>alert('Failed to delete item. Please try again.'); window.location.href='itemManagement.php';</script>";
    }
    
    $stmt->close();
} else {
    echo "<script>alert('Item ID not provided.'); window.location.href='itemManagement.php';</script>";
}

$conn->close();
?>