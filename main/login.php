
<?php
session_start();

// IMPORT THE NEEDED FILES TO ACCESS
include("../config.php");

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

        echo "<script>alert('FDGDF');</script>";

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Plain-text comparison (TEMPORARY)
            if ($password === $user['password']) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role_name'];

                header("Location: dashboard.php");
                echo "<script>alert('Success');</script>";
                exit;
            } else {
                echo "<script>alert('Incorrect password');</script>";
                header("Location: login.php");
            }
        } else {
            echo "<script>alert('User not found');</script>";
            header("Location: login.php");
        }
    } else {
        //echo "<script>alert('Please fill in all fields');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,700" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Montserrat';
      background: linear-gradient(to bottom right, #2e3440, #1e2e2e);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    * {
      font-family: 'Montserrat', sans-serif;
    }
    .w100 {
      font-weight: 100;
    }
    .w200 {
      font-weight: 200;
    }
    .w300 {
      font-weight: 300;
    }
    .w400 {
      font-weight: 400;
    }
    .login-container {
      background-color: #4E5758;
      width: 400px;
      height: 450px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      text-align: center;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.1);
    }
    .title{
      margin-top: 6rem;
      color: white;
      font-size: 40px;
      font-weight: 700;
    }
    h2 {
      color: white;
      margin-bottom: 30px;
    }
    .usernameInput, .passwordInput {
      width: 220px;
      height: 18px;
      padding: 12px 5px;
      margin: 10px 0;
      border: none;
      border-radius: 25px;
      background-color: #ccc;
    }
    .login {
      margin-top: 2rem;
      width: 220px;
      height: 38px;
      color: black;
      font-size: 25px;
      border: none;
      border-radius: 25px;
      background-color: #FFFFFF;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    button:hover {
      background-color: #ddd;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <form id="loginForm" action="login.php" method="post">      
      <p class="title">USER LOGIN</p>
      <input class="usernameInput" type="text" name="username" placeholder="username" required>
      <input class="passwordInput" type="password" name="password" placeholder="password" required>
      <input class="login" type="submit" name="LOGIN" value="LOGIN">
    </form>
  </div>
  
  <script>
    
  </script>
</body>
</html>