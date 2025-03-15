<?php
// index.php
require_once 'db.php';
include 'includes/header.php';

// Consulta de productos
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>
<div class="container">
    <h2>Productos en Venta</h2>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-img">
                    <?php if($product['images']): ?>
                        <img src="<?= htmlspecialchars($product['images']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                    <?php else: ?>
                        <img src="images/default.png" alt="Sin imagen">
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <span class="price">$<?= number_format($product['price'], 2) ?></span>
                    <a class="btn" href="product.php?id=<?= $product['id'] ?>">Ver Detalle</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
