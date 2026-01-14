<?php 
    session_start();
    require_once 'dp.php';

    $errors = [];
    $success = [];

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $login = trim($_POST['login']);
        $password = $_POST['password'];

        $captcha_response = $_POST['g-recaptcha-response'] ?? '';
        if (!$captcha_response) {
            $errors[] = "Подтвердите, что вы не робот.";
        } else {
            $secret_key = "6LeHKkosAAAAAEqamnjTYy8MUJrF7ZUVm_W6JPb9";
            $data = [
                'secret' => $secret_key,
                'response' => $captcha_response
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                ]
            ];
            $context  = stream_context_create($options);
            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify", false, $context);
            $result = json_decode($verify, true);

            if (empty($result['success']) || $result['success'] != true) {
                $errors[] = "Проверка капчи не пройдена.";
            }
        }

        if (empty($login) || empty($password)) {
            $errors[] = "Все поля обязательны";
        }


        if (empty($errors)) {
            $sql = "SELECT * FROM users WHERE email = :login OR phone = :login";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    "id" => $user['id'],
                    "email" => $user['email'],
                    "user_name" => $user['user_name'] ?? '',
                    "phone" => $user['phone']
                ];

                $success[] = "Успешная авторизация.";
                header("Location: pageForUsers.php");
                exit;
            } else {
                $errors[] = "Неверный логин или пароль.";
            }
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style/style.css">
    
</head>
<body>
    <?php require_once 'header.php'; ?>
    <main>
        <section class="main-section">
            <div>
                <h1>Авторизация</h1>
                <form method="post" action="login.php">
                    <label for="login">Email или телефон:</label><br>
                    <input type="text" id="login" name="login" required placeholder="Введите почту или телефон">

                    <label for="password">Пароль:</label><br>
                    <input type="password" id="password" name="password" required placeholder="введите пароль">

                    <div class="g-recaptcha" data-sitekey="6LeHKkosAAAAAO8B5Dac8_F5jMx0pubz-XeNDn03"></div>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <button type="submit">Войти</button><br>
                    <button onclick="window.location.href='register.php'" type="button">Регистрация</button>
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

