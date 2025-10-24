<?php
session_start();
if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    header("Location: login.php?error=" . urlencode("First login"));
    exit();
}

include "DB_connection.php";
include "app/Model/Task.php";
include "app/Model/User.php";

// Initialize all variables to avoid warnings
$num_users = $num_task = $todaydue_task = $overdue_task = $nodeadline_task = $pending = $in_progress = $completed = $num_my_task = 0;

if ($_SESSION['role'] === "admin") {
    $num_users        = count_users($conn);
    $num_task         = count_tasks($conn);
    $todaydue_task    = count_tasks_due_today($conn);
    $overdue_task     = count_tasks_overdue($conn);
    $nodeadline_task  = count_tasks_NoDeadline($conn); // ✅ fixed name
    $pending          = count_pending_tasks($conn);
    $in_progress      = count_in_progress_tasks($conn);
    $completed        = count_completed_tasks($conn);
} else {
    $num_my_task      = count_my_tasks($conn, $_SESSION['id']);
    $overdue_task     = count_my_tasks_overdue($conn, $_SESSION['id']);
    $nodeadline_task  = count_my_tasks_NoDeadline($conn, $_SESSION['id']); // ✅ fixed name
    $pending          = count_my_pending_tasks($conn, $_SESSION['id']);
    $in_progress      = count_my_in_progress_tasks($conn, $_SESSION['id']);
    $completed        = count_my_completed_tasks($conn, $_SESSION['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GuardianCare 360 | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1abc9c, #0f2027) !important;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
            min-height: 100vh;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 25px 15px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            color: #10f7d8ff;
            cursor: pointer;
            text-decoration: none;
            display: block;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
            background-color: rgba(255, 255, 255, 0.12);
        }
        .dashboard-card i {
            font-size: 28px;
            margin-bottom: 10px;
            color: #00d4ff;
        }
        .dashboard-card h6 {
            margin: 8px 0 4px;
            font-size: 15px;
            font-weight: 600;
        }
        .dashboard-card span {
            display: block;
            font-size: 16px;
            font-weight: 500;
            color: #fff;
        }
        .floating-broadcast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #00d4ff;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 14px 20px;
            font-size: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            cursor: pointer;
            z-index: 999;
            transition: transform 0.2s ease, background 0.3s ease;
        }
        .floating-broadcast i {
            margin-right: 6px;
        }
        .floating-broadcast:hover {
            background: #0097a7;
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php"; ?>
    <div class="body">
        <?php include "inc/nav.php"; ?>
        <section class="section-1 p-4">
           <h4 class="mb-4">Welcome to Your Dashboard</h4>
<div class="dashboard-grid">
    <?php if ($_SESSION['role'] === "admin") { ?>
        <a href="user.php" class="dashboard-card"><i class="fas fa-users"></i><h6>Employees</h6><span><?= $num_users ?> Total</span></a>
        <a href="tasks.php" class="dashboard-card"><i class="fas fa-tasks"></i><h6>All Tasks</h6><span><?= $num_task ?> Tasks</span></a>
        <a href="tasks.php?filter=overdue" class="dashboard-card"><i class="fas fa-calendar-times"></i><h6>Overdue</h6><span><?= $overdue_task ?> Tasks</span></a>
        <a href="tasks.php?filter=no_deadline" class="dashboard-card"><i class="fas fa-calendar-minus"></i><h6>No Deadline</h6><span><?= $nodeadline_task ?> Tasks</span></a>
        <a href="tasks.php?filter=today" class="dashboard-card"><i class="fas fa-calendar-day"></i><h6>Due Today</h6><span><?= $todaydue_task ?> Tasks</span></a>
        <a href="notifications.php" class="dashboard-card"><i class="fas fa-bell"></i><h6>Notifications</h6><span><?= $overdue_task ?> Alerts</span></a>
        <a href="tasks.php?filter=pending" class="dashboard-card"><i class="fas fa-hourglass-start"></i><h6>Pending</h6><span><?= $pending ?> Tasks</span></a>
        <a href="tasks.php?filter=in_progress" class="dashboard-card"><i class="fas fa-spinner"></i><h6>In Progress</h6><span><?= $in_progress ?> Tasks</span></a>
        <a href="tasks.php?filter=completed" class="dashboard-card"><i class="fas fa-check-circle"></i><h6>Completed</h6><span><?= $completed ?> Tasks</span></a>
    <?php } else { ?>
        <a href="my_task.php" class="dashboard-card"><i class="fas fa-tasks"></i><h6>My Tasks</h6><span><?= $num_my_task ?> Total</span></a>
        <a href="my_task.php?filter=overdue" class="dashboard-card"><i class="fas fa-calendar-times"></i><h6>Overdue</h6><span><?= $overdue_task ?> Tasks</span></a>
        <a href="my_task.php?filter=no_deadline" class="dashboard-card"><i class="fas fa-calendar-minus"></i><h6>No Deadline</h6><span><?= $nodeadline_task ?> Tasks</span></a>
        <a href="my_task.php?filter=pending" class="dashboard-card"><i class="fas fa-hourglass-start"></i><h6>Pending</h6><span><?= $pending ?> Tasks</span></a>
        <a href="my_task.php?filter=in_progress" class="dashboard-card"><i class="fas fa-spinner"></i><h6>In Progress</h6><span><?= $in_progress ?> Tasks</span></a>
        <a href="my_task.php?filter=completed" class="dashboard-card"><i class="fas fa-check-circle"></i><h6>Completed</h6><span><?= $completed ?> Tasks</span></a>
    <?php } ?>
</div>
</section>
</div>

<?php if ($_SESSION['role'] === "admin") { ?>
    <a href="send_broadcast.php" class="floating-broadcast">
        <i class="fas fa-bullhorn"></i> Broadcast
    </a>
<?php } ?>

<script>
    document.querySelector("#navList li:nth-child(1)")?.classList.add("active");
</script>
</body>
</html>