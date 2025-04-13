<?php

use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_store\Entity\Store;

// Load or create store type
if (!\Drupal::entityTypeManager()
    ->getStorage('commerce_store_type')
    ->load('default_store')) {
    \Drupal\commerce_store\Entity\StoreType::create([
        'id' => 'default_store',
        'label' => 'Default Store Type',
    ])->save();
}

// Load or create store
if (!\Drupal::entityTypeManager()
    ->getStorage('commerce_store')
    ->load('default_store')) {
    \Drupal\commerce_store\Entity\Store::create([
        'name' => 'Default Store',
        'type' => 'default_store',
        'status' => TRUE,
    ])->save();
}

// Load or create product type
if (!\Drupal::entityTypeManager()
    ->getStorage('commerce_product_type')
    ->load('demo_product')) {
    \Drupal\commerce_product\Entity\ProductType::create([
        'id' => 'demo_product',
        'label' => 'Demo Product',
        'variationType' => 'demo_variation',
    ])->save();
}

// Load or create product variation type
if (!\Drupal::entityTypeManager()
    ->getStorage('commerce_product_variation_type')
    ->load('demo_variation')) {
    \Drupal\commerce_product\Entity\ProductVariationType::create([
        'id' => 'demo_variation',
        'label' => 'Demo Variation',
    ])->save();
}

// Get the default store
$store = \Drupal::entityTypeManager()
    ->getStorage('commerce_store')
    ->loadMultiple();
$store = reset($store);

// Create demo products
for ($i = 1; $i <= 50; $i++) {
    $product = \Drupal::entityTypeManager()
        ->getStorage('commerce_product')
        ->load('SKU' . str_pad($i, 3, '0', STR_PAD_LEFT));

    if (!$product) {
        $product = Product::create([
            'type' => 'demo_product',
            'title' => 'Demo Product ' . $i,
            'sku' => 'SKU' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'status' => TRUE,
            'store_id' => $store->id(),
        ]);
        $product->save();

        // Create variation
        $variation = ProductVariation::create([
            'type' => 'demo_variation',
            'title' => 'Variation ' . $i,
            'sku' => 'SKU' . str_pad($i, 3, '0', STR_PAD_LEFT) . '-01',
            'status' => TRUE,
            'price' => [
                'amount' => rand(1000, 10000),
                'currency_code' => 'USD',
            ],
            'product_id' => $product->id(),
        ]);
        $variation->save();
    }
}

// Create demo orders
for ($i = 1; $i <= 20; $i++) {
    $order = \Drupal::entityTypeManager()
        ->getStorage('commerce_order')
        ->load('ORDER' . str_pad($i, 3, '0', STR_PAD_LEFT));

    if (!$order) {
        $order = Order::create([
            'type' => 'default',
            'store_id' => $store->id(),
            'uid' => 1, // Using admin user for demo
            'order_number' => 'ORDER' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'state' => 'completed',
        ]);

        // Add 2-4 random products to the order
        $num_items = rand(2, 4);
        for ($j = 0; $j < $num_items; $j++) {
            $products = \Drupal::entityTypeManager()
                ->getStorage('commerce_product')
                ->loadMultiple();

            if (!empty($products)) {
                $product_id = array_rand($products);
                $product = $products[$product_id];
                $variations = $product->getVariations();
                
                if (!empty($variations)) {
                    $variation = reset($variations);
                    $order_item = OrderItem::create([
                        'type' => 'default',
                        'quantity' => rand(1, 3),
                        'purchased_entity' => $variation,
                    ]);
                    $order->addItem($order_item);
                }
            }
        }
        $order->save();
    }
}

print "Demo content generation complete!\n";
