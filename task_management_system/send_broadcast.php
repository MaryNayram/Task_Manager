<?php
session_start();

// ✅ Secure access: only admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// ✅ Connect to database
include "DB_connection.php";

// ✅ Optional: use model function instead of raw SQL
// include "app/Model/notification_all.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Send Broadcast Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>Broadcast Notification to All Employees</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="send_broadcast.php">
            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="general">General</option>
                    <option value="reminder">Reminder</option>
                    <option value="system">System</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Send Notification</button>
        </form>
    </div>
</body>
</html>

<?php
// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    $type = $_POST['type'] ?? 'general';
    $created_by = $_SESSION['id'] ?? null;
    $date = date('Y-m-d');

    if (!empty($message)) {
        // ✅ Use prepared statement safely
        $sql = "INSERT INTO notification_all (message, type, date, target_role, created_by, is_active)
                VALUES (?, ?, ?, 'employee', ?, TRUE)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$message, $type, $date, $created_by]);

        header("Location: send_broadcast.php?success=Notification sent");
        exit();
    } else {
        header("Location: send_broadcast.php?error=Message cannot be empty");
        exit();
    }
}
?>