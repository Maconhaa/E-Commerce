<?php
// publish_product.php

session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que todos los campos necesarios estén presentes
    if (isset($_POST['title'], $_POST['description'], $_POST['price']) && isset($_FILES['images'])) {
        
        // Recoger datos del formulario
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        
        // Procesar las imágenes subidas
        $uploadedImages = [];
        $imageErrors = [];
        $mainImageIndex = 0; // La primera imagen será la principal

        // Verificar y procesar cada imagen subida
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            // Validar cada imagen
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                // Asegurarse de que el archivo tiene una extensión válida
                $fileExtension = strtolower(pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION));
                $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($fileExtension, $validExtensions)) {
                    $imageErrors[] = "La imagen " . $_FILES['images']['name'][$key] . " no tiene una extensión válida.";
                    continue;
                }

                // Generar un nombre único para la imagen
                $imageName = uniqid() . '.' . $fileExtension;
                $imagePath = 'uploads/' . $imageName;
                
                // Mover la imagen al directorio de destino
                if (move_uploaded_file($tmp_name, $imagePath)) {
                    $uploadedImages[] = $imagePath;  // Guardar el path de la imagen
                } else {
                    $imageErrors[] = "Hubo un error al subir la imagen " . $_FILES['images']['name'][$key];
                }
            } else {
                $imageErrors[] = "Hubo un error con la imagen " . $_FILES['images']['name'][$key];
            }
        }

        // Si no se subieron imágenes, mostrar un error
        if (empty($uploadedImages)) {
            die("No se subieron imágenes.");
        }

        // Guardar los datos del producto en la base de datos
        $stmt = $pdo->prepare("INSERT INTO products (title, description, price, images) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $title,
            $description,
            $price,
            json_encode($uploadedImages), // Guardar las imágenes como un JSON
        ]);

        // Redirigir al usuario a la página del producto recién creado
        $product_id = $pdo->lastInsertId();
        header("Location: product.php?id=" . $product_id);
        exit();
    }
}
?>
