<?php
$content = file_get_contents(__DIR__.'/resources/views/frontend/products/show.blade.php');
if (preg_match('/<script>(.*?)<\/script>/s', $content, $matches)) {
    $js = $matches[1];
} else if (preg_match_all('/<script[^>]*>(.*?)<\/script>/i', $content, $matches)) {
    $js = implode("\n", $matches[1]);
} else {
    echo "No JS found\n"; exit;
}

$lines = explode("\n", $js);
$curly = 0;
$paren = 0;
foreach ($lines as $i => $line) {
    $lineCln = preg_replace('/\/\/.*$/', '', $line);
    $curly += substr_count($lineCln, '{') - substr_count($lineCln, '}');
    $paren += substr_count($lineCln, '(') - substr_count($lineCln, ')');
    if ($curly < 0 || $paren < 0) {
        echo "Possible mismatch at line " . ($i+1) . ": $line\n";
    }
}
echo "Final curly: $curly, Final paren: $paren\n";
