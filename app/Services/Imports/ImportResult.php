<?php

namespace App\Services\Imports;

class ImportResult
{
    public function __construct(
        public readonly int $imported,
        public readonly string $method,
        public readonly int $skipped = 0,
    ) {}
}
