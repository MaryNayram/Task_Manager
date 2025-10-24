<?php
session_start(); // ✅ Start session before accessing $_SESSION

include_once "DB_connection.php";
include_once "app/Model/Task.php";

// ✅ Safely fetch tasks only if user is logged in
$tasks = [];
if (isset($_SESSION['id']) && function_exists('get_all_tasks_by_id')) {
    $tasks = get_all_tasks_by_id($conn, $_SESSION['id']);
    $tasks = is_array($tasks) ? $tasks : [];
}
?>

<!-- ✅ Include Bootstrap and Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- ✅ Optional: Add this CSS block -->
<style>
	.fade-in {
		animation: fadeIn 0.6s ease-in-out;
	}
	@keyframes fadeIn {
		from { opacity: 0; transform: translateY(10px); }
		to { opacity: 1; transform: translateY(0); }
	}
	.table tbody tr:hover {
		background-color: #eef9fc;
	}
	.btn-outline-primary:hover {
		background-color: #127b8e;
		color: #fff;
		transform: scale(0.97);
	}
</style>

<?php if (!empty($tasks)): ?>
<div class="table-responsive mt-4">
	<table class="table table-bordered table-hover align-middle shadow-sm">
		<thead class="table-dark">
			<tr>
				<th>#</th>
				<th>Title</th>
				<th>Description</th>
				<th>Status</th>
				<th>Due Date</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 0; foreach ($tasks as $task): ?>
			<tr class="fade-in">
				<td><?= ++$i ?></td>
				<td><?= $task['title'] ?></td>
				<td><?= $task['description'] ?></td>
				<td>
					<?php
						$status = strtolower($task['status']);
						$badge = match($status) {
							'pending' => 'warning',
							'in progress' => 'info',
							'completed' => 'success',
							default => 'secondary'
						};
					?>
					<span class="badge bg-<?= $badge ?> text-capitalize"><?= $task['status'] ?></span>
				</td>
				<td><?= $task['due_date'] ?></td>
				<td>
					<a href="edit-task-employee.php?id=<?= urlencode($task['id']) ?>" class="btn btn-sm btn-outline-primary">
						<i class="fas fa-edit"></i> Edit
					</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
	<div class="alert alert-info mt-4">You have no tasks assigned yet.</div>
<?php endif; ?>