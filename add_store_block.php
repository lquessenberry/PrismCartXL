<?php

use Drupal\block\Entity\Block;
use Drupal\node\Entity\Node;

// Create the store block
$block = Block::create([
    'id' => 'commerce_store_block',
    'plugin' => 'commerce_store_block',
    'region' => 'content',
    'settings' => [],
    'weight' => 0,
    'theme' => 'commerce_store_theme',
    'status' => TRUE,
]);
$block->save();

// Load the store page
$store_page = Node::load(68);
if ($store_page) {
    // Add the block to the store page
    $block_content = \Drupal::entityTypeManager()
        ->getStorage('block_content')
        ->create([
            'info' => 'Store Block',
            'type' => 'basic',
            'field_block' => [
                'target_id' => $block->id(),
            ],
        ]);
    $block_content->save();

    // Add the block content to the store page
    $store_page->field_paragraphs[] = [
        'target_id' => $block_content->id(),
    ];
    $store_page->save();
}

print "Store block added to store page\n";
