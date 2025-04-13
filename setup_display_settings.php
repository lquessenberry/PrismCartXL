<?php

use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\field\FieldConfig;
use Drupal\field\FieldStorageConfig;

// Load the product grid paragraph type
$product_grid_type = ParagraphsType::load('product_grid');
if (!$product_grid_type) {
    print "Product grid paragraph type not found\n";
    return;
}

// Create default view mode if it doesn't exist
$view_mode = \Drupal::service('entity_display.repository')
    ->getViewDisplay('paragraph', 'product_grid', 'default');

if (!$view_mode) {
    $view_mode = \Drupal::entityTypeManager()
        ->getStorage('entity_view_display')
        ->create([
            'targetEntityType' => 'paragraph',
            'bundle' => 'product_grid',
            'mode' => 'default',
            'status' => TRUE,
        ]);
}

// Configure the field display
$view_mode->setComponent('field_product', [
    'type' => 'commerce_product',
    'weight' => 0,
]);

$view_mode->save();

print "Display settings configured for product grid paragraph type\n";
