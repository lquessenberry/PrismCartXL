<?php

namespace Drupal\commerce_store_components\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'product_grid' formatter.
 *
 * @FieldFormatter(
 *   id = "product_grid",
 *   label = @Translation("Product Grid"),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
class ProductGridFormatter extends FormatterBase implements ContainerFactoryPluginInterface {
  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new ProductGridFormatter instance.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct($plugin_id, $plugin_definition, $field_definition, array $settings, $label, $view_mode, array $third_party_settings, RendererInterface $renderer) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      // Get the referenced products.
      $products = $item->referencedEntities();

      if (!empty($products)) {
        // Create a render array for the products using the existing Views configuration.
        $elements[$delta] = [
          '#theme' => 'commerce_store_components_product_grid',
          '#products' => $products,
          '#items_per_page' => $this->getSetting('items_per_page'),
          '#columns' => $this->getSetting('columns'),
        ];
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'items_per_page' => 12,
      'columns' => 3,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['items_per_page'] = [
      '#type' => 'number',
      '#title' => $this->t('Items per page'),
      '#default_value' => $this->getSetting('items_per_page'),
      '#min' => 1,
      '#max' => 100,
    ];

    $form['columns'] = [
      '#type' => 'number',
      '#title' => $this->t('Columns'),
      '#default_value' => $this->getSetting('columns'),
      '#min' => 1,
      '#max' => 12,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Items per page: @count', ['@count' => $this->getSetting('items_per_page')]);
    $summary[] = $this->t('Columns: @count', ['@count' => $this->getSetting('columns')]);

    return $summary;
  }
}
