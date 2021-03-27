<?php

$manifest = [
    'name' => ':UpperCamelName',
    'version' => '1.0.0',
    'published_date' => ':date',

    'key' => ':UpperCamelName',
    'author' => 'MyCRM GmbH',
    'description' => ':description',
    'icon' => '',
    'readme' => 'README',

    'type' => 'module',
    'is_uninstallable' => 'true',
    'remove_tables' => 'prompt',

    'acceptable_sugar_versions' => [
        'regex_matches' => ['9.*','10.*','11.*','12.*'],
    ],
    'acceptable_sugar_flavors' => ['ENT', 'ULT', 'PRO', 'CORP'],
];

$installdefs = [
    'id' => ':UpperCamelName',
    'beans' => [],
    'layoutdefs' => [],
    'relationships' => [],
    'dependencies' => [],
    'copy' => [
        //modules  Ext files
        [
            'from' => '<basepath>/custom/',
            'to' => 'custom/',
        ],
    ],
    'vardefs' => [
    ],
    'language' => [],
    'logic_hooks' => [],
    'layoutfields' => [],
    'custom_fields' => [],
    'post_execute' => [],
    'post_uninstall' => [
//        '<basepath>/scripts/post_uninstall.php', //todo: enable if needed
    ],
];
