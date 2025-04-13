<?php

namespace Drupal\commerce_store_components\Plugin\Field\FieldFormatter;

use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\commerce_product\Entity\ProductVariationInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'commerce_product_grid' formatter.
 *
 * @FieldFormatter(
 *   id = "commerce_product_grid",
 *   label = @Translation("Product Grid"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class CommerceProductFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'view_mode' => 'default',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    
    $form['view_mode'] = [
      '#type' => 'select',
      '#title' => $this->t('View mode'),
      '#options' => $this->getEntityViewModeOptions(),
      '#default_value' => $this->getSetting('view_mode'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      /** @var \Drupal\commerce_product\Entity\ProductInterface $product */
      $product = $item->entity;
      
      $elements[$delta] = [
        '#theme' => 'commerce_product_grid_item',
        '#product' => $product,
        '#variation' => $product->getVariations()[0],
      ];
    }

    return $elements;
  }

  /**
   * Gets the entity view mode options.
   */
  protected function getEntityViewModeOptions() {
    $options = [];
    $view_modes = \Drupal::service('entity_display.repository')
      ->getViewModeOptionsByBundle('commerce_product', 'default');
    
    foreach ($view_modes as $view_mode => $label) {
      $options[$view_mode] = $label;
    }
    
    return $options;
  }
}
