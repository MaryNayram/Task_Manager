<?php
session_start();
include "DB_connection.php";

$message = null;
$category = $_POST['category'] ?? 'Cyber Safety';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quizJson = file_get_contents("quiz.json");
    $quizData = json_decode($quizJson, true);

    if (is_array($quizData)) {
        foreach ($quizData as $q) {
            // Insert question with selected category
            $stmt = $conn->prepare("INSERT INTO questions (question, category) VALUES (?, ?)");
            $stmt->execute([$q['question'], $category]);
            $question_id = $conn->lastInsertId();

            // Insert options
            foreach ($q['options'] as $index => $opt) {
                $is_correct = ($index === $q['answer']) ? 1 : 0;
                $stmt = $conn->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                $stmt->execute([$question_id, $opt, $is_correct]);
            }
        }

        $message = "✅ Quiz generated and saved successfully under '$category' category!";
    } else {
        $message = "⚠️ Failed to load quiz data. Check quiz.json format.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Generate Quiz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1abc9c, #0f2027);
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    .card-box {
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(10px);
      padding: 30px;
      border-radius: 16px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }
    .btn-primary {
      background-color: #00d4ff;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0097a7;
    }
    select.form-select {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="card-box">
    <h3 class="mb-4">Generate Quiz by Category</h3>
    <?php if ($message): ?>
      <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
      <label for="category" class="form-label">Select Quiz Category:</label>
      <select name="category" id="category" class="form-select">
        <option value="Cyber Safety" <?= $category === 'Cyber Safety' ? 'selected' : '' ?>>Cyber Safety</option>
        <option value="Cyber Hygiene" <?= $category === 'Cyber Hygiene' ? 'selected' : '' ?>>Cyber Hygiene</option>
        <option value="Incident Reporting" <?= $category === 'Incident Reporting' ? 'selected' : '' ?>>Incident Reporting</option>
        <option value="Reporting" <?= $category === 'Reporting' ? 'selected' : '' ?>>Reporting</option>
        <option value="Training Analytic" <?= $category === 'Training Analytic' ? 'selected' : '' ?>>Training Analytic</option>
        <option value="Patient Privacy" <?= $category === 'Patient Privacy' ? 'selected' : '' ?>>Patient Privacy</option>
        <option value="Admin Training" <?= $category === 'Admin Training' ? 'selected' : '' ?>>Admin Training</option>
      </select>
      <button type="submit" class="btn btn-primary">Generate Quiz with Copilot</button>
    </form>
  </div>
</body>
</html>