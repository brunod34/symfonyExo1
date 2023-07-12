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
        private readonly AutoImportConfig $config,
    ) {
    }

    public function getById(string $imdbID): Movie
    {
        $movieModel = $this->client->getById($imdbID);

        if ($this->config->getValue() === true) {
            $this->importer->import($movieModel, true);
        }

        return $movieModel;
    }

    public function searchByTitle(string $title): array
    {
        return $this->client->searchByTitle($title);
    }
}
