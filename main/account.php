<?php
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

if (isset($_SESSION['success_message'])) {
  unset($_SESSION['success_message']);
}
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Accounts</title>
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
  <!-- TO REUSE THE SIDEBAR --> 
  <?php include("sidebar.php") ?>

  <div class="main">
    <h5 class="card-title">Accounts</h5>
    <p class="card-text">You can add, edit, update, and delete accounts here</p>
    <!-- TO NOTIFY THE USER OF THE PROCESS IS SUCCESS OR NOT -->
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php 
            echo $_SESSION['success_message']; 
            unset($_SESSION['success_message']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php 
            echo $_SESSION['error_message']; 
            unset($_SESSION['error_message']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <div class="mb-3">
      <a href="save_account.php" class="btn btn-success"><i class='bx bx-plus'></i> Add Account</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>User Name</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // JOIN WITH ROLE TABLE TO GET THE ROLE NAMES
          $sql = "SELECT u.user_id, u.username, u.first_name, u.last_name, r.role_name, u.status 
                 FROM users u 
                 JOIN roles r ON u.role_id = r.role_id 
                 ORDER BY u.user_id";
          
          $result = $conn->query($sql);

          if ($result && $result->num_rows > 0) {
              while ($user = $result->fetch_assoc()) {
                  $badgeClass = $user['status'] == 'Active' ? 'bg-success' : 'bg-secondary';
                  
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($user['user_id']) . "</td>";
                  echo "<td>" . htmlspecialchars($user['first_name']) . "</td>";
                  echo "<td>" . htmlspecialchars($user['last_name']) . "</td>";
                  echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                  echo "<td>" . htmlspecialchars($user['role_name']) . "</td>";
                  echo "<td><span class='badge {$badgeClass}'>" . htmlspecialchars($user['status']) . "</span></td>";
                  echo "<td>
                          <a href='edit_account.php?id={$user['user_id']}' class='btn btn-warning btn-sm'><i class='bx bx-edit'></i></a>
                          <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                            data-id='{$user['user_id']}' data-name='{$user['first_name']} {$user['last_name']}'>
                            <i class='bx bx-trash'></i>
                          </button>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='7'>No accounts found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this account? This action cannot be undone.</p>
          <p id="deleteAccountName" class="fw-bold"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
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
  <script>
    // SETUP MODAL FOR DELETE
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
      deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const accountId = button.getAttribute('data-id');
        const accountName = button.getAttribute('data-name');
        
        document.getElementById('deleteAccountName').textContent = accountName;
        document.getElementById('confirmDeleteBtn').href = 'delete_account.php?id=' + accountId;
      });
    }
  </script>
</body>
</html>