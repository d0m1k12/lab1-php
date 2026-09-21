<?php
  $appName = "Task Manager";
  $taskTitle = "виконати лабораторну роботу з PHP";
  $taskTimeEstimate = 2;
  $isCompleted = true;
?>


<!DOCTYPE html>
<html lang="uk">
<head>
  <title><?= $appName ?></title>
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
    <h1><?= $appName ?></h1>
  </header>
  
  <main>
    <h2>Список завдань:</h2>
    <ul>
      <li class="<?= $isCompleted ? 'task-done' : 'task-pending' ?>">
        <strong>Завдання:</strong> <?= $taskTitle ?> <br>
        <span class="status-badge">
          <?php if ($isCompleted): ?>
            Виконано
          <?php else: ?>
            В процесі виконання
          <?php endif; ?>
        </span><br>
        <strong>Очікуваний час виконання:</strong> <?= $taskTimeEstimate ?> год.
      </li>
    </ul>
  </main>
</body>
</html>