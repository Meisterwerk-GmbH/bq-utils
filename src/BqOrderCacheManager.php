<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils;

class BqOrderCacheManager
{
    private FileManager $fileManager;

    public function __construct(string $cachePath)
    {
        $this->fileManager = new FileManager($cachePath);
    }

    public function getOrder($orderNumber)
    {
        return $this->fileManager->getFileData($orderNumber);
    }

    public function setOrder($orderData): void
    {
        $this->fileManager->setFileData($orderData);
    }
}
