<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    /** @var list<string> */
    private const GENRE_NAMES = [
        'Biopic',
        'Comedy',
        'Drame',
        'Famille',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::GENRE_NAMES as $genreName) {
            $genre = (new Genre())->setName($genreName);
            $manager->persist($genre);

            $this->addReference("Genre.{$genreName}", $genre);
        }

        $manager->flush();
    }
}
