<?php
session_start();

$appName = "тосьо";
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

$tasks = [
  [
    'id' => 1,
    'title' => 'зробити то сьо',
    'priority' => 'High',
    'isCompleted' => false,
    'timeEstimate' => 2
  ],

  [
    'id' => 2,
    'title' => 'покушать',
    'priority' => 'Medium',
    'isCompleted' => true,
    'timeEstimate' => 4
  ],

  [
    'id' => 3,
    'title' => 'поспать',
    'priority' => 'High',
    'isCompleted' => false,
    'timeEstimate' => 8
  ],

  [
    'id' => 4,
    'title' => 'погулять',
    'priority' => 'Low',
    'isCompleted' => true,
    'timeEstimate' => 1
  ]
];
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
    <ul>

      <?php foreach ($tasks as $task): ?>

        <li class="<?= $task['isCompleted'] ? 'task-done' : 'task-pending' ?>">
          <strong>Завдання:</strong> <?= formatTitle($task['title']) ?> <br>
          <span class="status-badge">
            <?php if ($task['isCompleted']): ?>
              Виконано
            <?php else: ?>
              В процесі виконання
            <?php endif; ?>
          </span><br>
          <strong>Очікуваний час виконання:</strong> <?= $task['timeEstimate'] ?> год.
        </li>
      <?php endforeach; ?>
    </ul>
  </main>
</body>
</html>