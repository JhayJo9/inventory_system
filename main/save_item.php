<?php
session_start();

include("../config.php");
include("restrictAccess.php");
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// IDENTIFYING THE USER WHO CAN ACCESS THIS PAGE
restrictAccess(['Admin', 'Staff']);
// Form submission handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['item_id'];
    $name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['item_unit'];
    $restock = $_POST['restock_point'];


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO items (item_no, item_name, category, quantity, item_unit, restock_point) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issisi", $id, $name, $category, $quantity, $unit, $restock);

    if ($stmt->execute()) {
        header("Location: items.php?success=1");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
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
  <title>Add Item</title>
  <style>
    * { font-family: 'Montserrat', sans-serif; box-sizing: border-box; padding: 0; margin: 0; }
    body { display: flex; background-color: #F5EEDD; }
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
    .title { font-weight: 600; font-size: 20px; margin-bottom: 1rem; }
    .circle {
      width: 100px; height: 100px; border-radius: 50%;
      background-color: #D9D9D9; color: #393E46;
      display: flex; justify-content: center; align-items: center;
      font-weight: bold; font-size: 40px; margin-bottom: 0.5rem;
    }
    .name { margin-bottom: 0.5rem; font-weight: 500; }
    .role {
      width: 85px; height: 25px; border-radius: 8px;
      background-color: #D9D9D9; color: black;
      font-weight: 600; text-align: center; padding-top: 0.3rem;
      font-size: 13px; margin-bottom: 4rem;
    }
    .nav-anchor {
      display: flex; flex-direction: column; gap: 1.5rem;
      width: 100%; padding-left: 2rem; margin-bottom: 2rem;
    }
    .nav-anchor a {
      font-size: 15px; font-weight: 500; color: white;
      text-decoration: none; display: flex; align-items: center; gap: 10px;
      transition: all 0.3s;
    }
    .nav-anchor a:hover { color: gray; }
    .logout-btn {
      font-size: 15px; font-weight: 500; color: white;
      background: transparent; border: none; cursor: pointer;
      display: flex; align-items: center; gap: 10px;
      width: 100%; text-align: left; transition: color 0.3s;
      padding-left: 2rem;
    }
    .logout-btn:hover { color: gray; }
    .logout-container { margin-top: auto; margin-bottom: 2rem; width: 100%; }
    .main {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 3rem;
    }
    .welcome { font-size: 2rem; font-weight: bold; }
    .subtitle { color: #666; margin-bottom: 2rem; }
  </style>
</head>
<body>

    <?php include('sidebar.php') ?>
    
<div class="main">
  <h1 class="welcome">Add new item</h1>
  <p class="subtitle">You can edit, update, and delete items here</p>

  <div style="max-width: 600px; padding: 2rem; border-radius: 15px;">
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form action="save_item.php" method="post">
      <div class="row mb-3">
        <div class="col">
          <label>Item No.</label>
          <input type="text" name="item_id" class="form-control" required>
        </div>
        <div class="col">
          <label>Category</label>
          <select name="category" class="form-select" required>
            <option disabled selected>Select Category</option>
            <option value="Cleaning">Cleaning</option>
            <option value="Furniture">Furniture</option>
          </select>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label>Item Name</label>
          <input type="text" name="item_name" class="form-control" required>
        </div>
        <div class="col">
          <label>Quantity</label>
          <input type="number" name="quantity" class="form-control" required>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label>Item Unit</label>
          <input type="text" name="item_unit" class="form-control" required>
        </div>
        <div class="col">
          <label>Restock Point</label>
          <input type="number" name="restock_point" class="form-control" required>
        </div>
      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-dark me-2">Add</button>
        <a href="items.php" class="btn btn-danger">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- LOGOUT CONFIRMATION MODAL -->
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
            <button type="submit" class="btn btn-light text-dark fw-bold">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
