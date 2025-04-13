<?php

// Delete products
$products = \Drupal::entityTypeManager()
    ->getStorage('commerce_product')
    ->loadMultiple();
foreach ($products as $product) {
    $product->delete();
}

// Delete product variations
$variations = \Drupal::entityTypeManager()
    ->getStorage('commerce_product_variation')
    ->loadMultiple();
foreach ($variations as $variation) {
    $variation->delete();
}

// Delete product types
$product_types = \Drupal::entityTypeManager()
    ->getStorage('commerce_product_type')
    ->loadMultiple();
foreach ($product_types as $type) {
    $type->delete();
}

// Delete product variation types
$variation_types = \Drupal::entityTypeManager()
    ->getStorage('commerce_product_variation_type')
    ->loadMultiple();
foreach ($variation_types as $type) {
    $type->delete();
}

// Delete store
$store = \Drupal::entityTypeManager()
    ->getStorage('commerce_store')
    ->loadMultiple();
foreach ($store as $store_entity) {
    $store_entity->delete();
}

// Delete store type
$store_types = \Drupal::entityTypeManager()
    ->getStorage('commerce_store_type')
    ->loadMultiple();
foreach ($store_types as $type) {
    $type->delete();
}

print "Store content cleanup complete!\n";
