<?php

use \PhpCsFixer\Config;
use \PhpCsFixer\Finder;

$rules = [
    '@PSR12' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short']
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())->setFinder($finder)->setRules($rules)->setRiskyAllowed(false)->setUsingCache(true);
