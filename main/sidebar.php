<?php
// Start session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once('check_session.php');

function getInitials($name) {
    $parts = explode(" ", trim($name));
    $initials = '';
    foreach ($parts as $part) {
        $initials .= strtoupper($part[0]);
    }
    return substr($initials, 0, 2);
}
?>
<div class="side">
    <p class="title">Inventory System</p>
    <div class="circle"><?php echo getInitials($_SESSION['username'] ?? 'Admin'); ?></div>
    <p class="name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></p>
    <div class="role"><?php echo $_SESSION['role'] ?? 'ADMIN'; ?></div>

    <div class="nav-anchor">
        <a href="dashboard.php"><i class='bx bxs-dashboard'></i> Dashboard</a>
        <a href="profile.php"><i class='bx bxs-user'></i> Profile</a>
        <a href="items.php"><i class='bx bxs-box'></i> Items</a>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
          <a href="category.php"><i class='bx bxs-category'></i> Category</a>
        <?php endif; ?>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
            <a href="account.php"><i class='bx bxs-user-account'></i> Accounts</a>
        <?php endif; ?>
    </div>

    <div class="logout-container">
         
          <button type="submit" name="logout" value="submit" class="logout-btn" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class='bx bx-log-out'></i> Logout
          </button>
         
  </div>
</div>