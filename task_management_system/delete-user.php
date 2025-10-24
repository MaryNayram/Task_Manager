<?php
session_start();

if (isset($_SESSION['role'], $_SESSION['id']) && $_SESSION['role'] === "admin") {
	include "DB_connection.php";
	include "app/Model/User.php";

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		header("Location: user.php");
		exit();
	}

	$id = (int) $_GET['id'];
	$user = get_user_by_id($conn, $id);

	if ($user === 0) {
		header("Location: user.php");
		exit();
	}

	delete_user($conn, [$id, "employee"]);

	$sm = "Deleted Successfully";
	header("Location: user.php?success=" . urlencode($sm));
	exit();
} else {
	$em = "First login";
	header("Location: login.php?error=" . urlencode($em));
	exit();
}