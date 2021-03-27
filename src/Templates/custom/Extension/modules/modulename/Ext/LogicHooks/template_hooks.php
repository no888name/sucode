<?php

$hook_version = 1;

$hook_array['before_save'][] = [
    '100', //order
    '', //description
    null, // file (null when using namespaces)
    \Sugarcrm\Sugarcrm\custom\hooks\:className::class,
    'beforeSave', // method
];

$hook_array['after_save'][] = [
    '100', //order
    '', //description
    null, // file (null when using namespaces)
    \Sugarcrm\Sugarcrm\custom\hooks\:className::class,
    'afterSave', // method
];



$hook_array['after_relationship_add'][] = [
    '100', //order
    '', //description
    null, // file (null when using namespaces)
    \Sugarcrm\Sugarcrm\custom\hooks\:className::class,
    'afterRel', // method
];
