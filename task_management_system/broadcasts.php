<?php
session_start();
if (isset($_SESSION['role'], $_SESSION['id']) && $_SESSION['role'] === 'admin') {
	include "DB_connection.php";
	include "app/Model/notification_all.php";

	// Handle filters
	$filter_role = $_GET['role'] ?? '';
	$filter_type = $_GET['type'] ?? '';

	// Fetch broadcasts with optional filters
	$broadcasts = get_all_broadcast_notifications($conn, $filter_role, $filter_type);
	$broadcasts = is_array($broadcasts) ? $broadcasts : [];

	// Sort by date descending
	usort($broadcasts, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Broadcasts</title>
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
				<h4 class="title">All Broadcasts</h4>
				<a href="send_broadcast.php" class="btn btn-primary">
					<i class="fas fa-plus me-2"></i> Create Broadcast
				</a>
			</div>

			<!-- Filter Form -->
			<form method="GET" class="row g-3 mb-4">
				<div class="col-md-4">
					<select name="role" class="form-select">
						<option value="">All Roles</option>
						<option value="employee" <?= $filter_role === 'employee' ? 'selected' : '' ?>>Employee</option>
						<option value="nurse" <?= $filter_role === 'nurse' ? 'selected' : '' ?>>Nurse</option>
						<option value="developer" <?= $filter_role === 'developer' ? 'selected' : '' ?>>Developer</option>
					</select>
				</div>
				<div class="col-md-4">
					<select name="type" class="form-select">
						<option value="">All Types</option>
						<option value="general" <?= $filter_type === 'general' ? 'selected' : '' ?>>General</option>
						<option value="reminder" <?= $filter_type === 'reminder' ? 'selected' : '' ?>>Reminder</option>
						<option value="system" <?= $filter_type === 'system' ? 'selected' : '' ?>>System</option>
					</select>
				</div>
				<div class="col-md-4">
					<button type="submit" class="btn btn-outline-secondary">Filter</button>
				</div>
			</form>

			<?php if (!empty($broadcasts)): ?>
				<div class="table-responsive">
					<table class="table table-bordered table-striped align-middle">
						<thead class="table-dark">
							<tr>
								<th>#</th>
								<th>Message</th>
								<th>Type</th>
								<th>Target Role</th>
								<th>Date</th>
								<th>Read Stats</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 0; foreach ($broadcasts as $broadcast): ?>
							<tr>
								<td><?= ++$i ?></td>
								<td><?= htmlspecialchars($broadcast['message']) ?></td>
								<td><?= htmlspecialchars($broadcast['type']) ?></td>
								<td><?= htmlspecialchars($broadcast['target_role']) ?></td>
								<td><?= htmlspecialchars($broadcast['date']) ?></td>
								<td>
									<?php
										$read_count = get_broadcast_read_count($conn, $broadcast['id']);
										$total_users = get_total_users_by_role($conn, $broadcast['target_role']);
										echo "$read_count of $total_users read";
									?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="alert alert-info">No broadcasts found for this filter.</div>
			<?php endif; ?>
		</section>
	</div>

	<script>
		document.querySelector("#navList li:nth-child(4)")?.classList.add("active");
	</script>
</body>
</html>
<?php
} else {
	header("Location: login.php?error=" . urlencode("First login"));
	exit();
}
?>