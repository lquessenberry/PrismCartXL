<?php

use Drupal\node\Entity\Node;

// Get all nodes
$query = \Drupal::entityQuery('node')
    ->accessCheck(FALSE);

$nodes = $query->execute();

if (empty($nodes)) {
    print "No pages found. Please create a page first.\n";
    return;
}

print "Available pages:\n";
foreach ($nodes as $nid) {
    $node = Node::load($nid);
    print "- " . $node->getTitle() . " (ID: " . $node->id() . ")\n";
}
