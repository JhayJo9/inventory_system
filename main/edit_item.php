<?php
session_start();

// IMPORT THE NEEDED FILES TO ACCESS
require_once('check_session.php');
include("../config.php");
include("restrictAccess.php");

// IDENTIFYING THE USER WHO CAN ACCESS THIS PAGE
restrictAccess(['Admin', 'Staff']);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$itemId = $_GET['id'];

// FETCH EACH ITEM IN THE DATABASE
$sql = "SELECT * FROM items WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $itemId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_message'] = "Item not found";
    header("Location: items.php");
    exit;
}

$item = $result->fetch_assoc();
// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: items.php");
    exit;
}



// HANDLE FROM FORM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // For Admin users, they can update all fields
    if ($_SESSION['role'] === 'Admin') {
        $itemId = $_POST['itemNo'];
        $itemName = $_POST['itemName'];
        $category = $_POST['category'];
        $quantity = $_POST['quantity'];
        $itemUnit = $_POST['itemUnit'];
        $restockPoint = $_POST['restockPoint'];
        
        // Update item with all fields
        $updateSql = "UPDATE items SET 
                      item_id = ?, 
                      item_name = ?, 
                      category = ?, 
                      quantity = ?, 
                      item_unit = ?, 
                      restock_point = ? 
                      WHERE item_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("issisii", $itemId, $itemName, $category, $quantity, $itemUnit, $restockPoint, $itemId);
    } else {
        // For Staff users, they can only update quantity
        $quantity = $_POST['quantity'];
        
        // Update item with only quantity
        $updateSql = "UPDATE items SET quantity = ? WHERE item_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $quantity, $itemId);
    }
    
    if ($updateStmt->execute()) {
        $_SESSION['success_message'] = "Item updated successfully";
        header("Location: items.php");
        exit;
    } else {
        $error = "Error updating item: " . $conn->error;
    }
}

    // DISPLAY AUTOMATICALLY THE STATUS BASED ON THE QUANTITY AND RESTOCK POINT
    $status = "Sufficient";
    if ($item['quantity'] == 0) {
        $status = "Out of Stock";
    } elseif ($item['quantity'] <= $item['restock_point']) {
        $status = "Low Stock";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Edit Item</title>
  <style>
    * {
      font-family: 'Montserrat', sans-serif;
      box-sizing: border-box;
      padding: 0;
      margin: 0;
    }
    body {
      display: flex;
      background-color: #F5EEDD;
    }
    .side {
      background-color: #393E46;
      width: 250px;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: fixed;
      padding-top: 2rem;
      color: white;
    }
    .title {
      font-weight: 600;
      font-size: 20px;
      margin-bottom: 1rem;
    }
    .circle {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background-color: #D9D9D9;
      color: #393E46;
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: bold;
      font-size: 40px;
      margin-bottom: 0.5rem;
    }
    .name {
      margin-bottom: 0.5rem;
      font-weight: 500;
    }
    .role {
      width: 85px;
      height: 25px;
      border-radius: 8px;
      background-color: #D9D9D9;
      color: black;
      font-weight: 600;
      text-align: center;
      padding-top: 0.3rem;
      font-size: 13px;
      margin-bottom: 4rem;
    }
    .nav-anchor {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      width: 100%;
      padding-left: 2rem;
      margin-bottom: 2rem;
    }
    .nav-anchor a {
      font-size: 15px;
      font-weight: 500;
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s;
    }
    .nav-anchor a:hover {
      color: gray;
    }
    .logout-btn {
      font-size: 15px;
      font-weight: 500;
      color: white;
      background: transparent;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      text-align: left;
      transition: color 0.3s;
      padding-left: 2rem;
    }
    .logout-btn:hover {
      color: gray;
    }
    .logout-container {
      margin-top: auto;
      margin-bottom: 2rem;
      width: 100%;
    }
    .main {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 2rem;
    }
    @media screen and (max-width: 768px) {
      .side {
        width: 100px;
      }
      .main {
        margin-left: 100px;
        width: calc(100% - 100px);
      }
    }
  </style>
</head>
<body>
  <?php include("sidebar.php") ?>

  <div class="main">
    <h5 class="card-title">Edit Item</h5>
    <?php if ($_SESSION['role'] === 'Admin'): ?>
      <p class="card-text">Modify item details</p>
    <?php else: ?>
      <p class="card-text">Update item quantity</p>
    <?php endif; ?>
    
    <div class="card">
      <div class="card-body">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form id="editItemForm" action="edit_item.php?id=<?php echo $itemId; ?>" method="POST">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="itemNo" class="form-label">Item ID</label>
              <input type="text" disabled class="form-control" id="itemNo" name="itemNo" value="<?php echo htmlspecialchars($item['item_id']); ?>" <?php echo ($_SESSION['role'] === 'Admin') ? 'required' : 'readonly'; ?>>
            </div>
            <div class="col-md-6 mb-3">
              <label for="itemName" class="form-label">Item Name</label>
              <input type="text" class="form-control" id="itemName" name="itemName" value="<?php echo htmlspecialchars($item['item_name']); ?>" <?php echo ($_SESSION['role'] === 'Admin') ? 'required' : 'readonly'; ?>>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="category" class="form-label">Category</label>
              <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($item['category']); ?>" <?php echo ($_SESSION['role'] === 'Admin') ? 'required' : 'readonly'; ?>>
            </div>
            <div class="col-md-6 mb-3">
              <label for="itemUnit" class="form-label">Unit</label>
              <input type="text" class="form-control" id="itemUnit" name="itemUnit" value="<?php echo htmlspecialchars($item['item_unit']); ?>" <?php echo ($_SESSION['role'] === 'Admin') ? 'required' : 'readonly'; ?>>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="quantity" class="form-label">Quantity</label>
              <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required min="0">
            </div>
            <div class="col-md-6 mb-3">
              <label for="restockPoint" class="form-label">Restock Point</label>
              <input type="number" class="form-control" id="restockPoint" name="restockPoint" value="<?php echo htmlspecialchars($item['restock_point']); ?>" <?php echo ($_SESSION['role'] === 'Admin') ? 'required' : 'readonly'; ?> min="0">
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="status" class="form-label">Status</label>
              <input type="text" disabled class="form-control" id="status" value="<?php echo $status; ?>" readonly>
              <small class="text-muted">Status is calculated automatically based on quantity and restock point</small>
            </div>
          </div>
          
          <div class="mt-4 d-flex justify-content-end">
            <a href="item.php" class="btn btn-secondary me-2">Cancel</a>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmUpdateModal">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- UPDATE CONFIRMATION MODAL -->
  <div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmUpdateModalLabel">Confirm Update</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to update this item?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-warning" onclick="document.getElementById('editItemForm').submit()">Update</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="background-color: #4E5758; color: white; border-radius: 10px;">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to logout?
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form action="logout.php" method="post">
            <input type="submit" name="Logout" value="Logout" class="btn btn-light text-dark fw-bold" />
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>