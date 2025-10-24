<?php
session_start();
if (isset($_SESSION['role'], $_SESSION['id']) && $_SESSION['role'] === "admin") {
	include "DB_connection.php";
	include "app/Model/Task.php";
	include "app/Model/User.php";

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		header("Location: tasks.php");
		exit();
	}

	$id = (int) $_GET['id'];
	$task = get_task_by_id($conn, $id);

	if ($task === 0) {
		header("Location: tasks.php");
		exit();
	}

	$users = get_all_users($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Edit Task</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<style>
		body {
			background: linear-gradient(135deg, #127b8e, #1f2c3e);
			font-family: 'Segoe UI', sans-serif;
			min-height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 20px;
		}
		.task-card {
			background: rgba(255, 255, 255, 0.1);
			backdrop-filter: blur(12px);
			border-radius: 16px;
			padding: 40px 30px;
			width: 100%;
			max-width: 600px;
			box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
			color: #fff;
			animation: fadeIn 0.6s ease-out;
		}
		.task-card h3 {
			text-align: center;
			margin-bottom: 30px;
			font-weight: 700;
			color: #fff;
		}
		.form-control, .form-select {
			border-radius: 10px;
			background-color: rgba(255, 255, 255, 0.2);
			color: #0a0909ff;
			border: none;
		}
		.form-control::placeholder {
			color: #ccc;
		}
		.form-control:focus, .form-select:focus {
			box-shadow: 0 0 0 0.2rem rgba(18, 123, 142, 0.4);
			border-color: #127b8e;
			background-color: rgba(255, 255, 255, 0.3);
		}
		.btn-primary {
			background-color: #00bcd4;
			border: none;
			border-radius: 10px;
			font-weight: 600;
			transition: background 0.3s ease, transform 0.2s ease;
		}
		.btn-primary:hover {
			background-color: #0097a7;
			transform: scale(0.98);
		}
		.alert {
			border-radius: 10px;
			font-size: 14px;
		}
		@keyframes fadeIn {
			from { opacity: 0; transform: translateY(20px); }
			to { opacity: 1; transform: translateY(0); }
		}
	</style>
</head>
<body>

	<form method="POST" action="app/update-task.php" class="task-card">
		<h3><i class="fas fa-edit me-2"></i>Edit Task</h3>

		<?php if (isset($_GET['error'])): ?>
			<div class="alert alert-danger"><?= stripcslashes($_GET['error']) ?></div>
		<?php endif; ?>

		<?php if (isset($_GET['success'])): ?>
			<div class="alert alert-success"><?= stripcslashes($_GET['success']) ?></div>
		<?php endif; ?>

		<div class="mb-3">
			<label for="title" class="form-label">Title</label>
			<input type="text" name="title" id="title" class="form-control" placeholder="Task Title" value="<?= htmlspecialchars($task['title']) ?>" required>
		</div>

		<div class="mb-3">
			<label for="description" class="form-label">Description</label>
			<textarea name="description" id="description" rows="5" class="form-control" placeholder="Task Description"><?= htmlspecialchars($task['description']) ?></textarea>
		</div>

		<div class="mb-3">
			<label for="due_date" class="form-label">Snooze (Due Date)</label>
			<input type="date" name="due_date" id="due_date" class="form-control" value="<?= htmlspecialchars($task['due_date']) ?>">
		</div>

		<div class="mb-3">
			<label for="assigned_to" class="form-label">Assigned To</label>
			<select name="assigned_to" id="assigned_to" class="form-select" required>
				<option value="0">Select employee</option>
				<?php foreach ($users as $user): ?>
					<option value="<?= $user['id'] ?>" <?= $task['assigned_to'] == $user['id'] ? 'selected' : '' ?>>
						<?= htmlspecialchars($user['full_name']) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<input type="hidden" name="id" value="<?= $task['id'] ?>">

		<button type="submit" class="btn btn-primary w-100">
			<i class="fas fa-save me-2"></i>Update Task
		</button>
	</form>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
} else {
	$em = "First login";
	header("Location: login.php?error=" . urlencode($em));
	exit();
}
?>