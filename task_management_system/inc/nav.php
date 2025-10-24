<nav class="side-bar" style="width: 240px; background: rgba(0,0,0,0.6); backdrop-filter: blur(10px); color: #fff; min-height: 100vh; padding-top: 20px; box-shadow: 0 0 12px rgba(0,0,0,0.3);">
  <div class="user-p text-center mb-4">
    <img src="img/user.png" alt="User" style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid #00d4ff;">
    <h4 class="mt-2" style="font-weight: 500;">@<?= htmlspecialchars($_SESSION['username']) ?></h4>
  </div>

  <ul id="navList" class="list-unstyled px-3">
    <?php if ($_SESSION['role'] === "employee") { ?>
      <!-- Employee Navigation -->
      <li><a href="index.php" class="nav-link"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
      <li><a href="my_task.php" class="nav-link"><i class="fa fa-tasks"></i> <span>My Task</span></a></li>
      <li><a href="profile.php" class="nav-link"><i class="fa fa-user"></i> <span>Profile</span></a></li>
      <li><a href="notifications.php" class="nav-link"><i class="fa fa-bell"></i> <span>Notifications</span></a></li>
      <li><a href="logout.php" class="nav-link"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
    <?php } else { ?>
      <!-- Admin Navigation -->
      <li><a href="index.php" class="nav-link"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
      <li><a href="user.php" class="nav-link"><i class="fa fa-users"></i> <span>Manage Users</span></a></li>
      <li><a href="create_task.php" class="nav-link"><i class="fa fa-plus"></i> <span>Create Task</span></a></li>
      <li><a href="tasks.php" class="nav-link"><i class="fa fa-tasks"></i> <span>All Tasks</span></a></li>
      <li><a href="logout.php" class="nav-link"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
    <?php } ?>
  </ul>
</nav>