<?php
session_start();

if (isset($_SESSION['role'], $_SESSION['id']) && $_SESSION['role'] === "admin") {
	include "DB_connection.php";
	include "app/Model/Task.php";
	include "app/Model/User.php";

	$text = "All Tasks";
	$tasks = [];
	$num_task = 0;

	// Fetch all tasks based on due_date filter
	if (isset($_GET['due_date'])) {
		switch ($_GET['due_date']) {
			case "Due Today":
				$text = "Due Today";
				$tasks = get_all_tasks_due_today($conn);
				$num_task = count_tasks_due_today($conn);
				break;
			case "Overdue":
				$text = "Overdue";
				$tasks = get_all_tasks_overdue($conn);
				$num_task = count_tasks_overdue($conn);
				break;
			case "No Deadline":
				$text = "No Deadline";
				$tasks = get_all_tasks_NoDeadline($conn);
				$num_task = count_tasks_NoDeadline($conn);
				break;
			default:
				$tasks = get_all_tasks($conn);
				$num_task = count_tasks($conn);
		}
	} else {
		$tasks = get_all_tasks($conn);
		$num_task = count_tasks($conn);
	}

	$users = get_all_users($conn);

	// Apply additional filters
	if (isset($_GET['assigned_to']) && $_GET['assigned_to'] !== '') {
		$tasks = array_filter($tasks, fn($t) => $t['assigned_to'] == $_GET['assigned_to']);
	}
	if (isset($_GET['status']) && $_GET['status'] !== '') {
		$tasks = array_filter($tasks, fn($t) => strtolower($t['status']) == strtolower($_GET['status']));
	}
	$num_task = count($tasks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>All Tasks</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<link rel="stylesheet" href="css/style.css">
	<style>
		.floating-create-task {
			position: fixed;
			bottom: 30px;
			right: 30px;
			background: #127b8e;
			color: #fff;
			padding: 12px 20px;
			border-radius: 50px;
			font-size: 16px;
			box-shadow: 0 6px 20px rgba(0,0,0,0.2);
			text-decoration: none;
			z-index: 999;
			transition: transform 0.2s ease, background 0.3s ease;
		}
		.floating-create-task i {
			margin-right: 8px;
		}
		.floating-create-task:hover {
			background: #0e5f6f;
			transform: scale(0.98);
		}
	</style>
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php"; ?>
	<div class="body">
		<?php include "inc/nav.php"; ?>
		<section class="section-1">
			<h4 class="title-2 mb-3"><?= htmlspecialchars($text) ?> (<?= $num_task ?>)</h4>

			<!-- Filter Form -->
			<form method="GET" action="tasks.php" class="d-flex flex-wrap gap-2 mb-3">
				<select name="due_date" class="form-select form-select-sm" style="width: 180px;">
					<option value="">All Deadlines</option>
					<option value="Due Today" <?= $_GET['due_date'] == 'Due Today' ? 'selected' : '' ?>>Due Today</option>
					<option value="Overdue" <?= $_GET['due_date'] == 'Overdue' ? 'selected' : '' ?>>Overdue</option>
					<option value="No Deadline" <?= $_GET['due_date'] == 'No Deadline' ? 'selected' : '' ?>>No Deadline</option>
				</select>

				<select name="assigned_to" class="form-select form-select-sm" style="width: 180px;">
					<option value="">All Users</option>
					<?php foreach ($users as $user): ?>
						<option value="<?= $user['id'] ?>" <?= isset($_GET['assigned_to']) && $_GET['assigned_to'] == $user['id'] ? 'selected' : '' ?>>
							<?= htmlspecialchars($user['full_name']) ?>
						</option>
					<?php endforeach; ?>
				</select>

				<select name="status" class="form-select form-select-sm" style="width: 180px;">
					<option value="">All Statuses</option>
					<option value="pending" <?= $_GET['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
					<option value="in progress" <?= $_GET['status'] == 'in progress' ? 'selected' : '' ?>>In Progress</option>
					<option value="completed" <?= $_GET['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
				</select>

				<button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
				<a href="export_tasks.php?format=excel" class="btn btn-sm btn-outline-success">Export to Excel</a>
				<a href="export_tasks.php?format=pdf" class="btn btn-sm btn-outline-danger">Export to PDF</a>
			</form>

			<?php if (isset($_GET['success'])): ?>
				<div class="alert alert-success"><?= stripcslashes($_GET['success']) ?></div>
			<?php endif; ?>

			<?php if (!empty($tasks)): ?>
				<div class="table-responsive">
					<table class="table table-bordered table-hover align-middle shadow-sm">
						<thead class="table-dark">
							<tr>
								<th>#</th>
								<th>Title</th>
								<th>Description</th>
								<th>Assigned To</th>
								<th>Due Date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 0; foreach ($tasks as $task): ?>
							<tr>
								<td><?= ++$i ?></td>
								<td><?= htmlspecialchars($task['title']) ?></td>
								<td><?= htmlspecialchars($task['description']) ?></td>
								<td>
									<?php
										foreach ($users as $user) {
											if ($user['id'] == $task['assigned_to']) {
												echo htmlspecialchars($user['full_name']);
												break;
											}
										}
									?>
								</td>
								<td><?= $task['due_date'] ?: "No Deadline" ?></td>
								<td>
									<span class="badge bg-<?= match(strtolower($task['status'])) {
										'pending' => 'warning',
										'in progress' => 'info',
										'completed' => 'success',
										default => 'secondary'
									} ?> text-capitalize">
										<?= htmlspecialchars($task['status']) ?>
									</span>
								</td>
								<td>
									<a href="edit-task.php?id=<?= urlencode($task['id']) ?>" class="btn btn-sm btn-outline-primary">
										<i class="fas fa-edit"></i> Edit
									</a>
									<a href="delete-task.php?id=<?= urlencode($task['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this task?');">
										<i class="fas fa-trash"></i> Delete
									</a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="alert alert-info">No tasks found for this filter.</div>
			<?php endif; ?>
		</section>
	</div>

	<!-- Floating Create Task Button -->
	<a href="create_task.php" class="floating-create-task">
		<i class="fas fa-plus"></i> Create Task
	</a>

	<script>
		document.querySelector("#navList li:nth-child(4)")?.classList.add("active");
	</script>
</body>
</html>
<?php
} else {
	$em = "First login";
	header("Location: login.php?error=$em");
	exit();
}
?>