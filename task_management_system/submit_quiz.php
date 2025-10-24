<?php
session_start();
include "DB_connection.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$score = 0;
$answers = [];

foreach ($_POST as $key => $selected_option) {
    if (preg_match('/^q\d+$/', $key) && is_numeric($selected_option)) {
        $question_id = (int) substr($key, 1);

        // Validate that the selected option belongs to the question
        $stmt = $conn->prepare("SELECT is_correct FROM options WHERE id = ? AND question_id = ?");
        $stmt->execute([$selected_option, $question_id]);
        $is_correct = $stmt->fetchColumn();

        if ($is_correct !== false) {
            // Store attempt
            $stmt = $conn->prepare("INSERT INTO quiz_attempts (user_id, question_id, selected_option_id, is_correct) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $question_id, $selected_option, $is_correct]);

            // Track score and answers
            if ($is_correct) $score++;
            $answers[$question_id] = [
                'selected' => $selected_option,
                'correct' => $is_correct
            ];
        } else {
            // Option doesn't belong to question — skip or log
            $answers[$question_id] = [
                'selected' => $selected_option,
                'correct' => false
            ];
        }
    }
}

// Store score and answers in session
$_SESSION['quiz_score'] = $score;
$_SESSION['quiz_answers'] = $answers;

header("Location: quiz_result.php");
exit();