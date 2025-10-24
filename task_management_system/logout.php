<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Logging Out...</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<style>
		body {
			background: linear-gradient(135deg, #127b8e, #1f2c3e);
			color: #fff;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			font-family: 'Segoe UI', sans-serif;
		}
		.logout-box {
			text-align: center;
			animation: fadeOut 2s ease-in forwards;
		}
		.logout-box i {
			font-size: 60px;
			margin-bottom: 20px;
			color: #00d4ff;
			animation: pulse 1.5s infinite;
		}
		.logout-box h3 {
			font-weight: 600;
			font-size: 24px;
			margin-bottom: 10px;
		}
		.logout-box p {
			font-size: 16px;
			color: #e0f7ff;
		}
		@keyframes pulse {
			0% { transform: scale(1); opacity: 1; }
			50% { transform: scale(1.1); opacity: 0.7; }
			100% { transform: scale(1); opacity: 1; }
		}
		@keyframes fadeOut {
			0% { opacity: 1; }
			100% { opacity: 0; transform: scale(0.95); }
		}
	</style>
</head>
<body>
	<div class="logout-box">
		<i class="fas fa-sign-out-alt"></i>
		<h3>Logging you out...</h3>
		<p>You’ve been logged out successfully. See you soon!</p>
	</div>

	<script>
		setTimeout(() => {
			window.location.href = "login.php";
		}, 2000);
	</script>
</body>
</html>