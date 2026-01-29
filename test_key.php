<?php

$apiKey = "AIzaSyAgF0pz7DNuAvA1GjaHJy_6th-vfZFro30";
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

echo "Testing API Key validity...\n";
echo "URL: $url\n\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response:\n";
print_r(json_decode($response, true));
