<?php
session_start();
include "DB_connection.php";

// Get selected category from URL or default
$selected_category = $_GET['category'] ?? 'Cyber Safety';

// Fetch questions and options by category
$stmt = $conn->prepare("SELECT q.id AS question_id, q.question, o.id AS option_id, o.option_text 
                        FROM questions q 
                        JOIN options o ON q.id = o.question_id 
                        WHERE q.category = ?
                        ORDER BY q.id, o.id");
$stmt->execute([$selected_category]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group questions and options
$quiz = [];
foreach ($rows as $row) {
    $qid = $row['question_id'];
    if (!isset($quiz[$qid])) {
        $quiz[$qid] = [
            'question' => $row['question'],
            'options' => []
        ];
    }
    $quiz[$qid]['options'][] = [
        'id' => $row['option_id'],
        'text' => $row['option_text']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($selected_category) ?> Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1abc9c, #0f2027);
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      padding: 40px;
    }
    .quiz-container {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 30px;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
    }
    h4 {
      margin-bottom: 30px;
      font-weight: 600;
      color: #00d4ff;
    }
    h5 {
      margin-top: 20px;
      font-size: 18px;
      font-weight: 500;
    }
    label {
      display: block;
      margin-left: 20px;
      margin-bottom: 6px;
      cursor: pointer;
    }
    .btn-submit {
      margin-top: 30px;
    }
    .form-select {
      max-width: 300px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <!-- Back to Dashboard Button -->
  <a href="index.php" class="btn btn-outline-light mb-4 d-inline-flex align-items-center">
  <i class="fa-solid fa-arrow-left me-2"></i> Back to Dashboard
</a>

  <div class="quiz-container">
    <h4><?= htmlspecialchars($selected_category) ?> Quiz</h4>

    <!-- Category Selector -->
    <form method="GET">
      <select name="category" class="form-select" onchange="this.form.submit()">
        <option value="Cyber Safety" <?= $selected_category === 'Cyber Safety' ? 'selected' : '' ?>>Cyber Safety</option>
        <option value="Cyber Hygiene" <?= $selected_category === 'Cyber Hygiene' ? 'selected' : '' ?>>Cyber Hygiene</option>
        <option value="Incident Reporting" <?= $selected_category === 'Incident Reporting' ? 'selected' : '' ?>>Incident Reporting</option>
        <option value="Reporting" <?= $selected_category === 'Reporting' ? 'selected' : '' ?>>Reporting</option>
        <option value="Training Analytic" <?= $selected_category === 'Training Analytic' ? 'selected' : '' ?>>Training Analytic</option>
        <option value="Patient Privacy" <?= $selected_category === 'Patient Privacy' ? 'selected' : '' ?>>Patient Privacy</option>
        <option value="Admin Training" <?= $selected_category === 'Admin Training' ? 'selected' : '' ?>>Admin Training</option>
      </select>
    </form>

    <!-- Quiz Form -->
    <form action="submit_quiz.php" method="POST">
      <?php if (empty($quiz)): ?>
        <p>No questions available for this category.</p>
      <?php else: ?>
        <?php foreach ($quiz as $qid => $q): ?>
          <h5><?= htmlspecialchars($q['question']) ?></h5>
          <?php foreach ($q['options'] as $opt): ?>
            <label>
              <input type="radio" name="q<?= $qid ?>" value="<?= $opt['id'] ?>" required>
              <?= htmlspecialchars($opt['text']) ?>
            </label>
          <?php endforeach; ?>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-outline-primary btn-submit">Submit Quiz</button>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>