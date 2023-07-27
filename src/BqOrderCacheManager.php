<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils;

class BqOrderCacheManager
{
    private mixed $order = null;

    // fileHandle is a resource; this is a special variable, holding a reference to an external resource
    private $fileHandle;

    private string $cachePath;

    public function __construct(string $cachePath)
    {
        $this->cachePath = $cachePath;
    }
    public function __destruct()
    {
        $this->writeAndUnlock();
    }

    public function getOrder($orderNumber)
    {
        if (!is_null($this->order)) {
            throw new \Exception('Already a file locked!');
        }
        $orderPath = $this->cachePath . $orderNumber . '.json';
        if (!file_exists($orderPath)) {
            file_put_contents($orderPath, json_encode((object)[]));
        }
        $this->fileHandle = fopen($orderPath, 'r+');
        $this->order = $this->readAndLock($orderPath);
        return $this->order;
    }

    public function setOrder($orderData): void
    {
        $this->order = $orderData;
    }

    public function writeAndUnlock(): void
    {
        ftruncate($this->fileHandle, 0);    //Truncate the file to 0
        rewind($this->fileHandle);           //Set write pointer to beginning of file
        fwrite($this->fileHandle, json_encode($this->order, JSON_PRETTY_PRINT));    //Write the new Hit Count
        flock($this->fileHandle, LOCK_UN);
    }

    private function readAndLock($orderPath)
    {
        if (flock($this->fileHandle, LOCK_EX)) {
            return json_decode(fread($this->fileHandle, filesize($orderPath)));
        }
        throw new \Exception('FileHandle could not be locked!');
    }
}
