<?php
$brainDir = 'C:\Users\SereyPanha\.gemini\antigravity-ide\brain\29742227-b45a-42d8-bea9-fe243ebcb93a';

// Recursively find all content.md files
$directory = new RecursiveDirectoryIterator($brainDir);
$iterator = new RecursiveIteratorIterator($directory);
$files = [];
foreach ($iterator as $info) {
    if ($info->isFile() && $info->getFilename() === 'content.md') {
        $files[] = $info->getPathname();
    }
}

echo "Found " . count($files) . " content.md source files.\n";

$allProducts = [];

function findProducts($arr, &$products) {
    if (!is_array($arr)) return;
    if (isset($arr['product_id']) && isset($arr['image'])) {
        $id = $arr['product_id'];
        $products[$id] = $arr;
        return;
    }
    foreach ($arr as $k => $v) {
        findProducts($v, $products);
    }
}

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match('/<script id="__NEXT_DATA__" type="application\/json">(.*?)<\/script>/s', $content, $matches)) {
        $jsonStr = $matches[1];
        $data = json_decode($jsonStr, true);
        if (is_array($data)) {
            findProducts($data, $allProducts);
        }
    }
}

echo "Total raw products extracted: " . count($allProducts) . "\n";

$subcatPools = [
    'mens-shirts' => [],
    'mens-pants' => [],
    'mens-jackets' => [],
    'womens-dresses' => [],
    'womens-tops' => [],
    'womens-pants' => [],
    'boys-clothing' => [],
    'girls-clothing' => [],
    'baby-rompers' => [],
    'footwear' => [],
    'bags' => [],
    'accessories-belts' => [],
];

$excludeKeywords = [
    'notebook', 'pen ', 'pens ', 'pencil', 'blanket', 'pillowcase', 'lamp', 'suitcase', 'luggage', 
    'umbrella', 'backpack', 'basketball', 'badminton', 'tie', 'glasses', 'charm', 'racket', 'calculator',
    'desk', 'basket', 'toy', 'bottle', 'mug', 'cup', 'spoon', 'fork', 'plate', 'bowl', 'mat', 'sheet'
];

foreach ($allProducts as $p) {
    $name = strtolower($p['product_name'] ?? '');
    $cat = strtolower($p['category_name'] ?? '');
    $seo = strtolower($p['product_seo_url'] ?? '');
    $image = $p['image'] ?? '';
    
    if (empty($name) || empty($image)) {
        continue;
    }
    
    // Check exclusion
    $exclude = false;
    foreach ($excludeKeywords as $kw) {
        if (strpos($name, $kw) !== false || strpos($cat, $kw) !== false) {
            $exclude = true;
            break;
        }
    }
    if ($exclude) {
        continue;
    }
    
    // Format full Zando URL
    $imgUrl = $image;
    if (strpos($imgUrl, 'http') === false) {
        if (strpos($imgUrl, 'image/') === 0) {
            $imgUrl = 'https://zandokh.com/' . $imgUrl;
        } else {
            $imgUrl = 'https://zandokh.com/image/' . $imgUrl;
        }
    }
    
    $mappedItem = [
        'title' => trim($p['product_name']),
        'image' => $imgUrl,
        'price' => floatval(str_replace('$', '', $p['full_price'] ?? '15.00'))
    ];
    
    // Categorize
    if (strpos($name, 'sneakers') !== false || strpos($name, 'shoes') !== false || strpos($name, 'heels') !== false || strpos($name, 'loafers') !== false || strpos($name, 'boots') !== false || strpos($name, 'sandals') !== false || strpos($cat, 'footwear') !== false) {
        $subcatPools['footwear'][] = $mappedItem;
    } elseif (strpos($name, 'bag') !== false || strpos($name, 'tote') !== false || strpos($name, 'purse') !== false || strpos($name, 'wallet') !== false || strpos($cat, 'bag') !== false) {
        $subcatPools['bags'][] = $mappedItem;
    } elseif (strpos($name, 'sunglasses') !== false || strpos($name, 'belt') !== false || strpos($name, 'hat') !== false || strpos($name, 'cap') !== false || strpos($cat, 'accessories') !== false) {
        $subcatPools['accessories-belts'][] = $mappedItem;
    } elseif (strpos($cat, 'boys') !== false || strpos($seo, 'boy') !== false) {
        $subcatPools['boys-clothing'][] = $mappedItem;
    } elseif (strpos($cat, 'girls') !== false || strpos($seo, 'girl') !== false) {
        $subcatPools['girls-clothing'][] = $mappedItem;
    } elseif (strpos($name, 'romper') !== false || strpos($name, 'onesie') !== false || strpos($cat, 'baby') !== false) {
        $subcatPools['baby-rompers'][] = $mappedItem;
    } elseif (strpos($cat, 'women') !== false || strpos($seo, 'women') !== false) {
        if (strpos($name, 'dress') !== false || strpos($name, 'jumpsuit') !== false) {
            $subcatPools['womens-dresses'][] = $mappedItem;
        } elseif (strpos($name, 'skirt') !== false || strpos($name, 'shorts') !== false || strpos($name, 'jeans') !== false || strpos($name, 'pants') !== false || strpos($name, 'trousers') !== false) {
            $subcatPools['womens-pants'][] = $mappedItem;
        } else {
            $subcatPools['womens-tops'][] = $mappedItem;
        }
    } elseif (strpos($cat, 'men') !== false || strpos($seo, 'men') !== false) {
        if (strpos($name, 'jeans') !== false || strpos($name, 'pants') !== false || strpos($name, 'shorts') !== false || strpos($name, 'trousers') !== false || strpos($name, 'jorts') !== false) {
            $subcatPools['mens-pants'][] = $mappedItem;
        } elseif (strpos($name, 'jacket') !== false || strpos($name, 'sweatshirt') !== false || strpos($name, 'hoodie') !== false || strpos($name, 'bomber') !== false || strpos($name, 'cardigan') !== false) {
            $subcatPools['mens-jackets'][] = $mappedItem;
        } else {
            $subcatPools['mens-shirts'][] = $mappedItem;
        }
    } else {
        if (strpos($name, 'dress') !== false) {
            $subcatPools['womens-dresses'][] = $mappedItem;
        } elseif (strpos($name, 'shirt') !== false || strpos($name, 'tee') !== false || strpos($name, 'polo') !== false || strpos($name, 'top') !== false || strpos($name, 'blouse') !== false) {
            $subcatPools['womens-tops'][] = $mappedItem;
        } elseif (strpos($name, 'jeans') !== false || strpos($name, 'pants') !== false || strpos($name, 'skirt') !== false || strpos($name, 'shorts') !== false) {
            $subcatPools['womens-pants'][] = $mappedItem;
        } else {
            $subcatPools['mens-shirts'][] = $mappedItem;
        }
    }
}

foreach ($subcatPools as $key => $pool) {
    $temp = [];
    foreach ($pool as $item) {
        $temp[$item['image']] = $item;
    }
    $subcatPools[$key] = array_values($temp);
    echo "Category [{$key}] count: " . count($subcatPools[$key]) . "\n";
}

file_put_contents('bulk_zando_products.json', json_encode($subcatPools, JSON_PRETTY_PRINT));
echo "Saved bulk products database to bulk_zando_products.json\n";
