<?php
$content = file_get_contents(__DIR__.'/resources/views/frontend/products/show.blade.php');
if (preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $content, $matches)) {
    $js = implode("\n", $matches[1]);
}

$lines = explode("\n", $js);
$curly = 0; $paren = 0;
foreach ($lines as $i => $line) {
    // Basic comment strip
    $lineCln = preg_replace('/(\/\*.*?\*\/|\/\/.*)/', '', $line);
    
    // Ignore brackets inside strings (heuristic for template)
    $lineCln = preg_replace('/(\'.*?\'|".*?"|`.*?`)/', '', $lineCln);
    
    $c = substr_count($lineCln, '{') - substr_count($lineCln, '}');
    $p = substr_count($lineCln, '(') - substr_count($lineCln, ')');
    
    $curly += $c;
    $paren += $p;
    
    if ($c > 0 || $p > 0) {
        // block opens
    }
}
echo "Total Curly: $curly, Total Paren: $paren\n";
