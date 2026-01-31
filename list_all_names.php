<?php
$apiKey = "AIzaSyARg7Etgi2608_EU7jyDuEDfgVOIJOUEJo";
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=$apiKey";
$res = @file_get_contents($url);
if ($res) {
    $data = json_decode($res, true);
    if (isset($data['models'])) {
        foreach ($data['models'] as $m) {
            echo $m['name'] . "\n";
        }
    }
}
