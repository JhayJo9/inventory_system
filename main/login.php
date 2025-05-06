<?php
session_start();

// IMPORT THE NEEDED FILES TO ACCESS
include("../config.php");

// Define user statuses
define('STATUS_ACTIVE', 'Active');
define('STATUS_INACTIVE', 'Inactive');

$message = '';
$message_type = '';

if (isset($_POST["LOGIN"])) {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $stmt = $conn->prepare("
            SELECT users.*, roles.role_name 
            FROM users 
            JOIN roles ON users.role_id = roles.role_id
            WHERE username = ?
        ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Check if user account is active
            if ($user['status'] !== 'Active') {
                $message = 'Your account is inactive. Please contact an administrator.';
                $message_type = 'warning';
            }
            // Plain-text comparison (TEMPORARY)
            else if ($password === $user['password']) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role_name'];

                $message = 'Login successful! Redirecting...';
                $message_type = 'success';
                
                // Redirect after showing message
                header("refresh:2;url=dashboard.php");
            } else {
                $message = 'Incorrect password';
                $message_type = 'danger';
            }
        } else {
            $message = 'User not found';
            $message_type = 'danger';
        }
    } else {
        $message = 'Please fill in all fields';
        $message_type = 'warning';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,700" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(to bottom right, #2e3440, #1e2e2e);
    }
    .w100 { font-weight: 100; }
    .w200 { font-weight: 200; }
    .w300 { font-weight: 300; }
    .w400 { font-weight: 400; }
    
    .login-container {
      background-color: #4E5758;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.1);
      padding: 2rem;
      margin: 0 auto;
    }
    
    .login-title {
      color: white;
      font-size: 36px;
      font-weight: 700;
      margin-bottom: 1.5rem;
    }
    
    .form-control {
      background-color: #ccc;
      border: none;
      border-radius: 25px;
      padding: 12px 20px;
      margin-bottom: 1rem;
    }
    
    .btn-login {
      background-color: #FFFFFF;
      color: black;
      font-weight: bold;
      border-radius: 25px;
      padding: 10px 20px;
      font-size: 18px;
      transition: all 0.3s ease;
      width: 100%;
    }
    
    .btn-login:hover {
      background-color: #ddd;
    }
    
    .alert {
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>
  <div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
      <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="login-container">
          
          <?php if(!empty($message)): ?>
          <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php endif; ?>
          
          <h1 class="login-title text-center">USER LOGIN</h1>
          
          <form id="loginForm" action="login.php" method="post">
            <div class="mb-3">
              <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="d-grid gap-2 mt-4">
              <button type="submit" name="LOGIN" class="btn btn-login">LOGIN</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>