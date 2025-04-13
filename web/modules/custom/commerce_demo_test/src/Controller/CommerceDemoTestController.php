<?php

namespace Drupal\commerce_demo_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_store\Entity\Store;
use Drupal\commerce_customer\Entity\Customer;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_cart\CartProviderInterface;
use Drupal\commerce_cart\CartManagerInterface;
use Drupal\commerce_cart\CartOrderManagerInterface;

/**
 * Controller for commerce demo content.
 */
class CommerceDemoTestController extends ControllerBase {

  /**
   * Displays the commerce demo page.
   */
  public function demo() {
    // Load demo products
    $products = \Drupal::entityTypeManager()
      ->getStorage('commerce_product')
      ->loadMultiple();

    // Load demo orders
    $orders = \Drupal::entityTypeManager()
      ->getStorage('commerce_order')
      ->loadMultiple();

    // Load demo customers
    $customers = \Drupal::entityTypeManager()
      ->getStorage('commerce_customer')
      ->loadMultiple();

    // Load demo store
    $store = \Drupal::entityTypeManager()
      ->getStorage('commerce_store')
      ->load(1);

    // Add products to the build array
    $build['products'] = [
      '#theme' => 'commerce_demo_test_products',
      '#products' => $products,
    ];

    // Add orders to the build array
    $build['orders'] = [
      '#theme' => 'commerce_demo_test_orders',
      '#orders' => $orders,
    ];

    // Add customers to the build array
    $build['customers'] = [
      '#theme' => 'commerce_demo_test_customers',
      '#customers' => $customers,
    ];

    // Add store information
    $build['store'] = [
      '#theme' => 'commerce_demo_test_store',
      '#store' => $store,
    ];

    return $build;
  }
}
