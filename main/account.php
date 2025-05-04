<?php
session_start();

// IMPORT THE NEEDED FILES TO ACCESS
include("restrictAccess.php");
include("../config.php");

// IDENTIFYING THE USER WHO CAN ACCESS THIS PAGE
restrictAccess(['admin']);


if (!isset($_SESSION['username'])) {
    header("Location: /system/login.php");
    exit;
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Accounts</title>
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
            box-sizing: border-box;
            padding: 0px;
            margin: 0px;
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
        .wrap-search {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2rem;
        }
        .wrap-search input {
            padding-left: 10px;
            width: 220px;
            height: 40px;
            background-color: #D9D9D9;
            border: none;
            border-radius: 8px;
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
<>
      <?php include('sidebar.php') ?>

    <div class="main">
        <h1 class="welcome">Accounts</h1>
        <p class="subtitle">You can edit, update, and delete accounts here</p>

        <div class="wrap-search">
            <input type="text" placeholder="Search">
        </div>

        <div class="stats-container">
            <!-- Replace the stat-card content dynamically with your database queries -->
            <div class="stat-card">
                <div class="stat-title">TOTAL ACCOUNTS</div>
                <div class="stat-value">0</div> <!-- Replace with dynamic value -->
            </div>
            <div class="stat-card">
                <div class="stat-title">ACTIVE ACCOUNTS</div>
                <div class="stat-value">0</div> <!-- Replace with dynamic value -->
            </div>
            <div class="stat-card">
                <div class="stat-title">SUSPENDED ACCOUNTS</div>
                <div class="stat-value">0</div> <!-- Replace with dynamic value -->
            </div>
            <div class="stat-card">
                <div class="stat-title">PENDING ACCOUNTS</div>
                <div class="stat-value">0</div> <!-- Replace with dynamic value -->
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>
