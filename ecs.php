<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use whatwedo\PhpCodingStandard\Fixer\DumpFixer;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->skip([
        DumpFixer::class => null,
    ]);

    $ecsConfig->sets([__DIR__ . '/vendor/whatwedo/php-coding-standard/config/whatwedo-common.php']);
};
