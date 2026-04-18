<?php
$f = 'storage/app/vn_communes.json';
if(file_exists($f)){
    $d = json_decode(file_get_contents($f), true);
    $p = [];
    foreach($d as $i) $p[$i['provinceName']] = 1;
    $p = array_keys($p);
    sort($p);
    print_r($p);
} else {
    echo "File not found";
}
