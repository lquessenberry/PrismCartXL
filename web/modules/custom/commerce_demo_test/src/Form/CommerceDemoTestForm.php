<?php

namespace Drupal\commerce_demo_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_store\Entity\Store;
use Drupal\commerce_customer\Entity\Customer;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_product\Entity\ProductType;
use Drupal\commerce_product\Entity\ProductVariationType;
use Drupal\commerce_product\Entity\ProductAttribute;
use Drupal\commerce_product\Entity\ProductAttributeValue;

/**
 * Form for generating commerce demo content.
 */
class CommerceDemoTestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'commerce_demo_test_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'markup',
      '#markup' => $this->t('Generate demo content for Commerce. This will create sample products, orders, and customers.'),
    ];

    $form['product_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Products'),
      '#default_value' => 50,
      '#min' => 1,
      '#max' => 100,
    ];

    $form['customer_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Customers'),
      '#default_value' => 10,
      '#min' => 1,
      '#max' => 50,
    ];

    $form['order_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Orders'),
      '#default_value' => 20,
      '#min' => 1,
      '#max' => 100,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate Content'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $product_count = $form_state->getValue('product_count');
    $customer_count = $form_state->getValue('customer_count');
    $order_count = $form_state->getValue('order_count');

    // Create product attributes
    $this->createAttributes();

    // Create product type
    $this->createProductType();

    // Create demo products
    for ($i = 0; $i < $product_count; $i++) {
      $this->createProduct();
    }

    // Create demo customers
    for ($i = 0; $i < $customer_count; $i++) {
      $this->createCustomer();
    }

    // Create demo orders
    for ($i = 0; $i < $order_count; $i++) {
      $this->createOrder();
    }

    $this->messenger()->addMessage($this->t('Commerce demo content has been generated successfully.'));
    
    $form_state->setRedirect('commerce_demo_test.demo');
  }

  /**
   * Creates product attributes.
   */
  private function createAttributes() {
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
   * Creates product type.
   */
  private function createProductType() {
    $product_type = ProductType::create([
      'id' => 'demo_product',
      'label' => 'Demo Product',
      'variationType' => 'demo_variation',
    ]);
    $product_type->save();
  }

  /**
   * Creates a demo product.
   */
  private function createProduct() {
    $store = Store::load(1);
    
    $product = Product::create([
      'type' => 'demo_product',
      'title' => $this->faker->sentence(3),
      'sku' => 'SKU-' . $this->faker->unique()->numberBetween(1000, 9999),
      'status' => TRUE,
      'store_id' => $store->id(),
    ]);
    $product->save();

    // Create variation
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
    $attributes = \Drupal::entityTypeManager()
      ->getStorage('commerce_product_attribute')
      ->loadMultiple();

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

  /**
   * Creates a demo customer.
   */
  private function createCustomer() {
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

  /**
   * Creates a demo order.
   */
  private function createOrder() {
    $customers = \Drupal::entityTypeManager()
      ->getStorage('commerce_customer')
      ->loadMultiple();

    $products = \Drupal::entityTypeManager()
      ->getStorage('commerce_product')
      ->loadMultiple();

    if (empty($customers) || empty($products)) {
      return;
    }

    $customer = array_rand($customers);
    $store = Store::load(1);

    $order = Order::create([
      'type' => 'default',
      'store_id' => $store->id(),
      'uid' => $customer->id(),
      'order_number' => $this->faker->unique()->numberBetween(1000, 9999),
      'state' => 'completed',
    ]);

    // Add 2-4 random products to the order
    $num_items = $this->faker->numberBetween(2, 4);
    for ($i = 0; $i < $num_items; $i++) {
      $product = array_rand($products);
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
