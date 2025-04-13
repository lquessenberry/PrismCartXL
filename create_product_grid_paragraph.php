<?php

use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

// Create product grid paragraph type
$product_grid_type = ParagraphsType::create([
    'id' => 'product_grid',
    'label' => 'Product Grid',
    'description' => 'Displays a grid of products',
    'status' => TRUE,
]);
$product_grid_type->save();

// Create field storage for product reference
$field_storage = FieldStorageConfig::create([
    'field_name' => 'field_product',
    'entity_type' => 'paragraph',
    'type' => 'entity_reference',
    'settings' => [
        'target_type' => 'commerce_product',
    ],
]);
$field_storage->save();

// Create field instance for product grid paragraph
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

print "Product grid paragraph type and field created successfully!\n";
