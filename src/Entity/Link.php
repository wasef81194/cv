<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkRepository::class)]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $github;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $web;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $video;

    #[ORM\OneToOne(mappedBy: 'links', targetEntity: Article::class, cascade: ['persist', 'remove'])]
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): self
    {
        $this->web = $web;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        // unset the owning side of the relation if necessary
        if ($article === null && $this->article !== null) {
            $this->article->setLinks(null);
        }

        // set the owning side of the relation if necessary
        if ($article !== null && $article->getLinks() !== $this) {
            $article->setLinks($this);
        }

        $this->article = $article;

        return $this;
    }
}
