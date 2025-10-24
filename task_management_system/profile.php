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
    <title>My Profile | GuardianCare 360</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1abc9c, #0f2027);
            font-family: 'Segoe UI', sans-serif;
            color: #1b1919ff;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .profile-container {
            max-width: 500px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.8s ease-out;
        }
        .profile-container h4 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .profile-container h4 a {
            font-size: 14px;
            color: #00d4ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .profile-container h4 a:hover {
            color: #ffffff;
        }
        .profile-table {
            width: 100%;
            border-collapse: collapse;
        }
        .profile-table td {
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 15px;
        }
        .profile-table td:first-child {
            font-weight: 600;
            color: #00d4ff;
            width: 40%;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php"; ?>
    <div class="body">
        <?php include "inc/nav.php"; ?>
        <section class="section-1">
            <div class="profile-container">
                <h4>Profile <a href="edit_profile.php"><i class="fas fa-edit"></i> Edit</a></h4>
                <table class="profile-table">
                    <tr>
                        <td>Full Name</td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                    </tr>
                    <tr>
                        <td>Joined At</td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                </table>
            </div>
        </section>
    </div>

    <script>
        document.querySelector("#navList li:nth-child(3)")?.classList.add("active");
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