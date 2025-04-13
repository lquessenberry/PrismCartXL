<?php

use Drupal\field\FieldConfig;
use Drupal\field\FieldStorageConfig;

// Load the field storage
$field_storage = FieldStorageConfig::load('paragraph.field_product');
if ($field_storage) {
    // Create or update the field display
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
    ]);

    $display->save();
    print "Product grid formatter configured\n";
}
