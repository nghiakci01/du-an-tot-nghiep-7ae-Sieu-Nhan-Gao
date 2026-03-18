<?php
$categories = App\Models\Category::whereNotNull('parent_id')->get();
if ($categories->isEmpty()) {
    echo "No child categories found.\n";
} else {
    foreach ($categories as $cat) {
        $childrenCount = $cat->children()->count();
        $activeProductsCount = $cat->products()->count();
        $trashedProductsCount = $cat->products()->onlyTrashed()->count();
        echo "Child Category ID: {$cat->id}, Name: {$cat->name}\n";
        echo " - Sub-children: {$childrenCount}\n";
        echo " - Active Products: {$activeProductsCount}\n";
        echo " - Trashed Products: {$trashedProductsCount}\n";
        echo "--------------------------\n";
    }
}
