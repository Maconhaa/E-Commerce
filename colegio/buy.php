<?php
// buy.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);

    // Obtener información del producto
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Producto no encontrado.");
    }

    // Crear la orden (para simplificar se crea una orden por producto)
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $product['price']]);
        $order_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product['id'], 1, $product['price']]);

        $pdo->commit();
        echo "Compra realizada con éxito.";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error al procesar la compra: " . $e->getMessage();
    }
}
?>
<br>
<a class="btn" href="index.php">Volver a Inicio</a>
s