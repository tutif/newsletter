<?php
declare(strict_types=1);

namespace App\Service;

class IdGenerator
{
    public function generateId(): string
    {
        return join('#', [
            mt_rand(1, 99999999),
            microtime(true)
        ]);
    }
}
