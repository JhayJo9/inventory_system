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

// Get user data from database
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Process form submission
$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_profile'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $newUsername = $_POST['username'];
    $password = !empty($_POST['password']) ? $_POST['password'] : $user['password'];
    
    // Check if username was changed and is not already taken
    if ($newUsername != $username) {
        $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND username != ?");
        $checkStmt->bind_param("ss", $newUsername, $username);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $errorMessage = "Username already exists. Please choose another.";
            $checkStmt->close();
        } else {
            $checkStmt->close();
            
            // Update user information
            $updateStmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, username = ?, password = ? WHERE username = ?");
            $updateStmt->bind_param("ssssss", $firstName, $lastName, $email, $newUsername, $password, $username);
            
            if ($updateStmt->execute()) {
                $_SESSION['username'] = $newUsername; // Update session with new username
                $successMessage = "Profile updated successfully!";
                
                // Refresh user data
                $username = $newUsername;
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();
            } else {
                $errorMessage = "Error updating profile: " . $conn->error;
            }
            
            $updateStmt->close();
        }
    } else {
        // Update user information without changing username
        $updateStmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE username = ?");
        $updateStmt->bind_param("sssss", $firstName, $lastName, $email, $password, $username);
        
        if ($updateStmt->execute()) {
            $successMessage = "Profile updated successfully!";
            
            // Refresh user data
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
        } else {
            $errorMessage = "Error updating profile: " . $conn->error;
        }
        
        $updateStmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile - Inventory System</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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

    .page-title {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 1.5rem;
      color: #393E46;
    }
    .profile-form {
      background-color: #D9D9D9;
      border-radius: 10px;
      padding: 2rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      max-width: 530px;
      display: flex;
      gap: 2rem;
    }
    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }
    .form-group label {
      display: block;
      font-size: 14px;
      font-weight: 500;
      color: #666;
      margin-bottom: 0.5rem;
    }
    .form-group input {
      width: 400px;
      padding: 0.75rem;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }
    .form-actions {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
    }
    .form-actions button {
      padding: 0.75rem 1.5rem;
      border-radius: 5px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.3s;
    }
    .save-btn {
      background-color: #393E46;
      color: white;
    }
    .save-btn:hover {
      background-color: #2d3138;
    }
    .cancel-btn {
      background-color: #f0f0f0;
      color: #333;
    }
    .cancel-btn:hover {
      background-color: #e0e0e0;
    }
    
    /* Password toggle icon styles */
    .password-toggle-icon {
      position: absolute;
      right: 10px;
      top: 38px;
      cursor: pointer;
      color: #666;
    }
    
    /* Alert styles */
    .alert {
      margin-bottom: 1rem;
      padding: 0.75rem 1.25rem;
      border-radius: 5px;
    }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    @media screen and (max-width: 768px) {
      .side {
        width: 100px;
      }
      .main {
        margin-left: 100px;
        width: calc(100% - 100px);
      }
      .profile-form {
        flex-direction: column;
        align-items: center;
      }
      .profile-circle {
        width: 200px;
        height: 200px;
        font-size: 50px;
      }
      .form-group input {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <?php include("sidebar.php") ?>

  <div class="main">
    <h1 class="page-title">Personal Information</h1>
    
    <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success">
      <?php echo $successMessage; ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger">
      <?php echo $errorMessage; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="profile-form">
        <div class="profile-circle">
          <?php 
            // Display the first letter of the first name if available
            if (isset($user['first_name']) && !empty($user['first_name'])) {
              echo strtoupper(substr($user['first_name'], 0, 1));
            } else {
              echo strtoupper(substr($username, 0, 1));
            }
          ?>
        </div>
        <div class="profile-info">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="firstName" value="<?php echo isset($user['first_name']) ? htmlspecialchars($user['first_name']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lastName" value="<?php echo isset($user['last_name']) ? htmlspecialchars($user['last_name']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label>User Name</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="passwordField" value="<?php echo isset($user['password']) ? htmlspecialchars($user['password']) : ''; ?>">
            <i class="bx bx-hide password-toggle-icon" id="togglePassword"></i>
          </div>
          <div class="form-actions">
            <button type="submit" name="save_profile" class="save-btn">SAVE</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='dashboard.php'">CANCEL</button>
          </div>
        </div>
      </div>
    </form>
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
            <button type="submit" class="btn btn-light text-dark fw-bold">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Password visibility toggle
    document.addEventListener('DOMContentLoaded', function() {
      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('passwordField');
      
      togglePassword.addEventListener('click', function() {
        // Toggle password visibility
        if (passwordField.type === 'password') {
          passwordField.type = 'text';
          togglePassword.classList.remove('bx-hide');
          togglePassword.classList.add('bx-show');
        } else {
          passwordField.type = 'password';
          togglePassword.classList.remove('bx-show');
          togglePassword.classList.add('bx-hide');
        }
      });
    });
  </script>
</body>
</html>