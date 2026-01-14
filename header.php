<?php 
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <section>
        <div><h1 class="header-title">Сайт на php</h1></div>
    </section>
    <nav>
        <a href="index.php">Главная</a>

        <?php if (isset($_SESSION['user'])): ?>
            <a href="pageForUsers.php">Личный кабинет</a>
            <a href="logout.php">Выйти</a>
        <?php else: ?>
            <a href="login.php">Войти</a>
            <a href="register.php">Регистрация</a>
        <?php endif; ?>
    </nav>
</header>