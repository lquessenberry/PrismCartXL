<?php

namespace Drupal\commerce_store_components\Plugin\Block;

use Drupal\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Plugin\ContextAwarePluginTrait;
use Drupal\Core\Plugin\ContextAwarePluginInterface;
use Drupal\Core\Plugin\ContextAwarePluginTrait;
use Drupal\Core\Plugin\ContextAwarePluginTrait;
use Drupal\Core\Plugin\ContextAwarePluginTrait;
use Drupal\Core\Plugin\ContextAwarePluginTrait;
use Drupal\Core\Plugin\ContextAwarePluginTrait;
use Drupal\Core\Plugin\ContextAwarePluginTrait;

/**
 * Provides a 'Store' block.
 *
 * @Block(
 *   id = "commerce_store_block",
 *   admin_label = @Translation("Store Block"),
 *   category = @Translation("Commerce"),
 * )
 */
class StoreBlock extends BlockBase implements BlockPluginInterface, ContextAwarePluginInterface {

  use ContextAwarePluginTrait;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $paragraphs = \Drupal::entityTypeManager()
      ->getStorage('paragraph')
      ->loadByProperties(['type' => 'product_grid']);

    $build = [
      '#theme' => 'commerce_store_block',
      '#paragraphs' => $paragraphs,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return ['paragraphs_list:product_grid'];
  }
}
