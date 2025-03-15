<?php
// product.php
session_start();
require_once 'db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    die("Producto no encontrado.");
}

$product_id = intval($_GET['id']);

// Obtener datos del producto
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Producto no existe.");
}

// Obtener las imágenes del producto (si hay varias)
$images = json_decode($product['images'], true); // Decodificar las imágenes del producto en un array
$main_image_index = isset($product['main_image_index']) ? $product['main_image_index'] : 0; // Si no hay índice, asignamos 0 como predeterminado

// Obtener comentarios
$stmtComments = $pdo->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.product_id = ? ORDER BY c.created_at DESC");
$stmtComments->execute([$product_id]);
$comments = $stmtComments->fetchAll();
?>

<div class="image-carousel">
    <!-- Contenedor para las imágenes -->
    <div class="image-container">
        <?php 
        // Mostramos la imagen principal
        echo '<img src="' . htmlspecialchars($images[$main_image_index]) . '" alt="' . htmlspecialchars($product['title']) . '" class="carousel-image" id="productImage">';
        ?>
    </div>
    <!-- Flechas de navegación -->
    <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
    <button class="next" onclick="moveSlide(1)">&#10095;</button>
</div>

<div class="comments-section">
    <h3>Comentarios</h3>
    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="add_comment.php" method="POST" class="comment-form">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <textarea name="comment" placeholder="Deja tu comentario aquí..." required></textarea>
            <button type="submit" class="btn">Enviar Comentario</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Inicia sesión</a> para dejar un comentario.</p>
    <?php endif; ?>
    <?php if (count($comments) > 0): ?>
        <div class="comments-list">
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p class="comment-author"><?= htmlspecialchars($comment['username']) ?> dice:</p>
                    <p class="comment-text"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                    <span class="comment-date"><?= date("d/m/Y H:i", strtotime($comment['created_at'])) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay comentarios aún.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    let images = <?= json_encode($images) ?>;
    let currentIndex = <?= $main_image_index ?>;
    const imgElement = document.getElementById('productImage');

    function moveSlide(direction) {
        currentIndex = (currentIndex + direction + images.length) % images.length;  // Se asegura que no se salga del array
        imgElement.src = images[currentIndex];
    }

    document.querySelector('.next').addEventListener('click', () => moveSlide(1));
    document.querySelector('.prev').addEventListener('click', () => moveSlide(-1));
</script>
