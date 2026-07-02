<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$totalCount = \App\Models\Product::count();
$uniqueImagesCount = \App\Models\Product::distinct('image')->count('image');

echo "Database Verification:\n";
echo "Total Products Seeded: {$totalCount}\n";
echo "Total Unique Product Images: {$uniqueImagesCount}\n";

$sample = \App\Models\Product::orderBy('id', 'asc')->take(10)->get();
foreach ($sample as $s) {
    echo "- ID: {$s->id} | Title: '{$s->title}' | Image: '{$s->image}'\n";
}
