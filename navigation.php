<?php
?>
<nav>
    <ul>
        <li><a href="index.php">Головна</a></li>

        <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
            <li><a href="cabinet.php">Кабінет</a></li>
            <li><a href="logout.php">Вихід</a></li>
        <?php else: ?>
            <li><a href="login.php">Вхід</a></li>
        <?php endif; ?>
    </ul>
</nav>