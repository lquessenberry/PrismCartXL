<?php

namespace Drupal\commerce_store_components\Plugin\views\data;

use Drupal\views\Plugin\views\data\TablePluginBase;

/**
 * Views data for the product grid paragraph type.
 *
 * @ViewsData("commerce_store_components_data")
 */
class CommerceStoreComponentsData extends TablePluginBase {

  /**
   * {@inheritdoc}
   */
  public function getTableSummary() {
    return $this->t('Commerce Store Components Data');
  }

  /**
   * {@inheritdoc}
   */
  public function getTableOptions() {
    return [
      'group' => $this->t('Commerce Store Components'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFields() {
    $fields = parent::getFields();

    $fields['field_product'] = [
      'title' => $this->t('Product'),
      'help' => $this->t('The referenced products in the product grid.'),
      'field' => [
        'id' => 'entity_reference_revisions',
      ],
      'filter' => [
        'id' => 'entity_reference_revisions',
      ],
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getSorts() {
    return [
      'field_product' => [
        'title' => $this->t('Product'),
        'help' => $this->t('Sort by the referenced product.'),
        'id' => 'entity_reference_revisions',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [
      'field_product' => [
        'title' => $this->t('Product'),
        'help' => $this->t('Filter by the referenced product.'),
        'id' => 'entity_reference_revisions',
      ],
    ];
  }
}
