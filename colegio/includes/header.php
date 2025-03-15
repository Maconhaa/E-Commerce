<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MercadoPHP</title>
    <!-- Fuente profesional -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Archivos CSS -->
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="css/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header class="site-header">
        <!-- Aquí va el contenido del header -->
        <div class="header-container">
            <div class="logo">
                <a href="index.php">MercadoPHP</a>
            </div>
            <div class="search-bar">
                <form action="search.php" method="GET">
                    <input type="text" name="q" placeholder="Buscar productos..." required>
                    <button type="submit">Buscar</button>
                </form>
            </div>
            <div class="user-menu">
                <a href="profile.php" class="profile-icon" title="Perfil">
                    <img src="images/profile.svg" alt="Perfil">
                </a>
                <a href="settings.php" class="settings-icon" title="Configuración">
                    <img src="images/settings.svg" alt="Configuración">
                </a>
            </div>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="sell.php">Vender</a></li>
            </ul>
        </nav>
    </header>
