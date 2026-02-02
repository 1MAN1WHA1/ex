<?php
session_start();
require 'dp.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Получаем заказы пользователя
$sql = "
SELECT 
    orders.id,
    orders.created_at,
    orders.status,
    products.title,
    products.price
FROM orders
JOIN products ON products.id = orders.product_id
WHERE orders.user_id = ?
ORDER BY orders.id DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <!-- Навигация -->
    <div class="d-flex justify-content-between mb-3">
        <a href="index.php" class="btn btn-secondary">&larr; Назад на главную</a>
        <a href="change_password.php" class="btn btn-warning">Сменить пароль</a>
    </div>

    <h2>Мои заказы</h2>

    <?php if(empty($orders)): ?>
        <p>Вы пока не сделали ни одного заказа.</p>
    <?php endif; ?>

    <?php foreach ($orders as $order): ?>
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5><?= htmlspecialchars($order['title']) ?></h5>
                    <small><?= htmlspecialchars($order['created_at']) ?></small>
                </div>
                <div class="text-end">
                    <strong><?= $order['price'] ?> ₽</strong><br>
                    <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm mt-1">
                        Подробнее
                    </a>

                    <!-- Кнопка удаления -->
                    <form action="delete_order.php" method="POST" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm mt-1" onclick="return confirm('Вы уверены, что хотите удалить этот заказ?');">
                            Удалить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
