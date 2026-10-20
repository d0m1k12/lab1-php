<?php
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$lastLoginDate = "Невідомо";
if (isset($_COOKIE['user_time'])) {
    $lastLoginDate = date('d.m.Y H:i:s', (int)$_COOKIE['user_time']);
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Особистий кабінет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'navigation.php'; ?>

    <h1>Особистий кабінет</h1>
    <p>Вітаємо в секретній зоні! Ви успішно пройшли перевірку даних.</p>

    <p>Користувач: <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Невідомо') ?></strong></p>
    <p>Ваш ID користувача: <strong><?= htmlspecialchars($_SESSION['uid'] ?? 'Невідомо') ?></strong></p>
    <p>Дата останнього входу: <strong><?= $lastLoginDate ?></strong></p>

    <hr>

    <h3>Інформація про сесію</h3>
    <p>
        Ця сторінка захищена від несанкціонованого доступу. Тільки авторизовані користувачі можуть її бачити.
        Щоб завершити сеанс та вийти, скористайтеся кнопкою "Вихід" у навігаційному меню.
    </p>

</body>
</html>