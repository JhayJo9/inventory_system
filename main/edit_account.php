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

// Fetch user data from the database
$sql = "SELECT u.*, r.role_id FROM users u 
        JOIN roles r ON u.role_id = r.role_id 
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_message'] = "Account not found";
    header("Location: account.php");
    exit;
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $roleId = $_POST['roleId'];
    $status = $_POST['status'];
    
    // Check if username exists but belongs to another user
    $checkSql = "SELECT * FROM users WHERE username = ? AND user_id != ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("si", $username, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $error = "Username already exists. Please choose a different username.";
    } else {
        // Update user based on whether password is being changed
        if (empty($password)) {
            // Don't update password if field is empty
            $updateSql = "UPDATE users SET username = ?, first_name = ?, last_name = ?, role_id = ?, status = ? WHERE user_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sssisi", $username, $firstName, $lastName, $roleId, $status, $userId);
        } else {
            // Update with new password
            $updateSql = "UPDATE users SET username = ?, password = ?, first_name = ?, last_name = ?, role_id = ?, status = ? WHERE user_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sssssii", $username, $password, $firstName, $lastName, $roleId, $status, $userId);
        }
        
        if ($updateStmt->execute()) {
            $_SESSION['success_message'] = "Account updated successfully";
            header("Location: account.php");
            exit;
        } else {
            $error = "Error updating account: " . $conn->error;
        }
    }
}

// Get roles for dropdown
$rolesSql = "SELECT * FROM roles";
$rolesResult = $conn->query($rolesSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Edit Account</title>
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
    <h5 class="card-title">Edit Account</h5>
    <p class="card-text">Modify user account details</p>
    <div class="card">
      <div class="card-body">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form id="editAccountForm" action="edit_account.php?id=<?php echo $userId; ?>" method="POST">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="firstName" class="form-label">First Name</label>
              <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="lastName" class="form-label">Last Name</label>
              <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
              <small class="text-muted">Leave blank to keep current password</small>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="roleId" class="form-label">Role</label>
              <select class="form-select" id="roleId" name="roleId" required>
                <option value="">Select Role</option>
                <?php 
                // Reset the pointer to beginning of result set
                $rolesResult->data_seek(0);
                while($role = $rolesResult->fetch_assoc()): 
                  $selected = ($role['role_id'] == $user['role_id']) ? 'selected' : '';
                ?>
                  <option value="<?php echo $role['role_id']; ?>" <?php echo $selected; ?>><?php echo $role['role_name']; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="status" name="status" required>
                <option value="">Select Status</option>
                <option value="Active" <?php echo ($user['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                <option value="Inactive" <?php echo ($user['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
              </select>
            </div>
          </div>
          
          <div class="mt-4 d-flex justify-content-end">
            <a href="account.php" class="btn btn-secondary me-2">Cancel</a>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmUpdateModal">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update Confirmation Modal -->
  <div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmUpdateModalLabel">Confirm Update</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to update this account?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-warning" onclick="document.getElementById('editAccountForm').submit()">Update</button>
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