<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Campaign;
use App\Models\Product;
use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

echo "Starting Campaign Creation for Theme 7...\n";

// 1. Find a Product (Product ID 330)
$productId = 330;
$product = Product::find($productId);

if (!$product) {
    echo "Product with ID $productId not found. Using first available active product.\n";
    $product = Product::where('status', 1)->first();
}

if (!$product) {
    die("No active products found! Please create a product first.\n");
}

echo "Using Product: " . $product->name . " (ID: " . $product->id . ")\n";

// 2. Create Campaign Data
$campaignName = "Royal Islamic Learning - " . Str::random(5);
$slug = Str::slug($campaignName);

$campaign = new Campaign();
$campaign->name = $campaignName;
$campaign->slug = $slug;
$campaign->banner_title = "রাজকীয় অফার - ৫০% ছাড়";
$campaign->section_title = "কেন এই কুরআন বিশেষ?";
$campaign->section_desc = "<ul><li>সহজ পাঠ</li><li>ডিজিটাল লার্নিং</li><li>উন্নত কাগজ</li></ul>";
$campaign->theme_id = 7; // THEME SEVEN (Royal Islamic)
$campaign->product_id = json_encode([(string) $product->id]);
$campaign->short_description = "<p>আল-কুরআন শিখুন রাজকীয় পদ্ধতিতে। এখনই অর্ডার করুন।</p>";
$campaign->description = "<p>This is a detailed description of the Royal Islamic Learning campaign (Theme 7).</p>";
$campaign->review = 5;
$campaign->status = 1;

// Mock Images (using placeholder if specific ones aren't available, but keeping existing logic if possible)
// Ideally, we would upload images here, but for testing, we can use existing ones or placeholders.
// Checking if product has images to re-use
if ($product->image) {
    $campaign->image_one = $product->image->image;
    $campaign->image_two = $product->image->image;
} else {
    // Fallback placeholders
    $campaign->image_one = 'public/uploads/product/placeholder.jpg';
}

$campaign->save();

echo "Campaign Created Successfully!\n";
echo "Theme: 7 (Royal Islamic)\n";
echo "Name: " . $campaign->name . "\n";
echo "Slug: " . $campaign->slug . "\n";
echo "URL: " . url('campaign/' . $campaign->slug) . "\n";

?>