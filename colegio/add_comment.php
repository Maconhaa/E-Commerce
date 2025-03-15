<?php
// add_comment.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $comment = trim($_POST['comment']);

    if ($comment) {
        $stmt = $pdo->prepare("INSERT INTO comments (product_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$product_id, $_SESSION['user_id'], $comment]);
    }
    header("Location: product.php?id=" . $product_id);
    exit;
}
?>
