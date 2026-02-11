<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function get_env_value($key, $default = null) {
    $content = file_get_contents('.env');
    if (preg_match("/^{$key}=(.*)$/m", $content, $matches)) {
        return trim($matches[1], " \"'");
    }
    return $default;
}

$host = get_env_value('DB_HOST', '127.0.0.1');
$db   = get_env_value('DB_DATABASE', 'du_an_tot_nghiep');
$user = get_env_value('DB_USERNAME', 'root');
$pass = get_env_value('DB_PASSWORD', '');
$charset = 'utf8mb4';

echo "Connecting to $db on $host as $user...\n";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
     $pdo = new PDO($dsn, $user, $pass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     echo "Connected successfully.\n";
     
     $stmt = $pdo->query("SHOW TABLES LIKE 'banners'");
     if ($stmt->rowCount() > 0) {
         echo "Table 'banners' exists.\n";
     } else {
         echo "Table 'banners' does NOT exist.\n";
     }
} catch (\Exception $e) {
     echo "Error: " . $e->getMessage() . "\n";
}
