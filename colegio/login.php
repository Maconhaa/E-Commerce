<?php
// login.php
session_start();
require_once 'db.php';

// Si el usuario ya está autenticado, redirigir a index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        $message = "Usuario o contraseña incorrecta.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - MercadoPHP</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- CSS para autenticación -->
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/auth.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="auth-page">
    <div class="auth-container">
        <h2>Iniciar Sesión</h2>
        <?php if($message): ?>
            <p class="error"><?= $message ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST" class="auth-form">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="btn">Ingresar</button>
        </form>
        <p>¿No tienes cuenta? <a href="register.php" class="link-btn">Registrate aquí</a></p>
    </div>
</body>
</html>
