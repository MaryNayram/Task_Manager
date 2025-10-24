<?php
session_start();

if (!isset($_SESSION['role'], $_SESSION['id'])) {
	$em = "First login";
	header("Location: ../login.php?error=" . urlencode($em));
	exit();
}

if ($_SESSION['role'] !== 'employee') {
	$em = "Unauthorized access";
	header("Location: ../edit-task-employee.php?error=" . urlencode($em));
	exit();
}

if (isset($_POST['id'], $_POST['status'])) {
	include "../DB_connection.php";

	function validate_input($data) {
		return htmlspecialchars(stripslashes(trim($data)));
	}

	$id = validate_input($_POST['id']);
	$status = validate_input($_POST['status']);

	if (empty($status)) {
		$em = "Status is required";
		header("Location: ../edit-task-employee.php?error=" . urlencode($em) . "&id=" . urlencode($id));
		exit();
	}

	include "Model/Task.php";

	$data = [$status, $id];
	update_task_status($conn, $data);

	$em = "Task updated successfully";
	header("Location: ../edit-task-employee.php?success=" . urlencode($em) . "&id=" . urlencode($id));
	exit();
}

$em = "Unknown error occurred";
header("Location: ../edit-task-employee.php?error=" . urlencode($em));
exit();