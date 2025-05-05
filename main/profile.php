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
    }
  </style>
</head>
<body>
      <?php include("sidebar.php") ?>

  <div class="main">
    <h1 class="page-title">Personal Information</h1>
    <div class="profile-form">
      <div class="profile-circle"></div>
      <div class="profile-info">
        <div class="form-group">
          <label>First Name</label>
          <input type="text" value="First Name">
        </div>
        <div class="form-group">
          <label>Last Name</label>
          <input type="text" value="Last Name">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" value="email@gmail.com">
        </div>
        <div class="form-group">
          <label>User Name</label>
          <input type="text" value="<?php echo ($_SESSION['username']) ?>">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" value="password" readonly>
        </div>
        <div class="form-actions">
          <button class="save-btn">SAVE</button>
          <button class="cancel-btn">CANCEL</button>
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
            <button type="submit" class="btn btn-light text-dark fw-bold">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
