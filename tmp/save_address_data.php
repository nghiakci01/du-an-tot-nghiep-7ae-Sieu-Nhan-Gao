<?php

function cleanJson($content) {
    // Remove everything before the first '{'
    $pos = strpos($content, '{');
    if ($pos !== false) {
        return substr($content, $pos);
    }
    return $content;
}

$stepsDir = 'C:/Users/ADMIN/.gemini/antigravity/brain/e934deb6-7a2e-4870-b251-5516f3b488c6/.system_generated/steps/';
$targetDir = 'd:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/public/data/';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Provinces
$content = file_get_contents($stepsDir . '89/content.md');
file_put_contents($targetDir . 'provinces.json', cleanJson($content));

// Districts
$content = file_get_contents($stepsDir . '92/content.md');
file_put_contents($targetDir . 'districts.json', cleanJson($content));

// Wards
$content = file_get_contents($stepsDir . '107/content.md');
file_put_contents($targetDir . 'wards.json', cleanJson($content));

echo "Address data files saved successfully to $targetDir\n";
