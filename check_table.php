<?php
$env = parse_ini_file('.env');
$host = $env['DB_HOST'];
$db   = $env['DB_DATABASE'];
$user = $env['DB_USERNAME'];
$pass = $env['DB_PASSWORD'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
     $pdo = new PDO($dsn, $user, $pass);
     $stmt = $pdo->query("SHOW TABLES LIKE 'banners'");
     if ($stmt->rowCount() > 0) {
         echo "Table 'banners' exists.\n";
     } else {
         echo "Table 'banners' does NOT exist.\n";
     }
} catch (\PDOException $e) {
     echo "Connection failed: " . $e->getMessage() . "\n";
}
