<?php

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\node\Entity\NodeType;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\graphql\Plugin\GraphQL\Fragments\FragmentPluginManager;

// Create store page content type
$store_page_type = NodeType::create([
    'type' => 'store_page',
    'name' => 'Store Page',
    'description' => 'A page that displays products using paragraphs',
    'new_revision' => TRUE,
    'preview_mode' => 1,
    'status' => TRUE,
]);
$store_page_type->save();

// Create product grid paragraph type
$product_grid_type = ParagraphsType::create([
    'id' => 'product_grid',
    'label' => 'Product Grid',
    'description' => 'Displays a grid of products',
    'status' => TRUE,
]);
$product_grid_type->save();

// Create GraphQL fragment for product grid
$fragment = [
    'type' => 'product_grid',
    'label' => 'Product Grid',
    'description' => 'GraphQL fragment for product grid paragraph',
    'status' => TRUE,
    'query' => <<<'GRAPHQL'
fragment ProductGrid on Paragraph {
  id
  title
  fieldParagraphs {
    ...ProductCard
  }
}

fragment ProductCard on Paragraph {
  id
  title
  fieldProduct {
    title
    sku
    variations {
      price {
        amount
        currencyCode
      }
      sku
    }
  }
}
GRAPHQL
];

// Save the fragment
$fragment_manager = \Drupal::service('plugin.manager.graphql.fragment');
$fragment_manager->createInstance($fragment['type'], $fragment)->save();

print "Store page components created successfully!\n";
