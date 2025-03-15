<?php
// search.php
require_once 'db.php';
include 'includes/header.php';

$searchTerm = "";
$results = [];

if (isset($_GET['q'])) {
    $searchTerm = trim($_GET['q']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC");
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->execute([$likeTerm, $likeTerm]);
    $results = $stmt->fetchAll();
}
?>
<div class="container">
    <h2>Resultados de la búsqueda</h2>
    <?php if ($searchTerm): ?>
        <p>Resultados para: <strong><?= htmlspecialchars($searchTerm) ?></strong></p>
    <?php endif; ?>
    <?php if (count($results) > 0): ?>
        <div class="products-grid">
            <?php foreach ($results as $product): ?>
                <div class="product-card">
                    <div class="product-img">
                        <?php if($product['image']): ?>
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
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
    <?php else: ?>
        <p>No se encontraron productos para "<strong><?= htmlspecialchars($searchTerm) ?></strong>"</p>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
