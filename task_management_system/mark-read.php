<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php?error=Please login first");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: notifications.php?error=Invalid notification ID");
    exit();
}

include "DB_connection.php";

// Sanitize and prepare
$notificationId = intval($_GET['id']);
$userId = $_SESSION['id'];

try {
    $sql = "UPDATE notifications SET `read` = 1 WHERE id = :id AND (user_id = :user_id OR user_id IS NULL)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $notificationId,
        ':user_id' => $userId
    ]);

    $msg = "Notification marked as read";
    header("Location: notifications.php?success=" . urlencode($msg));
    exit();
} catch (PDOException $e) {
    $msg = "Failed to mark notification";
    header("Location: notifications.php?error=" . urlencode($msg));
    exit();
}
?>