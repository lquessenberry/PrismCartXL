<?php

namespace Drupal\commerce_store_components\Entity\ViewBuilder;

use Drupal\paragraphs\Entity\ViewBuilder\ParagraphViewBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;

/**
 * View builder handler for paragraphs.
 */
class CommerceStoreComponentsViewBuilder extends ParagraphViewBuilder {
  /**
   * {@inheritdoc}
   */
  protected function getBuildDefaults(EntityInterface $entity, EntityViewDisplayInterface $display) {
    $build = parent::getBuildDefaults($entity, $display);
    
    // Add any additional build defaults specific to our product grid.
    if ($entity->bundle() === 'product_grid') {
      $build['#theme'] = 'commerce_store_components_product_grid';
      $build['#products'] = $entity->get('field_product')->referencedEntities();
      $build['#items_per_page'] = $entity->get('field_items_per_page')->value ?? 12;
      $build['#columns'] = $entity->get('field_columns')->value ?? 3;
    }

    return $build;
  }
}
