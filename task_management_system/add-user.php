<?php
session_start();
if (isset($_SESSION['role'], $_SESSION['id']) && $_SESSION['role'] === "admin") {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Add User</title>
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
			<h4 class="title mb-3">Add Users <a href="user.php" class="btn btn-sm btn-outline-secondary ms-2">View All</a></h4>

			<form method="POST" action="app/add-user.php" class="form-1">
				<?php if (isset($_GET['error'])): ?>
					<div class="alert alert-danger"><?= stripcslashes($_GET['error']) ?></div>
				<?php endif; ?>

				<?php if (isset($_GET['success'])): ?>
					<div class="alert alert-success"><?= stripcslashes($_GET['success']) ?></div>
				<?php endif; ?>

				<div class="mb-3">
					<label for="full_name" class="form-label">Full Name</label>
					<input type="text" name="full_name" id="full_name" class="form-control input-1" placeholder="Full Name" required>
				</div>

				<div class="mb-3">
					<label for="user_name" class="form-label">Username</label>
					<input type="text" name="user_name" id="user_name" class="form-control input-1" placeholder="Username" required>
				</div>

				<div class="mb-3">
					<label for="password" class="form-label">Password</label>
					<input type="password" name="password" id="password" class="form-control input-1" placeholder="Password" required>
				</div>

				<button type="submit" class="btn btn-success edit-btn">
					<i class="fas fa-user-plus me-2"></i> Add User
				</button>
			</form>
		</section>
	</div>

	<script>
		document.querySelector("#navList li:nth-child(2)")?.classList.add("active");
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