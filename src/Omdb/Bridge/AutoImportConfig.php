<?php

declare(strict_types=1);

namespace App\Omdb\Bridge;

final class AutoImportConfig
{
    private bool|null $value = null;

    public function __construct(
        private readonly bool $default,
    ) {
    }

    public function skip(): void
    {
        $this->value = false;
    }

    public function restore(): void
    {
        $this->value = null;
    }

    public function getValue(): bool
    {
        return $this->value ?? $this->default;
    }
}
