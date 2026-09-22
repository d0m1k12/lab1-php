<?php
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

$taskTitle = "зробити то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, то сьо туда сюда, а ще треба зробити це і те, і ще багато чого іншого, що займає багато часу і сил і мозку і совісті і всього іншого, що тільки можна уявити";
$isCompleted = true;
$taskTimeEstimate = 3;
?>


<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
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
    <h1><?= getCurrentGreeting() ?>, Дмитре! </h1>
  </header>
  
  <main>
    <h2>Список завдань:</h2>
    <ul>
      <li class="<?= $isCompleted ? 'task-done' : 'task-pending' ?>">
        <strong>Завдання:</strong> <?= formatTitle($taskTitle) ?> <br>
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