<?php
// upload_product.php
session_start();
require_once 'db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    die("Debes estar logueado para subir un producto.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del producto
    $product_title = $_POST['product_title'];

    // Subir las imágenes
    $uploaded_images = [];
    $upload_dir = 'uploads/products/'; // Directorio donde se guardarán las imágenes
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    foreach ($_FILES['product_images']['name'] as $key => $image_name) {
        $tmp_name = $_FILES['product_images']['tmp_name'][$key];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        // Verificar que la extensión sea válida
        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            $new_image_name = uniqid() . '.' . $image_extension;
            $upload_path = $upload_dir . $new_image_name;

            if (move_uploaded_file($tmp_name, $upload_path)) {
                $uploaded_images[] = $upload_path;
            } else {
                die("Error al subir la imagen: $image_name");
            }
        } else {
            die("Archivo no válido: $image_name");
        }
    }

    // Convertir el array de imágenes en JSON
    $images_json = json_encode($uploaded_images);

    // Insertar el producto y las imágenes en la base de datos
    $stmt = $pdo->prepare("INSERT INTO products (title, images) VALUES (?, ?)");
    $stmt->execute([$product_title, $images_json]);

    // Redirigir al producto recién subido
    $product_id = $pdo->lastInsertId();
    header("Location: product.php?id=" . $product_id);
    exit;
}
?>
