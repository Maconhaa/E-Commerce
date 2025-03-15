<?php
include 'includes/header.php';
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$mensaje = "";

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    if ($title && $price && !empty($_FILES['images']['name'][0])) {
        $imageNames = [];

        // Crear la carpeta si no existe
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Procesar cada imagen subida
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetPath = $uploadDir . time() . "_" . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $imageNames[] = $targetPath;
            }
        }

        // Convertir array a string separado por comas
        $imagePaths = implode(',', $imageNames);

        // Insertar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO products (user_id, title, description, price, images) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $title, $description, $price, $imagePaths])) {
            $mensaje = "<p class='success'>Producto publicado exitosamente.</p>";
        } else {
            $mensaje = "<p class='error'>Hubo un error al publicar el producto.</p>";
        }
    } else {
        $mensaje = "<p class='error'>Complete los campos obligatorios y suba al menos una imagen.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicar un Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/sell.css">
    <link rel="stylesheet" href="css/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <div class="sell-container">
        <h2>Publicar un Producto</h2>

        <?php if ($mensaje): ?>
            <div class="message"><?= $mensaje; ?></div>
        <?php endif; ?>

        <form method="post" action="sell.php" class="sell-form" enctype="multipart/form-data">
            <label for="title">Título:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Descripción:</label>
            <textarea id="description" name="description"></textarea>

            <label for="price">Precio:</label>
            <input type="number" step="0.01" id="price" name="price" required>

            <label for="images">Subir imágenes:</label>
            <input type="file" id="images" name="images[]" multiple required>

            <button type="submit">Publicar</button>
        </form>
    </div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
