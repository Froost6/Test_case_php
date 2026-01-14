<?php
session_start();
require_once 'dp.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") { 
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $user_name = trim($_POST['user_name']);
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password_confirm'];

    if (empty($email) || empty($phone) || empty($password) || empty($passwordConfirm)) {
        echo "Все поля обязательны";
        exit;
    }

    if ($password !== $passwordConfirm) {
        echo "Пароли не совпадают";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


     $sql = "INSERT INTO users (email, phone, user_name, password)
            VALUES (:email, :phone, :user_name, :password)";
    $stmt = $pdo->prepare($sql);

    

    try {
        $stmt->execute([
            'email' => $email,
            'phone' => $phone,
            'password' => $hashedPassword,
            'user_name' => $user_name
        ]);

        $userId = $pdo->lastInsertId();

         $_SESSION['user'] = [
        "id" => $userId,
        "email" => $email,
        "user_name" => $user_name,
        "phone" => $phone
    ];

    header("Location: pageForUsers.php");
    exit(); 
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "Email или телефон уже используются";
        } else {
            echo "Ошибка: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require_once 'header.php'; ?>
    <main>
        <section class="main-section">
            <div>
                <h1>Регистрация</h1>
                <form method="post" action="register.php">
                    <label for="email">Введите email</label>
                    <input id="email" name="email" type="text" placeholder="Введите email ">

                    <label for="phone">Введите номер телефона</label>
                    <input id="phone" name="phone" type="text" placeholder="Введите номер телефона">

                    <label for="user_name">Укажите имя пользователя</label>
                    <input id="user_name" name="user_name" type="text" placeholder="Введите имя пользователя">

                    <label for="password">Введите пароль</label>
                    <input id="password" name="password" type="password" placeholder="Введите пароль">

                    <label for="password_confirm">Повторите пароль:</label>
                    <input id="password_confirm" type="password" name="password_confirm" required>

                    <button type="submit">Зарегистрироваться</button>
                </form>
            </div>
        </section>
    </main>
    <?php require_once 'footer.php'; ?>
</body>
</html>

