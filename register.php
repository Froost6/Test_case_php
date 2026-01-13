<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>
    <header></header>
    <main>
        <section>
            <div>
                <h1>Регистрация</h1>
                <form method="post" action="register.php">
                    <label for="login">Введите email или номер телефона</label>
                    <input id="login" name="login" type="text" placeholder="Введите email или номер телефона">

                    <label for="name">Укажите имя пользователя</label>
                    <input id="name" name="name" type="text" placeholder="Введите имя пользователя">

                    <label for="password">Введите пароль</label>
                    <input id="password" name="password" type="password" placeholder="Введите пароль">

                    <button type="submit">Зарегистрироваться</button>
                </form>
            </div>
        </section>
    </main>
    <footer></footer>
</body>
</html>

<?php
require_once 'dp.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") { 
    $LOGIN = trim($_POST['login']);
    $NAME = trim($_POST['name']);
    $password = $_POST['password'];

    if (filter_var($LOGIN, FILTER_VALIDATE_EMAIL)) {
        $type = 'email';
    } else if (preg_match('/^\+?\d{10,}$/', $LOGIN)) {
        $type = 'phone';
    } else {
        die('Неверный формат логина. Используйте email или номер телефона.');
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = :login");
    $stmt->execute([':login' => $LOGIN]);
    if ($stmt->fetchColumn() > 0) {
        die("Этот логин уже занят. Введите другой email или телефон.");
    }

    $HashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (LOGIN, USER_NAME, PASSWORD) VALUES (:login, :name, :password)");
    $stmt->execute([
        ':login' => $LOGIN,
        ':name' => $NAME,
        ':password' => $HashedPassword
    ]);
}

?>