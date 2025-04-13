<?php

use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\field\FieldStorageConfig;
use Drupal\field\FieldConfig;

// Create or update the paragraph type
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

// Create or update the field storage
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

// Create or update the field
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

print "Product grid paragraph type configured\n";
