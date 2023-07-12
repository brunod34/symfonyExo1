<?php

declare(strict_types=1);

namespace App\Omdb\Bridge;

use App\Omdb\Client\ApiClientInterface;
use App\Omdb\Client\Model\Movie;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(ApiClientInterface::class)]
final class AutoImportApiClient implements ApiClientInterface
{
    public function __construct(
        private readonly ApiClientInterface $client,
        private readonly DatabaseImporter $importer,
    ) {
    }

    public function getById(string $imdbID): Movie
    {
        $movieModel = $this->client->getById($imdbID);

//        $this->importer->import($movieModel, false);

        return $movieModel;
    }

    public function searchByTitle(string $title): array
    {
        return $this->client->searchByTitle($title);
    }
}
