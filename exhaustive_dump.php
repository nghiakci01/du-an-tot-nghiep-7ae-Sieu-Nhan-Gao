<?php
$apiKey = "AIzaSyARg7Etgi2608_EU7jyDuEDfgVOIJOUEJo";
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=$apiKey";
$res = @file_get_contents($url);
if ($res) {
    $data = json_decode($res, true);
    if (isset($data['models'])) {
        $names = [];
        foreach ($data['models'] as $m) {
            $names[] = $m['name'];
        }
        file_put_contents("c:\\laragon\\www\\du-an-tot-nghiep\\all_models_dump.txt", implode("\n", $names));
        echo "Dumped " . count($names) . " models.";
    } else {
        echo "No models found. Response: " . $res;
    }
} else {
    echo "Connection failed.";
}
