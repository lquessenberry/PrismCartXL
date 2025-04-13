<?php

use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\field\FieldConfig;
use Drupal\field\FieldStorageConfig;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;

// Load or create the paragraph type
$product_grid_type = ParagraphsType::load('product_grid');
if (!$product_grid_type) {
    $product_grid_type = ParagraphsType::create([
        'id' => 'product_grid',
        'label' => 'Product Grid',
        'description' => 'Displays a grid of products',
        'status' => TRUE,
    ]);
    $product_grid_type->save();
}

// Load or create the field storage
$field_storage = FieldStorageConfig::loadByName('paragraph', 'field_product');
if (!$field_storage) {
    $field_storage = FieldStorageConfig::create([
        'field_name' => 'field_product',
        'entity_type' => 'paragraph',
        'type' => 'entity_reference_revisions',
        'cardinality' => -1,
        'settings' => [
            'target_type' => 'commerce_product',
        ],
    ]);
    $field_storage->save();
}

// Load or create the field
$field = FieldConfig::loadByName('paragraph', 'product_grid', 'field_product');
if (!$field) {
    $field = FieldConfig::create([
        'field_storage' => $field_storage,
        'bundle' => 'product_grid',
        'label' => 'Products',
        'description' => 'Select products to display in the grid',
        'required' => FALSE,
        'settings' => [
            'handler' => 'default',
            'handler_settings' => [],
        ],
    ]);
    $field->save();
}

// Create or update the display
$display = \Drupal::service('entity_display.repository')
    ->getViewDisplay('paragraph', 'product_grid', 'default');

if (!$display) {
    $display = \Drupal::entityTypeManager()
        ->getStorage('entity_view_display')
        ->create([
            'targetEntityType' => 'paragraph',
            'bundle' => 'product_grid',
            'mode' => 'default',
            'status' => TRUE,
        ]);
}

// Set the formatter for the product field
$display->setComponent('field_product', [
    'type' => 'product_grid_formatter',
    'weight' => 0,
    'label' => 'hidden',
    'settings' => [],
    'third_party_settings' => [],
]);

$display->save();

print "Display settings configured programmatically\n";
