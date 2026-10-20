<?php
session_start();

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['username'] ?? '') === 'admin' && ($_POST['password'] ?? '') === '12345') {
        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_name'] = 'Адміністратор';
        $_SESSION['uid'] = 99;

        setcookie('user_time', time(), time() + 60 * 60 * 24 * 30, '/');
        
        header('Location: index.php');
        exit;
    } else {
        $error = "Невірний логін або пароль!";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Вхід</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"], input[type="password"] { width: 100%; max-width: 400px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .error-box { background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; max-width: 400px; }
    .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
  </style>
</head>
<body>

  <a href="index.php" class="back-link">← На головну</a>

  <h2>Вхід</h2>

  <?php if ($error !== ''): ?>
    <div class="error-box"><?= $error ?></div>
  <?php endif; ?>

  <form action="login.php" method="POST">
    <div class="form-group">
      <label for="username">Логін:</label>
      <input type="text" id="username" name="username">
    </div>

    <div class="form-group">
      <label for="password">Пароль:</label>
      <input type="password" id="password" name="password">
    </div>

    <button type="submit">Увійти</button>
  </form>

</body>
</html>