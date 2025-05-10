<?php 
session_start();

// IMPORT REQUIRED CONNECTION AND ACCESS CONTROL FILES
require_once('check_session.php');
include("../config.php");
include("restrictAccess.php");

// RESTRICT ACCESS TO ADMIN AND STAFF ONLY
restrictAccess(['Admin', 'Staff']);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// GET NEXT AVAILABLE CATEGORY ID
$sql = "SELECT IFNULL(MAX(category_id), 0) + 1 AS maxCategoryId FROM category";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nextCategoryId = $row['maxCategoryId'];
} else {
    $nextCategoryId = 1;
}

// PROCESS FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['Category_name']);
    
    if (empty($categoryName)) {
        $_SESSION['error_message'] = "Category name cannot be empty";
        header("Location: save_category.php");
        exit;
    }
    
    // CHECK IF CATEGORY NAME ALREADY EXISTS
    $checkSql = "SELECT * FROM category WHERE category_name = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $categoryName);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $_SESSION['error_message'] = "Category name already exists. Please choose a different name.";
        header("Location: save_category.php");
        exit;
    }
    
    // INSERT NEW CATEGORY INTO DATABASE
    $insertSql = "INSERT INTO category (category_name) VALUES (?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("s", $categoryName);
    
    if ($insertStmt->execute()) {
        $_SESSION['success_message'] = "Category added successfully";
        header("Location: category.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Error adding category: " . $conn->error;
        header("Location: save_category.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <?php include('sidebar.php'); ?>
    <div class="main">
        <h1 class="welcome">Add Category</h1>
        <p class="subtitle">You can add category here</p>

        <!-- Display error/success messages -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div style="max-width: 600px; background-color: #D9D9D9; padding: 2rem; border-radius: 15px;">
            <form action="save_category.php" method="post">
                <div class="row mb-3">
                    <div class="col">
                        <label>Category ID</label>
                        <input type="text" readonly class="form-control" value="<?php echo htmlspecialchars($nextCategoryId); ?>">
                        <!-- Hidden input to store the next category ID if needed -->
                        <input type="hidden" name="next_category_id" value="<?php echo htmlspecialchars($nextCategoryId); ?>">
                    </div>
                    <div class="col">
                        <label>Category Name</label>
                        <input type="text" name="Category_name" class="form-control" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark me-2">Add</button>
                    <a href="category.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>