<div class="side">
  <p class="title">Inventory System</p>
  <div class="circle">
    <?php
      function getInitials($name) {
          $parts = explode(" ", trim($name));
          $initials = '';
          foreach ($parts as $part) {
              $initials .= strtoupper($part[0]);
          }
          return substr($initials, 0, 2);
      }
      echo isset($_SESSION['username']) ? getInitials($_SESSION['username']) : 'AD';
    ?>
  </div>
  <p class="name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></p>
  <div class="role">ADMIN</div>

  <div class="nav-anchor">
    <a href="dashboard.php"><i class='bx bxs-dashboard'></i> Dashboard</a>
    <a href="profile.php"><i class='bx bxs-user'></i> Profile</a>
    <a href="items.php"><i class='bx bxs-box'></i> Items</a>
    <a href="accounts.php"><i class='bx bxs-user-account'></i> Accounts</a>
  </div>

  <div class="logout-container">
    <form action="logout.php" method="post">
      <button type="submit" class="logout-btn">
        <i class='bx bx-log-out'></i> Logout
      </button>
    </form>
  </div>
</div>
