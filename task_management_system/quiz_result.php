<?php
session_start();
if (!isset($_SESSION['quiz_score']) || !isset($_SESSION['quiz_answers'])) {
    header("Location: incident_quiz.php");
    exit();
}

$score = $_SESSION['quiz_score'];
$answers = $_SESSION['quiz_answers'];
$total = count($answers); // ✅ Actual number of questions answered
$percentage = $total > 0 ? round(($score / $total) * 100) : 0;

include "DB_connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quiz Results</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1abc9c, #0f2027);
      color: #fff;
      padding: 40px;
      font-family: 'Segoe UI', sans-serif;
    }
    .result-box {
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(10px);
      padding: 30px;
      border-radius: 16px;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }
    .correct { color: #28a745; font-weight: 600; }
    .wrong { color: #dc3545; font-weight: 600; }
    .btn-group {
      margin-top: 30px;
      display: flex;
      justify-content: center;
      gap: 20px;
    }
    .list-group-item {
      background: transparent;
      border: 1px solid rgba(255,255,255,0.1);
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="result-box">
    <h3 class="mb-3">Your Score: <?= $score ?> / <?= $total ?> (<?= $percentage ?>%)</h3>
    <p class="mb-4">Review your answers below. Correct answers are highlighted.</p>
    <ul class="list-group">
      <?php foreach ($answers as $qid => $data): 
        // Fetch question text
        $stmt = $conn->prepare("SELECT question FROM questions WHERE id = ?");
        $stmt->execute([$qid]);
        $question = $stmt->fetchColumn();

        // Fetch selected option text
        $stmt = $conn->prepare("SELECT option_text FROM options WHERE id = ? AND question_id = ?");
        $stmt->execute([$data['selected'], $qid]);
        $selected_text = $stmt->fetchColumn();

        // Fetch correct option text
        $stmt = $conn->prepare("SELECT option_text FROM options WHERE question_id = ? AND is_correct = 1");
        $stmt->execute([$qid]);
        $correct_text = $stmt->fetchColumn();

        // Fallbacks
        $selected_text = $selected_text ?: "[Unknown Option]";
        $correct_text = $correct_text ?: "[Correct Answer Not Found]";
      ?>
      <li class="list-group-item text-white">
        <strong>Q<?= $qid ?>: <?= htmlspecialchars($question) ?></strong><br>
        Your Answer: <span class="<?= $data['correct'] ? 'correct' : 'wrong' ?>">
          <?= htmlspecialchars($selected_text) ?>
        </span><br>
        <?php if (!$data['correct']): ?>
          Correct Answer: <span class="correct"><?= htmlspecialchars($correct_text) ?></span>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>

    <div class="btn-group">
      <a href="incident_quiz.php" class="btn btn-outline-light">Retake Quiz</a>
      <a href="index.php" class="btn btn-outline-primary">Back to Dashboard</a>
    </div>
  </div>
</body>
</html>