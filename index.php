<?php
  $appName = "Task Manager";
  $taskTitle = "виконати лабораторну роботу з PHP";
  $taskTimeEstimate = 2;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
  <title><?= $appName ?></title>
</head>
<body>
  <header>
    <h1><?= $appName ?></h1>
  </header>
  
  <main>
    <h2>Список завдань:</h2>
    <ul>
      <li>
        <strong>Завдання:</strong> <?= $taskTitle ?> <br>
        <strong>Очікуваний час виконання:</strong> <?= $taskTimeEstimate ?> год.
      </li>
    </ul>
  </main>
</body>
</html>