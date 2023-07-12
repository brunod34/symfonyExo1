<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use App\Omdb\Client\Model\Movie;

interface ApiClientInterface
{
    /**
     * @throws NoResult When the $imdbID was not found.
     */
    public function getById(string $imdbID): Movie;
}
