<?php
session_start();
if (isset($_SESSION['role'], $_SESSION['id']) && $_SESSION['role'] === "employee") {
	include "DB_connection.php";
	include "app/Model/User.php";

	$user = get_user_by_id($conn, $_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Edit Profile</title>
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
		.profile-card {
			background: rgba(255, 255, 255, 0.1);
			backdrop-filter: blur(12px);
			border-radius: 16px;
			padding: 40px 30px;
			width: 100%;
			max-width: 500px;
			box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
			color: #fff;
			animation: fadeIn 0.6s ease-out;
		}
		.profile-card h3 {
			text-align: center;
			margin-bottom: 30px;
			font-weight: 700;
			color: #fff;
		}
		.form-control {
			border-radius: 10px;
			background-color: rgba(255, 255, 255, 0.2);
			color: #fff;
			border: none;
		}
		.form-control::placeholder {
			color: #ccc;
		}
		.form-control:focus {
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

	<form method="POST" action="app/update-profile.php" class="profile-card">
		<h3><i class="fas fa-user-edit me-2"></i>Edit Profile</h3>

		<?php if (isset($_GET['error'])): ?>
			<div class="alert alert-danger"><?= stripcslashes($_GET['error']) ?></div>
		<?php endif; ?>

		<?php if (isset($_GET['success'])): ?>
			<div class="alert alert-success"><?= stripcslashes($_GET['success']) ?></div>
		<?php endif; ?>

		<div class="mb-3">
			<label for="full_name" class="form-label">Full Name</label>
			<input type="text" name="full_name" id="full_name" class="form-control" placeholder="Full Name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
		</div>

		<div class="mb-3">
			<label for="password" class="form-label">Old Password</label>
			<input type="password" name="password" id="password" class="form-control" placeholder="Old Password" required>
		</div>

		<div class="mb-3">
			<label for="new_password" class="form-label">New Password</label>
			<input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password">
		</div>

		<div class="mb-3">
			<label for="confirm_password" class="form-label">Confirm Password</label>
			<input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
		</div>

		<button type="submit" class="btn btn-primary w-100">
			<i class="fas fa-save me-2"></i>Save Changes
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