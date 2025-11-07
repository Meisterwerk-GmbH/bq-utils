<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use whatwedo\PhpCodingStandard\Fixer\DumpFixer;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->skip([
        DumpFixer::class => null,
    ]);
    $ecsConfig->sets([__DIR__ . '/vendor/whatwedo/php-coding-standard/config/whatwedo-common.php']);
    $ecsConfig->ruleWithConfiguration(OrderedClassElementsFixer::class, [
        'order' => [
            'use_trait',
            'case',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'method_public',
            'method_protected',
            'method_private',
            'phpunit',
            'magic',
        ],
    ]);
};
