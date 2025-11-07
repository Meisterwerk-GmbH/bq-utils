<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->skip([
        SlevomatCodingStandard\Sniffs\Variables\UnusedVariableSniff::class => null,

        // Explicitly remove some rules in a specific files
        PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer::class => [
            __DIR__ . '/PATH/FILE.php'
        ],
    ]);

    $ecsConfig->sets([__DIR__ . '/vendor/whatwedo/php-coding-standard/config/whatwedo-common.php']);
};
