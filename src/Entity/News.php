<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $link;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $pubDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Enclosure::class, inversedBy="news")
     */
    private $enclosures;

    public function __construct()
    {
        $this->enclosures = new ArrayCollection();
    }

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPubDate(): ?\DateTimeImmutable
    {
        return $this->pubDate;
    }

    public function setPubDate(\DateTimeImmutable $pubDate): self
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Enclosure[]
     */
    public function getEnclosures(): Collection
    {
        return $this->enclosures;
    }

    public function addEnclosure(Enclosure $enclosure): self
    {
        if (!$this->enclosures->contains($enclosure)) {
            $this->enclosures[] = $enclosure;
        }

        return $this;
    }

    public function removeEnclosure(Enclosure $enclosure): self
    {
        $this->enclosures->removeElement($enclosure);

        return $this;
    }
}
