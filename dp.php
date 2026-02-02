<?php
/*
 * Файл конфигурации базы данных
 * Используется PDO (PHP Data Objects)
 */

// Настройки подключения
$host = 'localhost';
$db   = 'm98312qr_test';
$user = 'm98312qr_test';
$pass = 'Test123';
$charset = 'utf8mb4';

// DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Опции PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Функция-обертка для htmlspecialchars
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
