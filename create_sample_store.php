<?php

use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

// Create a sample store page
$store_page = Node::create([
    'type' => 'store_page',
    'title' => 'Store',
    'status' => 1,
    'field_paragraphs' => [], // Will be populated below
]);

// Create product grid paragraphs for each product
$products = \Drupal::entityTypeManager()
    ->getStorage('commerce_product')
    ->loadMultiple();

foreach ($products as $product) {
    $paragraph = Paragraph::create([
        'type' => 'product_grid',
        'field_product' => [
            'target_id' => $product->id(),
        ],
    ]);
    $paragraph->save();
    
    $store_page->field_paragraphs[] = [
        'target_id' => $paragraph->id(),
    ];
}

$store_page->save();
print "Created sample store page with product grid paragraphs\n";
