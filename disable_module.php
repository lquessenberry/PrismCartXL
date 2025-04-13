<?php

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ModuleInstallerInterface;

// Get module handler and installer
$module_handler = \Drupal::service('module_handler');
$module_installer = \Drupal::service('module_installer');

// Check if module is installed
if ($module_handler->moduleExists('commerce_demo_generator')) {
    // Disable the module
    $module_installer->uninstall(['commerce_demo_generator']);
    print "Commerce demo generator module disabled\n";
} else {
    print "Commerce demo generator module not found\n";
}
