<?php

namespace App\Entity;

use App\Model\Rating;
use App\Repository\MovieRepository;
use App\Validator\Constraints\ValidMoviePoster;
use App\Validator\Constraints\ValidMovieSlug;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('slug')]
#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQUE_IDX_MOVIE_SLUG', fields: ['slug'])]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class Movie
{
    public const SLUG_FORMAT = '\d{4}-\w+(-\w+)*';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ValidMovieSlug]
    #[Assert\Length(min: 8, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 20)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $plot = null;

    #[Assert\NotBlank()]
    #[Assert\LessThanOrEqual('+100 years')]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $releasedAt = null;

    #[Assert\NotNull()]
    #[ValidMoviePoster()]
    #[ORM\Column(length: 255)]
    private ?string $poster = null;

    #[Assert\Count(min: 1)]
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'movies')]
    private Collection $genres;

    #[ORM\Column(length: 8, enumType: Rating::class, options: ['default' => 'G'])]
    private ?Rating $rated = Rating::GeneralAudiences;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string|null $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string|null $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string|null $plot): static
    {
        $this->plot = $plot;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeImmutable
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(\DateTimeImmutable|null $releasedAt): static
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string|null $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function getRated(): Rating|null
    {
        return $this->rated;
    }

    public function setRated(Rating|null $rated): static
    {
        $this->rated = $rated;

        return $this;
    }
}
