<?php
$f = "c:\\laragon\\www\\du-an-tot-nghiep\\full_model_list.txt";
$lines = file($f);
foreach ($lines as $l) {
    if (strpos($l, 'models/gemini-') !== false) {
        echo trim($l) . "\n";
    }
}
