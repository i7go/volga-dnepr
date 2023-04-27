<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        'declare_strict_types' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,
        '@PHP80Migration:risky' => true,
        '@PHP82Migration' => true,
    ])
    ->setFinder($finder)
;
