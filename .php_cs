<?php
$finder = PhpCsFixer\Finder::create()
    ->exclude(['tests', 'node_modules', 'web', 'config','vendor','assets'])
    ->notPath('config/listeners.php')
    ->in([
        __DIR__ . '/src/custom',
    ]);
$config = PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRules([
        '@Symfony' => true,
        'phpdoc_align' => true,
        'phpdoc_summary' => false,
        'phpdoc_no_empty_return' => false,
        'phpdoc_inline_tag' => false,
        'pre_increment' => false,
        'heredoc_to_nowdoc' => false,
        'cast_spaces' => false,
        'include' => false,
        'phpdoc_no_package' => false,
        'concat_space' => ['spacing' => 'one'],
        'ordered_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'yoda_style' => true
    ])
    ->setFinder($finder);
return $config;