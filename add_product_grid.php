<?php

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\node\Entity\Node;

// Load the store page
$store_page = Node::load(68);
if (!$store_page) {
    print "Store page not found\n";
    return;
}

// Create a new product grid paragraph
$product_grid = Paragraph::create([
    'type' => 'product_grid',
    'field_product' => [
        // Add all existing products
        'target_id' => \Drupal::entityQuery('commerce_product')
            ->accessCheck(FALSE)
            ->execute(),
    ],
]);

// Save the paragraph
$product_grid->save();

// Add the paragraph to the store page
$store_page->field_paragraphs[] = [
    'target_id' => $product_grid->id(),
];

// Save the store page
$store_page->save();

print "Product grid paragraph added to store page\n";
