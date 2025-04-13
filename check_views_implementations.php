<?php

// Check for any custom implementations of hook_views_data()
$modules = \Drupal::service('extension.list.module')->getList();
foreach ($modules as $module => $info) {
    if (file_exists($info->getPath() . '/' . $module . '.module')) {
        $content = file_get_contents($info->getPath() . '/' . $module . '.module');
        if (strpos($content, 'hook_views_data') !== false) {
            print "Module {$module} has hook_views_data implementation\n";
        }
    }
}
