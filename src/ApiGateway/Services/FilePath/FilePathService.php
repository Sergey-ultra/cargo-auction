<?php

declare(strict_types=1);

namespace App\ApiGateway\Services\FilePath;

use DomainException;

class FilePathService
{
    public function createIfNotExistFolder(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            $old = umask(0);
            if (!mkdir($dirPath, 0777, true) && !is_dir($dirPath)) {
                throw new DomainException('Error create folder');
            }
            umask($old);
        }
    }
}