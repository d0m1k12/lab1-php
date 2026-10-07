<?php
$title = '';
$description = '';
$priority = 'Low';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";
    

    $title = htmlspecialchars(trim($_POST['title'] ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));
    $priority = $_POST['priority'] ?? 'Low';
    if (empty($title)) {
        $errors[] = "Поле «Назва завдання» є обов'язковим для заповнення!";
    }
    if (empty($description)) {
        $errors[] = "Поле «Опис завдання» є обов'язковим для заповнення!";
    }
    if (empty($errors)) {
        echo "<div style='color: green; font-weight: bold; padding: 10px; border: 1px solid green; margin: 10px 0;'>Завдання успішно пройшло валідацію і готове до збереження!</div>";
        $title = '';
        $description = '';
        $priority = 'Low';
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Створити нове завдання</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"], textarea, select { width: 100%; max-width: 400px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    button { padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .error-box { background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; max-width: 400px;}
    .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
  </style>
</head>
<body>

  <a href="index.php" class="back-link">← Повернутись до списку</a>
  
  <h2>Створити нове завдання</h2>
  <?php if (!empty($errors)): ?>
    <div class="error-box">
      <strong>Увага! Виникли помилки:</strong>
      <ul style="margin-top: 5px; margin-bottom: 0;">
        <?php foreach ($errors as $error): ?>
          <li><?= $error ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="create.php" method="POST">
    
    <div class="form-group">
      <label for="title">Назва завдання:</label>
      <input type="text" id="title" name="title" value="<?= $title ?>" placeholder="Що потрібно зробити?">
    </div>

    <div class="form-group">
      <label for="description">Опис завдання:</label>
      <textarea id="description" name="description" rows="4" placeholder="Деталі..."><?= $description ?></textarea>
    </div>

    <div class="form-group">
      <label for="priority">Пріоритет:</label>
      <select id="priority" name="priority">
        <option value="Low" <?= ($priority === 'Low') ? 'selected' : '' ?>>Low (Низький)</option>
        <option value="Medium" <?= ($priority === 'Medium') ? 'selected' : '' ?>>Medium (Середній)</option>
        <option value="High" <?= ($priority === 'High') ? 'selected' : '' ?>>High (Високий)</option>
      </select>
    </div>

    <button type="submit">Зберегти</button>
  </form>

</body>
</html>