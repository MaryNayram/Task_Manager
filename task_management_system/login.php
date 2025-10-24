<?php
$name = "Mary"; // Replace with dynamic name logic if available
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | Task Management System</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<style>
		body {
			background: linear-gradient(135deg, #127b8e, #1f2c3e);
			min-height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
			font-family: 'Segoe UI', sans-serif;
		}
		.login-card {
			background: rgba(255, 255, 255, 0.95);
			border-radius: 12px;
			padding: 40px 30px;
			width: 100%;
			max-width: 420px;
			box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
			animation: fadeIn 0.6s ease-out;
		}
		.login-card h3 {
			font-weight: 700;
			color: #127b8e;
			margin-bottom: 10px;
			text-align: center;
		}
		.login-message {
			text-align: center;
			font-size: 16px;
			color: #444;
			margin-bottom: 20px;
		}
		.login-card .form-control {
			border-radius: 8px;
			transition: box-shadow 0.3s ease;
		}
		.login-card .form-control:focus {
			box-shadow: 0 0 0 0.2rem rgba(18, 123, 142, 0.25);
			border-color: #127b8e;
		}
		.login-card button {
			background-color: #127b8e;
			border: none;
			border-radius: 8px;
			padding: 10px;
			width: 100%;
			font-weight: 600;
			transition: background 0.3s ease, transform 0.2s ease;
		}
		.login-card button:hover {
			background-color: #0e5f6f;
			transform: scale(0.98);
		}
		.login-card .form-check {
			margin-bottom: 15px;
		}
		.login-card .forgot-link {
			display: block;
			text-align: right;
			font-size: 14px;
			color: #127b8e;
			text-decoration: none;
			margin-bottom: 15px;
		}
		.login-card .forgot-link:hover {
			text-decoration: underline;
		}
		@keyframes fadeIn {
			from { opacity: 0; transform: translateY(20px); }
			to { opacity: 1; transform: translateY(0); }
		}
	</style>
</head>
<body>

	<form method="POST" action="app/login.php" class="login-card">
		<h3><i class="fas fa-lock me-2"></i>Login</h3>

		<div class="login-message">
			Welcome back, <?php echo htmlspecialchars($name); ?>! Please log in to continue.
		</div>

		<?php if (isset($_GET['error'])): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo stripcslashes($_GET['error']); ?>
			</div>
		<?php endif; ?>

		<?php if (isset($_GET['success'])): ?>
			<div class="alert alert-success" role="alert">
				<?php echo stripcslashes($_GET['success']); ?>
			</div>
		<?php endif; ?>

		<div class="mb-3">
			<label for="user_name" class="form-label">Username</label>
			<input type="text" class="form-control" name="user_name" id="user_name" required autofocus autocomplete="username">
		</div>

		<div class="mb-3">
			<label for="password" class="form-label">Password</label>
			<input type="password" class="form-control" name="password" id="password" required autocomplete="current-password">
		</div>

		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="remember" id="remember">
			<label class="form-check-label" for="remember">Remember me</label>
		</div>

		<a href="forgot_password.php" class="forgot-link">Forgot password?</a>

		<button type="submit" class="btn btn-primary">
			<i class="fas fa-sign-in-alt me-2"></i>Login
		</button>
	</form>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>