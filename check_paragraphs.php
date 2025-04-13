<?php

$paragraph_types = \Drupal::entityTypeManager()
    ->getStorage('paragraphs_type')
    ->loadMultiple();

print "Paragraph Types:\n";
foreach ($paragraph_types as $type_id => $type) {
    print "- $type_id: " . $type->label() . "\n";
}

print "\nFields:\n";
$entity_type_manager = \Drupal::entityTypeManager();
$paragraph_field_storage = $entity_type_manager
    ->getStorage('field_storage_config')
    ->loadMultiple();

foreach ($paragraph_field_storage as $field_id => $field) {
    if ($field->getTargetEntityTypeId() === 'paragraph') {
        print "- $field_id\n";
    }
}
