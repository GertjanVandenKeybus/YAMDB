<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'tmdb_id', type: 'integer')]
    private int $tmdbId;

    #[ORM\Column(name: 'title', type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(name: 'director', type: 'string', length: 255, nullable: true)]
    private string $director;

    #[ORM\Column(name: 'release_year', type: 'integer', nullable: true)]
    private int $releaseYear;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private string $description;

    #[ORM\Column(name: 'poster_url', type: 'string', length: 255, nullable: true)]
    private string $posterUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(?int $releaseYear): self
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTmdbId(): int
    {
        return $this->tmdbId;
    }

    /**
     * @param mixed $tmdb_id
     */
    public function setTmdbId($tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }

    /**
     * @return string
     */
    public function getPosterUrl(): string
    {
        return $this->posterUrl;
    }

    /**
     * @param string $posterUrl
     */
    public function setPosterUrl(string $posterUrl): void
    {
        $this->posterUrl = $posterUrl;
    }
}
