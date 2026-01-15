<?php
session_start();
require_once 'dp.php';

$errors = [];
$success = [];


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    if (empty($user_name)) {
        $errors[] = "Имя пользователя обязательно для заполнения.";
    }
    if (empty($email)) {
        $errors[] = "Email обязателен для заполнения.";
    }

    if (!empty($password)) {
        if ($password !== $password_confirm) {
            $errors[] = "Пароли не совпадают.";
        }
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET user_name = :user_name, email = :email, phone = :phone 
            WHERE id = :id
        ");
        $stmt->execute([
            'user_name' => $user_name,
            'email' => $email,
            'phone' => $phone,
            'id' => $_SESSION['user']['id']
        ]);
    
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE users 
                SET password = :password 
                WHERE id = :id
            ");
            $stmt->execute([
                'password' => $hashedPassword,
                'id' => $_SESSION['user']['id']
            ]);
    
            $success[] = "Пароль успешно обновлён.";
        }
    
        $success[] = "Данные профиля успешно обновлены.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница пользователя</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require_once 'header.php'; ?>
    <main>
        <section class="main-section">
            <div>
                <h2>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user']['user_name']); ?>!</h2>
                <form method="POST" action="">
                    <h3>Изменить данные пользователя</h3>
                    <label for="user_name">Имя пользователя:</label>
                    <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($_SESSION['user']['user_name']); ?>">

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>">

                    <label for="phone">Телефон:</label>
                    <input id="phone" name="phone" type="text" value="<?php echo htmlspecialchars($_SESSION['user']['phone']); ?>">


                    <label for="password">Новый пароль:</label>
                    <input type="password" id="password" name="password" placeholder="**********">

                    <label for="password_confirm">Подтвердите пароль:</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="**********">

                    <button type="submit">Сохранить</button>
                    <?php if (!empty($errors)): ?>
                        <div class="alert error-messages">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($success)): ?>
                        <div class="alert success-messages">
                            <?php foreach ($success as $msg): ?>
                                <p><?php echo htmlspecialchars($msg); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </section>
    </main>
    <?php require_once 'footer.php'; ?>  
</body>
</html>

