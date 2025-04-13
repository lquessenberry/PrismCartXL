<?php

use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\node\Entity\Node;

// Create some demo products
$demo_products = [
    [
        'title' => 'Demo Product 1',
        'sku' => 'DP1',
        'price' => 99.99,
        'currency' => 'USD',
    ],
    [
        'title' => 'Demo Product 2',
        'sku' => 'DP2',
        'price' => 149.99,
        'currency' => 'USD',
    ],
];

foreach ($demo_products as $product_data) {
    // Create product
    $product = Product::create([
        'type' => 'default',
        'title' => $product_data['title'],
        'sku' => $product_data['sku'],
        'status' => TRUE,
    ]);
    $product->save();

    // Create product variation
    $variation = ProductVariation::create([
        'type' => 'default',
        'title' => $product_data['title'],
        'sku' => $product_data['sku'],
        'price' => [
            'number' => $product_data['price'],
            'currency_code' => $product_data['currency'],
        ],
        'status' => TRUE,
    ]);
    $variation->save();

    // Add variation to product
    $product->setVariations([$variation]);
    $product->save();

    // Create product grid paragraph
    $paragraph = Paragraph::create([
        'type' => 'product_grid',
        'field_product' => [
            'target_id' => $product->id(),
        ],
    ]);
    $paragraph->save();

    // Add paragraph to store page
    $store_page = Node::load(68);
    $store_page->field_paragraphs[] = [
        'target_id' => $paragraph->id(),
    ];
    $store_page->save();
}

print "Created demo products and added to store page\n";
