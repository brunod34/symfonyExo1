<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Genre as GenreEntity;
use App\Entity\Movie as MovieEntity;
use App\Omdb\Client\Model\Movie as MovieOmdbModel;
use DateTimeImmutable;
use function array_map;
use function explode;
use function str_starts_with;

final class Movie
{
    /**
     * @param list<string> $genres
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $title,
        public readonly string $plot,
        public readonly string $poster,
        public readonly Rating $rated,
        public readonly DateTimeImmutable $releasedAt,
        public readonly array $genres,
    ) {
    }

    public function year(): string
    {
        return $this->releasedAt->format('Y');
    }

    public function isRemotePoster(): bool
    {
        return str_starts_with($this->poster, 'http');
    }

    public static function fromEntity(MovieEntity $movieEntity): self
    {
        return new self(
            slug: $movieEntity->getSlug(),
            title: $movieEntity->getTitle(),
            plot: $movieEntity->getPlot(),
            poster: $movieEntity->getPoster(),
            rated: $movieEntity->getRated(),
            releasedAt: $movieEntity->getReleasedAt(),
            genres: array_map(
                fn(GenreEntity $genreEntity): string => $genreEntity->getName(),
                $movieEntity->getGenres()->toArray()
            ),
        );
    }

    /**
     * @param list<MovieEntity> $movieEntities
     *
     * @return list<self>
     */
    public static function fromEntities(array $movieEntities): array
    {
        return array_map(self::fromEntity(...), $movieEntities);
    }

    public static function fromOmdbModel(MovieOmdbModel $movieOmdbModel): self
    {
        return new self(
            slug: 'todo',
            title: $movieOmdbModel->Title,
            plot: $movieOmdbModel->Plot,
            poster: $movieOmdbModel->Poster,
            rated: Rating::tryFrom($movieOmdbModel->Rated) ?? Rating::GeneralAudiences,
            releasedAt: new DateTimeImmutable($movieOmdbModel->Released),
            genres: explode(', ', $movieOmdbModel->Genre),
        );
    }
}
