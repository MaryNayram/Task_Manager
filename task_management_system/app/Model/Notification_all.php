<?php
// app/Model/notification_all.php

/**
 * Fetch all active broadcast notifications for a specific role (e.g., 'employee').
 *
 * @param PDO $conn - The database connection
 * @param string $role - The target role to filter messages (default: 'employee')
 * @return array - List of broadcast notifications
 */
function get_broadcast_notifications($conn, $role = 'employee') {
    $sql = "SELECT * FROM notification_all 
            WHERE target_role = ? AND is_active = TRUE 
            ORDER BY date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$role]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Send a new broadcast notification to all users of a specific role.
 *
 * @param PDO $conn
 * @param string $message
 * @param string $type
 * @param string $role
 * @param int|null $created_by
 * @return bool
 */
function send_broadcast_notification($conn, $message, $type = 'general', $role = 'employee', $created_by = null) {
    $date = date('Y-m-d');

    $sql = "INSERT INTO notification_all (message, type, date, target_role, created_by, is_active)
            VALUES (?, ?, ?, ?, ?, TRUE)";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$message, $type, $date, $role, $created_by]);
}

/**
 * Fetch all broadcast notifications with optional filters (for admin view).
 *
 * @param PDO $conn
 * @param string $role
 * @param string $type
 * @return array
 */
function get_all_broadcast_notifications($conn, $role = '', $type = '') {
    $sql = "SELECT * FROM notification_all WHERE 1=1";
    $params = [];

    if (!empty($role)) {
        $sql .= " AND target_role = ?";
        $params[] = $role;
    }
    if (!empty($type)) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }

    $sql .= " ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Count how many users have read a specific broadcast.
 *
 * @param PDO $conn
 * @param int $broadcast_id
 * @return int
 */
function get_broadcast_read_count($conn, $broadcast_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM notification_read WHERE broadcast_id = ?");
    $stmt->execute([$broadcast_id]);
    return (int) $stmt->fetchColumn();
}

/**
 * Count total users by role (for read stats).
 *
 * @param PDO $conn
 * @param string $role
 * @return int
 */
function get_total_users_by_role($conn, $role) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
    $stmt->execute([$role]);
    return (int) $stmt->fetchColumn();
}