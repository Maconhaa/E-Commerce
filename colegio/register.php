<?php
// register.php
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
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($username && $email && $password) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $passwordHash]);
            $message = "Registro exitoso. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
        } catch (PDOException $e) {
            $message = "Error en el registro: " . $e->getMessage();
        }
    } else {
        $message = "Complete todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse - MercadoPHP</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- CSS para autenticación -->
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/auth.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="auth-page">
    <div class="auth-container">
        <h2>Registrarse</h2>
        <?php if($message): ?>
            <p class="error"><?= $message ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST" class="auth-form">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="btn">Registrarse</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php" class="link-btn">Inicia Sesión</a></p>
    </div>
</body>
</html>
