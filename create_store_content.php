<?php

use Drupal\node\Entity\NodeType;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

// Create store page content type if it doesn't exist
$store_page_type = NodeType::load('store_page');
if (!$store_page_type) {
    $store_page_type = NodeType::create([
        'type' => 'store_page',
        'name' => 'Store Page',
        'description' => 'A page that displays products using paragraphs',
        'new_revision' => TRUE,
        'preview_mode' => 1,
        'status' => TRUE,
    ]);
    $store_page_type->save();
    print "Created store page content type\n";
}

// Create product grid paragraph type if it doesn't exist
$product_grid_type = ParagraphsType::load('product_grid');
if (!$product_grid_type) {
    $product_grid_type = ParagraphsType::create([
        'id' => 'product_grid',
        'label' => 'Product Grid',
        'description' => 'Displays a grid of products',
        'status' => TRUE,
    ]);
    $product_grid_type->save();
    print "Created product grid paragraph type\n";
}

// Create field storage for product reference if it doesn't exist
$field_storage = FieldStorageConfig::load('paragraph.field_product');
if (!$field_storage) {
    $field_storage = FieldStorageConfig::create([
        'field_name' => 'field_product',
        'entity_type' => 'paragraph',
        'type' => 'entity_reference',
        'settings' => [
            'target_type' => 'commerce_product',
        ],
    ]);
    $field_storage->save();
    print "Created product reference field storage\n";
}

// Create field instance for product grid paragraph if it doesn't exist
$field = FieldConfig::load('paragraph.product_grid.field_product');
if (!$field) {
    $field = FieldConfig::create([
        'field_storage' => $field_storage,
        'bundle' => 'product_grid',
        'label' => 'Product',
        'description' => 'Select the product to display',
        'required' => FALSE,
        'settings' => [
            'handler' => 'default:commerce_product',
            'handler_settings' => [],
        ],
    ]);
    $field->save();
    print "Created product reference field instance\n";
}
