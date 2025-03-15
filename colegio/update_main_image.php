<?php
// update_main_image.php
session_start();
require_once 'db.php';

// Verificar que el usuario esté logueado (si es necesario)
if (!isset($_SESSION['user_id'])) {
    die("Debes estar logueado para cambiar la imagen principal.");
}

// Verificar que se haya recibido el ID del producto y la imagen seleccionada
if (!isset($_POST['product_id']) || !isset($_POST['main_image'])) {
    die("Datos incompletos.");
}

$product_id = intval($_POST['product_id']);
$main_image_index = intval($_POST['main_image']);

// Obtener los datos del producto
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Producto no encontrado.");
}

// Obtener las imágenes del producto
$images = json_decode($product['images']); // Decodificar las imágenes del producto

// Verificar que el índice de la imagen principal sea válido
if ($main_image_index < 0 || $main_image_index >= count($images)) {
    die("Índice de imagen inválido.");
}

// Actualizar el índice de la imagen principal en la base de datos
$stmtUpdate = $pdo->prepare("UPDATE products SET main_image_index = ? WHERE id = ?");
$stmtUpdate->execute([$main_image_index, $product_id]);

// Redirigir al usuario al producto actualizado
header("Location: product.php?id=" . $product_id);
exit;
?>
