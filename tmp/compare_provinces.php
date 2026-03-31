<?php
$config = require 'd:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/config/vietnam_provinces.php';
$jsonStr = file_get_contents('d:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/public/data/provinces.json');
$json = json_decode($jsonStr, true);

$jsonNames = [];
foreach ($json as $code => $p) {
    $jsonNames[] = trim($p['name']);
}

echo "CONFIG PROVINCE NAMES vs JSON NAMES\n";
echo "====================================\n";

foreach ($config as $cName) {
    $cName = trim($cName);
    if (!in_array($cName, $jsonNames)) {
        echo "MISMATCH: Config has '$cName', but NOT found in JSON.\n";
        
        // Find similar matches
        foreach ($jsonNames as $jName) {
            if (str_contains($jName, $cName) || str_contains($cName, $jName)) {
                echo "         -> Potential match: '$jName'\n";
            }
        }
    }
}
