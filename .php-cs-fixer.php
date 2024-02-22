<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->exclude([
        '.build/',
        '.github/',
    ])
    ->in(__DIR__);

$config = new Config();

$config
    ->setFinder($finder)
    ->setRules([
        '@PSR12' => true,
    ]);

return $config;
