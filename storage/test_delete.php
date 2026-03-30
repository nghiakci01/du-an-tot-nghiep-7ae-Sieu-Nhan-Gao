<?php
try {
    $c = App\Models\Category::whereNotNull('parent_id')->first();
    if ($c) {
        $c->delete();
        echo "Success";
    } else {
        echo "No child category found";
    }
} catch (\Exception $e) {
    echo "ERROR CAUGHT: " . $e->getMessage();
}
