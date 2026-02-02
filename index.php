<?php
session_start();
require 'dp.php';

// CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Товары
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-light bg-light px-4 mb-4 shadow-sm">
    <span class="navbar-brand">Мой Магазин</span>
    <div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="me-3">Привет!</span>
            <a href="profile.php" class="btn btn-outline-primary btn-sm">Личный кабинет</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_panel.php" class="btn btn-outline-danger btn-sm">Админка</a>
                <a href="add_item.php" class="btn btn-success btn-sm">+ Добавить товар</a>
            <?php endif; ?>

            <a href="logout.php" class="btn btn-dark btn-sm">Выйти</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary btn-sm">Войти</a>
            <a href="register.php" class="btn btn-outline-primary btn-sm">Регистрация</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Каталог товаров</h2>

    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php $img = $product['image_url'] ?: 'https://via.placeholder.com/300'; ?>
                    <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" style="height:200px; object-fit:cover;">

                    <div class="card-body">
                        <h5><?= htmlspecialchars($product['title']) ?></h5>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <strong><?= $product['price'] ?> ₽</strong>
                    </div>

                    <div class="card-footer bg-white">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" action="make_order.php">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button class="btn btn-primary w-100">Купить</button>
                            </form>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline-primary w-100">Войти для покупки</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
