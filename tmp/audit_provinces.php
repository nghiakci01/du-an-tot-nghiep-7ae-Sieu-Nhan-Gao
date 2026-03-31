<?php
$configPath = 'd:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/config/vietnam_provinces.php';
$jsonPath = 'd:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/public/data/provinces.json';

if (!file_exists($configPath) || !file_exists($jsonPath)) {
    die("Files missing.\n");
}

$config = require $configPath;
$json = json_decode(file_get_contents($jsonPath), true);

$jsonNames = [];
foreach ($json as $p) {
    $jsonNames[] = trim($p['name']);
}

$mismatches = [];
foreach ($config as $name) {
    $found = false;
    foreach ($jsonNames as $jName) {
        if (trim($name) === $jName) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        $mismatches[] = $name;
    }
}

if (empty($mismatches)) {
    echo "All config names match JSON names exactly.\n";
} else {
    echo "Found " . count($mismatches) . " mismatches:\n";
    foreach ($mismatches as $m) {
        echo "- $m\n";
    }
}
