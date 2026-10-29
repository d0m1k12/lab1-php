<?php
session_start();

require_once __DIR__ . '/Task.php';
require_once __DIR__ . '/TaskRepository.php';

$appName = "тосьо";

$repo = new TaskRepository(__DIR__ . '/tasks.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'complete') {
        $repo->complete((int) ($_POST['id'] ?? 0));
    }
    if ($action === 'delete') {
        $repo->delete((int) ($_POST['id'] ?? 0));
    }
    header('Location: index.php');
    exit;
}

$tasks = $repo->findAll();

function formatTitle($text, $maxLength = 20) {
    if (mb_strlen($text, 'UTF-8') > $maxLength) {
        return mb_substr($text, 0, $maxLength, 'UTF-8') . '...';
    }
    return $text;
}

function getCurrentGreeting() {
    $hour = (int)date('H'); 
    if ($hour >= 6 && $hour < 12) {
      return "Доброго ранку"; } 
    elseif ($hour >= 12 && $hour < 18) {
      return "Добрий день"; } 
    elseif ($hour >= 18 && $hour <= 23) {
      return "Добрий вечір"; } 
    else {
      return "Доброї ночі"; }
}
?>


<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title><?= $appName ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    .task-done {
      color: green;
      text-decoration: line-through;}
    .task-pending {
      color: gray;
      font-style: italic;}
    .status-badge {
      font-weight: bold;
      margin-left: 10px;}
    .task-actions {
      display: inline-block;
      margin-left: 10px; }
    .task-actions button {
      cursor: pointer;
      padding: 2px 8px;
      border: none;
      border-radius: 4px; }
    .btn-complete {
      background: #28a745;
      color: white; }
    .btn-delete {
      background: #dc3545;
      color: white; }
    .stats {
      margin-bottom: 20px;
      font-weight: bold;
      background: #f9f9f9;
      padding: 10px;
      border: 1px solid #ddd; }
    </style>
</head>
<body>
  <header>
    <?php include 'navigation.php'; ?>
    <h1><?= getCurrentGreeting() ?>, Дмитре! </h1>

    <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
      <p>Привіт, <?= htmlspecialchars($_SESSION['user_name']) ?>! <a href="logout.php">[Вийти]</a></p>
    <?php else: ?>
      <p>Ви увійшли як Гість. <a href="login.php">[Увійти]</a></p>
    <?php endif; ?>

  </header>
  
  <main>
    <h2>Список завдань:</h2>
    <a href="create.php" style="display: inline-block; margin-bottom: 15px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">+ Додати нове завдання</a>

    <div class="stats">
      Всього: <?= count($tasks) ?> |
      Виконано: <?= $repo->countCompleted() ?> |
      Прострочено: <?= $repo->countOverdue() ?>
    </div>

    <ul>
      <?php foreach ($tasks as $task): ?>
        <li class="<?= $task->isCompleted() ? 'task-done' : 'task-pending' ?>" style="margin-bottom: 10px;">
          <strong>Завдання:</strong> <?= formatTitle($task->getTitle()) ?> <br>
          <span class="status-badge">
            <?= $task->getStatusLabel() ?>
          </span><br>

          <?php if ($task->getDeadline()): ?>
              <strong>Дедлайн:</strong> <?= htmlspecialchars($task->getDeadline()) ?><br>
          <?php endif; ?>

          <div class="task-actions">
            <?php if (!$task->isCompleted()): ?>
                <form method="POST" action="index.php" style="display:inline">
                    <input type="hidden" name="action" value="complete">
                    <input type="hidden" name="id" value="<?= $task->getId() ?>">
                    <button type="submit" class="btn-complete">✓ Виконати</button>
                </form>
            <?php endif; ?>
            <form method="POST" action="index.php" style="display:inline">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $task->getId() ?>">
                <button type="submit" class="btn-delete">✕ Видалити</button>
            </form>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </main>
</body>
</html>