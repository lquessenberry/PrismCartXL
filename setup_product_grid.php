<?php

use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

// Create the product grid paragraph type if it doesn't exist
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

// Create the field storage if it doesn't exist
$field_storage = FieldStorageConfig::load('paragraph.field_product');
if (!$field_storage) {
    $field_storage = FieldStorageConfig::create([
        'field_name' => 'field_product',
        'entity_type' => 'paragraph',
        'type' => 'entity_reference_revisions',
        'settings' => [
            'target_type' => 'commerce_product',
        ],
    ]);
    $field_storage->save();
}

// Create the field instance if it doesn't exist
$field = FieldConfig::load('paragraph.product_grid.field_product');
if (!$field) {
    $field = FieldConfig::create([
        'field_storage' => $field_storage,
        'bundle' => 'product_grid',
        'label' => 'Product',
        'description' => 'Select the products to display',
        'required' => FALSE,
        'settings' => [
            'handler' => 'default:commerce_product',
            'handler_settings' => [],
        ],
    ]);
    $field->save();
}

// Set the display settings
$display = \Drupal::service('entity_display.repository')
    ->getViewDisplay('paragraph', 'product_grid', 'default');

if ($display) {
    $display->setComponent('field_product', [
        'type' => 'product_grid_formatter',
        'weight' => 0,
        'label' => 'hidden',
        'settings' => [],
    ]);
    $display->save();
}

print "Product grid paragraph type and field configuration set up successfully\n";
