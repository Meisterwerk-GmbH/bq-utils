<?php

namespace Meisterwerk\BqUtils;

class FileManager
{
    private mixed $fileData = null;

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

    public function getFileData($fileName)
    {
        if (!is_null($this->fileData)) {
            throw new \Exception('Already a file locked!');
        }
        $filePath = $this->cachePath . $fileName . '.json';
        if (!file_exists($filePath)) {
            file_put_contents($filePath, json_encode((object)[]));
        }
        $this->fileHandle = fopen($filePath, 'r+');
        $this->fileData = $this->readAndLock($filePath);
        return $this->fileData;
    }

    public function setFileData($data): void
    {
        $this->fileData = $data;
    }

    public function writeAndUnlock(): void
    {
        ftruncate($this->fileHandle, 0);    //Truncate the file to 0
        rewind($this->fileHandle);           //Set write pointer to beginning of file
        fwrite(
            $this->fileHandle,
            json_encode($this->fileData, JSON_PRETTY_PRINT)
        );    //Write the new Hit Count
        flock($this->fileHandle, LOCK_UN);
    }

    private function readAndLock($filePath)
    {
        if (flock($this->fileHandle, LOCK_EX)) {
            return json_decode(fread($this->fileHandle, filesize($filePath)));
        }
        throw new \Exception('FileHandle could not be locked!');
    }
}