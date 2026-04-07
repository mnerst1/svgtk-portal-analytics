<?php
// ============================================================
//  СВГТК Портал — конфигурация подключения к БД
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'svgtk_portal');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('ACADEMIC_YEAR', '2024-2025');
define('SITE_NAME', 'СВГТК Портал');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        die('<div style="font-family:sans-serif;padding:40px;color:#dc2626;">
            <h2>Ошибка подключения к базе данных</h2>
            <p>Проверьте настройки в <code>includes/config.php</code></p>
            <pre style="background:#fee2e2;padding:12px;border-radius:8px;margin-top:12px">'.
            htmlspecialchars($e->getMessage()).'</pre></div>');
    }
    return $pdo;
}
