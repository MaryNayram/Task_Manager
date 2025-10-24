<?php
session_start();

if (isset($_SESSION['role'], $_SESSION['id']) && $_SESSION['role'] === "admin") {
	include "DB_connection.php";
	include "app/Model/Task.php";

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

	delete_task($conn, [$id]);

	$sm = "Deleted Successfully";
	header("Location: tasks.php?success=" . urlencode($sm));
	exit();
} else {
	$em = "First login";
	header("Location: login.php?error=" . urlencode($em));
	exit();
}