<?php

namespace Drupal\commerce_demo\Plugin\DevelGenerate;

use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_product\Plugin\Commerce\ProductVariationType\DefaultProductVariation;
use Drupal\commerce_store\Entity\Store;
use Drupal\commerce_customer\Entity\Customer;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\devel_generate\Plugin\DevelGenerateBase;
use Drupal\commerce_product\Entity\ProductType;
use Drupal\commerce_product\Entity\ProductVariationType;
use Drupal\commerce_product\Entity\ProductAttributeValue;
use Drupal\commerce_product\Entity\ProductAttribute;

/**
 * Generates commerce demo content.
 *
 * @DevelGenerate(
 *   id = "commerce_demo",
 *   label = @Translation("Commerce Demo Content"),
 *   description = @Translation("Generates demo content for Commerce, including products, orders, and customers."),
 *   group = "commerce"
 * )
 */
class CommerceDemoGenerator extends DevelGenerateBase {

  /**
   * {@inheritdoc}
   */
  public function generate($count) {
    $this->generateProductTypes();
    $this->generateProductAttributes();
    $this->generateProductVariationTypes();
    $this->generateProducts($count);
    $this->generateCustomers();
    $this->generateOrders();
    $this->generateStore();
  }

  /**
   * Generates product types.
   */
  protected function generateProductTypes() {
    $product_type = ProductType::create([
      'id' => 'demo_product',
      'label' => 'Demo Product',
      'variationType' => 'demo_variation',
    ]);
    $product_type->save();
  }

  /**
   * Generates product attributes.
   */
  protected function generateProductAttributes() {
    $attributes = [
      'color' => ['Red', 'Blue', 'Green', 'Black', 'White'],
      'size' => ['Small', 'Medium', 'Large', 'X-Large'],
      'material' => ['Cotton', 'Polyester', 'Wool', 'Silk'],
    ];

    foreach ($attributes as $id => $options) {
      $attribute = ProductAttribute::create([
        'id' => $id,
        'label' => ucfirst($id),
      ]);
      $attribute->save();

      foreach ($options as $value) {
        $attribute_value = ProductAttributeValue::create([
          'attribute_id' => $id,
          'value' => $value,
        ]);
        $attribute_value->save();
      }
    }
  }

  /**
   * Generates product variation types.
   */
  protected function generateProductVariationTypes() {
    $variation_type = ProductVariationType::create([
      'id' => 'demo_variation',
      'label' => 'Demo Variation',
      'class' => DefaultProductVariation::class,
    ]);
    $variation_type->save();
  }

  /**
   * Generates products and their variations.
   */
  protected function generateProducts($count) {
    $attributes = \Drupal::entityTypeManager()
      ->getStorage('commerce_product_attribute')
      ->loadMultiple();

    for ($i = 0; $i < $count; $i++) {
      $product = Product::create([
        'type' => 'demo_product',
        'title' => $this->faker->sentence(3),
        'sku' => 'SKU-' . $this->faker->unique()->numberBetween(1000, 9999),
        'status' => TRUE,
      ]);
      $product->save();

      // Generate variations with different attributes
      $variation = ProductVariation::create([
        'type' => 'demo_variation',
        'title' => 'Default Variation',
        'sku' => $product->getSku() . '-01',
        'status' => TRUE,
        'price' => [
          'amount' => $this->faker->numberBetween(1000, 10000),
          'currency_code' => 'USD',
        ],
      ]);
      
      // Add random attributes
      foreach ($attributes as $attribute) {
        $values = $attribute->getValues();
        if (!empty($values)) {
          $value = $values[array_rand($values)];
          $variation->setAttribute($attribute->id(), $value);
        }
      }

      $variation->setProduct($product);
      $variation->save();
    }
  }

  /**
   * Generates customer profiles.
   */
  protected function generateCustomers() {
    for ($i = 0; $i < 10; $i++) {
      $customer = Customer::create([
        'mail' => $this->faker->email,
        'status' => TRUE,
      ]);
      $customer->save();

      // Add billing and shipping addresses
      $billing_address = [
        'country_code' => 'US',
        'administrative_area' => $this->faker->stateAbbr,
        'locality' => $this->faker->city,
        'postal_code' => $this->faker->postcode,
        'address_line1' => $this->faker->streetAddress,
        'given_name' => $this->faker->firstName,
        'family_name' => $this->faker->lastName,
      ];

      $customer->set('field_billing_address', $billing_address);
      $customer->set('field_shipping_address', $billing_address);
      $customer->save();
    }
  }

  /**
   * Generates orders.
   */
  protected function generateOrders() {
    $customers = \Drupal::entityTypeManager()
      ->getStorage('commerce_customer')
      ->loadMultiple();

    $products = \Drupal::entityTypeManager()
      ->getStorage('commerce_product')
      ->loadMultiple();

    foreach ($customers as $customer) {
      $order = Order::create([
        'type' => 'default',
        'store_id' => Store::load(1),
        'uid' => $customer->id(),
        'order_number' => $this->faker->unique()->numberBetween(1000, 9999),
        'state' => 'completed',
      ]);

      // Add 2-4 random products to the order
      $num_items = $this->faker->numberBetween(2, 4);
      for ($i = 0; $i < $num_items; $i++) {
        $product = $products[array_rand($products)];
        $variation = $product->getVariations()[0];

        $order_item = OrderItem::create([
          'type' => 'default',
          'quantity' => $this->faker->numberBetween(1, 3),
          'purchased_entity' => $variation,
        ]);
        $order->addItem($order_item);
      }

      $order->save();
    }
  }

  /**
   * Generates a store.
   */
  protected function generateStore() {
    $store = Store::create([
      'name' => 'Demo Store',
      'status' => TRUE,
      'address' => [
        'country_code' => 'US',
        'administrative_area' => 'CA',
        'locality' => 'San Francisco',
        'postal_code' => '94105',
        'address_line1' => '123 Demo Street',
      ],
    ]);
    $store->save();
  }
}
