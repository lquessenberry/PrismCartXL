<?php

/**
 * Implements hook_theme().
 */
function commerce_store_components_theme($existing, $type, $theme, $path) {
  return [
    'paragraph__product_grid__default' => [
      'render element' => 'elements',
      'base hook' => 'paragraph',
      'template' => 'paragraph--product-grid--default',
      'path' => drupal_get_path('module', 'commerce_store_components') . '/templates',
    ],
  ];
}
