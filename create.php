<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/Task.php';
require_once __DIR__ . '/TaskRepository.php';

$title = '';
$description = '';
$priority = 'Low';
$deadline = '';
$errors = [];
$uploadedFilePath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    echo "<pre>";
    var_dump($_POST);
    var_dump($_FILES);
    echo "</pre>";
    

    $title = htmlspecialchars(trim($_POST['title'] ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));
    $priority = $_POST['priority'] ?? 'Low';
    $deadline = trim($_POST['deadline'] ?? '') ?: null;

    if (empty($title)) {
        $errors[] = "Поле «Назва завдання» є обов'язковим для заповнення!";
    }
    if (empty($description)) {
        $errors[] = "Поле «Опис завдання» є обов'язковим для заповнення!";
    }
    $file = $_FILES['avatar'] ?? null;

    if ($file !== null && $file['error'] !== UPLOAD_ERR_NO_FILE) {

      if ($file['error'] === UPLOAD_ERR_OK) {

        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            $errors[] = "Файл завеликий! Максимальний розмір — 2 МБ.";
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            $errors[] = "Дозволено завантажувати лише зображення формату JPG або PNG!";
        }

        if (getimagesize($file['tmp_name']) === false) {
                $errors[] = "Завантажений файл не є зображенням!";
        }
      } elseif ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            $errors[] = "Файл перевищує дозволений ліміт сервера!";
      } else {
          $errors[] = "Помилка завантаження файлу (код " . $file['error'] . ").";
      }
    }

    if (empty($errors)) {

        if ($file !== null && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newName = uniqid('avatar_', true) . '.' . $ext;
            $destination = $uploadDir . $newName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $uploadedFilePath = $destination;
            } else {
                $errors[] = "Помилка запису на диск (перевірте права на папку uploads).";
            }
        }
    }

    if (empty($errors)) {
      $repo = new TaskRepository(__DIR__ . '/tasks.json');
      $repo->add($title, $deadline);

      echo "<div style='color: green; font-weight: bold; padding: 10px; border: 1px solid green; margin: 10px 0;'>Завдання успішно додано до бази даних!";
      if ($uploadedFilePath !== '') {
          echo "<br>Файл збережено як: " . htmlspecialchars($uploadedFilePath);
      }
      echo "</div>";

      if ($uploadedFilePath !== '') {
          echo "<img src='" . htmlspecialchars($uploadedFilePath) . "' alt='Аватар' style='max-width:150px; border-radius:8px; margin-bottom:15px;'>";
      }
      
      $title = '';
      $description = '';
      $priority = 'Low';
      $deadline = '';
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
    input[type="text"], input[type="date"], textarea, select { width: 100%; max-width: 400px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
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

  <form action="create.php" method="POST" enctype="multipart/form-data">
    
    <div class="form-group">
      <label for="title">Назва завдання:</label>
      <input type="text" id="title" name="title" value="<?= $title ?>" placeholder="Що потрібно зробити?">
    </div>

    <div class="form-group">
      <label for="description">Опис завдання:</label>
      <textarea id="description" name="description" rows="4" placeholder="Деталі..."><?= $description ?></textarea>
    </div>

    <div class="form-group">
      <label for="deadline">Дедлайн (необов'язково):</label>
      <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars((string)$deadline) ?>">
    </div>

    <div class="form-group">
      <label for="priority">Пріоритет:</label>
      <select id="priority" name="priority">
        <option value="Low" <?= ($priority === 'Low') ? 'selected' : '' ?>>Low (Низький)</option>
        <option value="Medium" <?= ($priority === 'Medium') ? 'selected' : '' ?>>Medium (Середній)</option>
        <option value="High" <?= ($priority === 'High') ? 'selected' : '' ?>>High (Високий)</option>
      </select>
    </div>

    <div class="form-group">
      <label for="avatar">Зображення до завдання (JPG/PNG, до 2 МБ):</label>
      <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg">
    </div>

    <button type="submit">Зберегти</button>
  </form>

</body>
</html>