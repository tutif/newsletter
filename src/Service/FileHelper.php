<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\StorageException;

class FileHelper
{
    /**
     * @param string $filepath
     *
     * @return string
     *
     * @throws StorageException
     */
    public function getFileContents(string $filepath): string
    {
        if (!file_exists($filepath)) {
            throw new StorageException(sprintf('File does not exist "%s"', $filepath));
        }

        $contents = file_get_contents($filepath);
        if ($contents === false) {
            throw new StorageException(sprintf('Cannot read file "%s"', $filepath));
        }

        return $contents;
    }

    public function writeData(string $data, string $filepath): void
    {
        file_put_contents($filepath, $data);
    }
}
