<?php
session_start();

// IMPORT THE NEEDED FILES TO ACCESS
include("../config.php");
include("restrictAccess.php");

// IDENTIFYING THE USER WHO CAN ACCESS THIS PAGE
restrictAccess(['Admin', 'Staff']);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
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
  <title>Admin Dashboard</title>
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
    .welcome {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    .welcome span {
      font-weight: 700;
    }
    .subtitle {
      font-size: 16px;
      font-weight: 400;
      margin-bottom: 2rem;
      color: #555;
    }
    .stats-container {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background-color: white;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .stat-title {
      font-size: 14px;
      font-weight: 500;
      color: #666;
      margin-bottom: 0.5rem;
    }
    .stat-value {
      font-size: 28px;
      font-weight: 600;
      color: #333;
    }
    @media screen and (max-width: 768px) {
      .side {
        width: 100px;
      }
      .main {
        margin-left: 100px;
        width: calc(100% - 100px);
      }
      .stats-container {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>
</head>
<body>
      <?php include("sidebar.php") ?>

  <div class="main">
  <h5 class="card-title"><strong>Items</strong></h5>
  <p class="card-text">You can edit, update, and delete items here</p>



  <?php if ($_SESSION['role'] === 'Admin'): ?>
      <div class="mb-3">
      <a href="save_item.php" class="btn btn-success"><i class='bx bx-plus'></i> Add Item</a>
    </div>
        <?php endif; ?>

  <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Unit</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Restock Point</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM items";
          $result = $conn->query($sql);

          if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['item_no']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['item_unit']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['restock_point']) . "</td>";
                  echo "<td>
                          <button class='btn btn-warning btn-sm'><i class='bx bx-edit'></i></button>
                          <button class='btn btn-danger btn-sm'><i class='bx bx-trash'></i></button>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='7'>No items found</td></tr>";
          }
          ?>
        </tbody>
      </table>
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
