<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "DB_connection.php";
	include "app/Model/Notification.php";
	include "app/Model/notification_all.php";

	// Fetch notifications
	$individual_notifications = get_all_my_notifications($conn, $_SESSION['id']);
	$broadcast_notifications = get_broadcast_notifications($conn, $_SESSION['role']);

	$notifications = array_merge(
		is_array($individual_notifications) ? $individual_notifications : [],
		is_array($broadcast_notifications) ? $broadcast_notifications : []
	);

	// Filter logic
	$filter = $_GET['filter'] ?? '';
	if ($filter === 'broadcast') {
		$notifications = array_filter($notifications, fn($n) => !isset($n['user_id']));
	} elseif ($filter === 'personal') {
		$notifications = array_filter($notifications, fn($n) => isset($n['user_id']));
	}

	// Sort by date descending
	usort($notifications, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

	// Pagination logic
	$perPage = 10;
	$page = max(1, intval($_GET['page'] ?? 1));
	$total = count($notifications);
	$start = ($page - 1) * $perPage;
	$notifications = array_slice($notifications, $start, $perPage);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Notifications</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<style>
		.badge {
			padding: 6px 10px;
			font-size: 13px;
			border-radius: 6px;
			display: inline-block;
		}
		.main-table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}
		.main-table th, .main-table td {
			padding: 12px;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}
		.main-table tr:hover {
			background-color: #f9f9f9;
		}
		.filter-form {
			margin-bottom: 20px;
		}
	</style>
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php"; ?>
	<div class="body">
		<?php include "inc/nav.php"; ?>
		<section class="section-1 p-4">
			<h4 class="title mb-3">All Notifications</h4>

			<?php if (isset($_GET['success'])): ?>
				<div class="alert alert-success" role="alert">
					<?php echo stripcslashes($_GET['success']); ?>
				</div>
			<?php endif; ?>

			<form method="GET" class="filter-form">
				<select name="filter" class="form-select w-auto d-inline-block">
					<option value="">All Notifications</option>
					<option value="broadcast" <?= $filter === 'broadcast' ? 'selected' : '' ?>>Broadcast Only</option>
					<option value="personal" <?= $filter === 'personal' ? 'selected' : '' ?>>Personal Only</option>
				</select>
				<button type="submit" class="btn btn-primary ms-2">Filter</button>
			</form>

			<?php if (!empty($notifications)) { ?>
			<table class="main-table table table-bordered table-hover align-middle shadow-sm">
				<thead class="table-dark">
					<tr>
						<th>#</th>
						<th>Message</th>
						<th>Type</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $start; foreach ($notifications as $notification): ?>
					<tr>
						<td><?= ++$i ?></td>
						<td>
							<?= htmlspecialchars($notification['message']) ?>
							<?php if (empty($notification['read'])): ?>
								<a href="mark-read.php?id=<?= urlencode($notification['id']) ?>" class="btn btn-sm btn-outline-success ms-2">
									<i class="fa fa-check"></i> Mark as read
								</a>
							<?php endif; ?>
						</td>
						<td>
							<?php
								$type = htmlspecialchars($notification['type']);
								$isBroadcast = !isset($notification['user_id']);
								$badgeClass = $isBroadcast ? 'bg-info' : 'bg-secondary';
							?>
							<span class="badge <?= $badgeClass ?>">
								<i class="fa <?= $isBroadcast ? 'fa-bullhorn' : 'fa-user' ?>"></i> <?= $type ?>
							</span>
						</td>
						<td><?= date("M j, Y g:i A", strtotime($notification['date'])) ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<?php if ($total > $perPage): ?>
			<nav>
				<ul class="pagination mt-3">
					<?php for ($p = 1; $p <= ceil($total / $perPage); $p++): ?>
						<li class="page-item <?= $p === $page ? 'active' : '' ?>">
							<a class="page-link" href="?page=<?= $p ?>&filter=<?= urlencode($filter) ?>"><?= $p ?></a>
						</li>
					<?php endfor; ?>
				</ul>
			</nav>
			<?php endif; ?>

			<?php } else { ?>
				<div class="alert alert-info">You have zero notifications.</div>
			<?php } ?>
		</section>
	</div>

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