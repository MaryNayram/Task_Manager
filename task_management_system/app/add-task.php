<?php
session_start();

if (!isset($_SESSION['role'], $_SESSION['id'])) {
	$em = "First login";
	header("Location: ../create_task.php?error=" . urlencode($em));
	exit();
}

if ($_SESSION['role'] !== 'admin') {
	$em = "Unauthorized access";
	header("Location: ../create_task.php?error=" . urlencode($em));
	exit();
}

if (
	isset($_POST['title'], $_POST['description'], $_POST['assigned_to'], $_POST['due_date'])
) {
	include "../DB_connection.php";

	function validate_input($data) {
		return htmlspecialchars(stripslashes(trim($data)));
	}

	$title = validate_input($_POST['title']);
	$description = validate_input($_POST['description']);
	$assigned_to = validate_input($_POST['assigned_to']);
	$due_date = validate_input($_POST['due_date']);

	if (empty($title)) {
		$em = "Title is required";
		header("Location: ../create_task.php?error=" . urlencode($em));
		exit();
	}
	if (empty($description)) {
		$em = "Description is required";
		header("Location: ../create_task.php?error=" . urlencode($em));
		exit();
	}
	if ($assigned_to == 0) {
		$em = "Select User";
		header("Location: ../create_task.php?error=" . urlencode($em));
		exit();
	}

	include "Model/Task.php";
	include "Model/Notification.php";

	$data = [$title, $description, $assigned_to, $due_date];
	insert_task($conn, $data);

	$message = "$title has been assigned to you. Please review and start working on it";
	$notif_data = [$message, $assigned_to, 'New Task Assigned'];
	insert_notification($conn, $notif_data);

	$em = "Task created successfully";
	header("Location: ../create_task.php?success=" . urlencode($em));
	exit();
}

$em = "Unknown error occurred";
header("Location: ../create_task.php?error=" . urlencode($em));
exit();