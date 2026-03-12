<?php
$content = file_get_contents(__DIR__.'/resources/views/frontend/products/show.blade.php');
if (preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $content, $matches)) {
    $js = implode("\n", $matches[1]);
}

$lines = explode("\n", $js);
$curly = 0; $paren = 0;
$mismatchStart = -1;
foreach ($lines as $i => $line) {
    if (str_contains($line, 'document.getElementById(')) { /* test */ }
    $lineCln = preg_replace('/(\/\*.*?\*\/|\/\/.*)/', '', $line);
    $lineCln = preg_replace('/(\'.*?\'|".*?"|`.*?`)/', '', $lineCln);
    $c = substr_count($lineCln, '{') - substr_count($lineCln, '}');
    $p = substr_count($lineCln, '(') - substr_count($lineCln, ')');
    
    $curly += $c;
    $paren += $p;
    
    if ($curly < 0 || $paren < 0) {
        echo "Line " . ($i + 1) . ": Curly: $curly, Paren: $paren -> $line\n";
    }
}
echo "Total Curly: $curly, Total Paren: $paren\n";
