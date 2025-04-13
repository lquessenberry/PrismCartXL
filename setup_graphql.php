<?php

use Drupal\graphql\Plugin\GraphQL\Fragments\FragmentPluginManager;

// Get the fragment plugin manager
$fragment_manager = \Drupal::service('plugin.manager.graphql.fragment');

// Define the product grid fragment
$fragment = [
    'id' => 'product_grid',
    'label' => 'Product Grid',
    'description' => 'GraphQL fragment for product grid paragraph',
    'status' => TRUE,
    'query' => <<<'GRAPHQL'
fragment ProductGrid on Paragraph {
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

// Create or update the fragment
try {
    $existing_fragment = $fragment_manager->createInstance('product_grid');
    $existing_fragment->setConfiguration($fragment);
    $existing_fragment->save();
    print "Updated product grid fragment\n";
} catch (\Exception $e) {
    $fragment_manager->createInstance('product_grid', $fragment)->save();
    print "Created product grid fragment\n";
}
