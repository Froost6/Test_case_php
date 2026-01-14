<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require_once 'header.php'; ?>
    <main>
        <section class="main-section width">
            <div>
                <h1>Добро пожаловать на главную страницу сайта!</h1>
            </div>
            <div>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eius perferendis corrupti explicabo minima animi labore consequatur similique nam iusto accusantium sequi cum quia, sit a recusandae quidem, neque provident saepe.</p>
            </div>
        </section>
    </main>
    <?php require_once 'footer.php'; ?>
</body>
</html>

