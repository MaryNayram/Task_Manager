<?php
session_start();

if (isset($_SESSION['role'], $_SESSION['id']) && $_SESSION['role'] === "admin") {
	include "DB_connection.php";
	include "app/Model/User.php";

	$users = get_all_users($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Create Task</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php"; ?>
	<div class="body">
		<?php include "inc/nav.php"; ?>
		<section class="section-1 p-4">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h4 class="title">Create Task</h4>
				<a href="send_broadcast.php" class="btn btn-primary">
					<i class="fas fa-bullhorn me-2"></i> Broadcast Notification
				</a>
			</div>

			<form method="POST" action="app/add-task.php" class="form-1">
				<?php if (isset($_GET['error'])): ?>
					<div class="alert alert-danger"><?= stripcslashes($_GET['error']) ?></div>
				<?php endif; ?>

				<?php if (isset($_GET['success'])): ?>
					<div class="alert alert-success"><?= stripcslashes($_GET['success']) ?></div>
				<?php endif; ?>

				<div class="mb-3">
					<label for="title" class="form-label">Title</label>
					<input type="text" name="title" id="title" class="form-control" placeholder="Task title" required>
				</div>

				<div class="mb-3">
					<label for="description" class="form-label">Description</label>
					<textarea name="description" id="description" class="form-control" placeholder="Task description" rows="4" required></textarea>
				</div>

				<div class="mb-3">
					<label for="due_date" class="form-label">Due Date</label>
					<input type="date" name="due_date" id="due_date" class="form-control">
				</div>

				<div class="mb-3">
					<label for="assigned_to" class="form-label">Assigned To</label>
					<select name="assigned_to" id="assigned_to" class="form-select" required>
						<option value="0">Select employee</option>
						<?php foreach ($users as $user): ?>
							<option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<button type="submit" class="btn btn-success">
					<i class="fas fa-plus me-2"></i> Create Task
				</button>
			</form>
		</section>
	</div>

	<script>
		document.querySelector("#navList li:nth-child(3)")?.classList.add("active");
	</script>
</body>
</html>
<?php
} else {
	$em = "First login";
	header("Location: login.php?error=" . urlencode($em));
	exit();
}
?>