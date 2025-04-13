<?php

namespace Drupal\test_views_data\Plugin\views\data;

use Drupal\views\Plugin\views\data\ViewsData;

/**
 * Provides Views data for custom fields.
 *
 * @ViewsData("custom_views_data")
 */
class CustomViewsData extends ViewsData {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Add any custom Views data here.
    
    return $data;
  }
}
