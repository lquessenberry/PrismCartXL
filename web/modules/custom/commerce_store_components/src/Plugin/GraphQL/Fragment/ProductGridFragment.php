<?php

namespace Drupal\commerce_store_components\Plugin\GraphQL\Fragment;

use Drupal\graphql\Plugin\GraphQL\Fragments\FragmentPluginBase;

/**
 * @GraphQLFragment(
 *   id = "product_grid",
 *   name = "Product Grid",
 *   description = "GraphQL fragment for product grid paragraph",
 *   status = TRUE,
 *   query = "fragment ProductGrid on Paragraph {
 *     id
 *     title
 *     fieldProduct {
 *       title
 *       sku
 *       variations {
 *         price {
 *           amount
 *           currencyCode
 *         }
 *         sku
 *       }
 *     }
 *   }"
 * )
 */
final class ProductGridFragment extends FragmentPluginBase {
  public function __construct() {
    parent::__construct([]);
  }
}
