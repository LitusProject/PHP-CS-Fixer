<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('Resources')
    ->in(__DIR__);

return Litus\CodeStyle\Config\Config::create()
    ->setLicense(__DIR__ . '/.license_header')
    ->finder($finder);
