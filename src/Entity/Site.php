<?php

namespace SonataVue\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private $robots;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: Page::class)]
    private $pages;

	#[ORM\Column(type: 'json', nullable: true)]
	private $hostNames;

	#[ORM\Column(type: 'boolean', nullable: false)]
	private $published;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
		$this->published = true;
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

    public function getRobots(): ?string
    {
        return $this->robots;
    }

    public function setRobots(?string $robots): self
    {
        $this->robots = $robots;

        return $this;
    }

    /**
     * @return Collection<int, Page>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages[] = $page;
            $page->setSite($this);
        }

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->pages->removeElement($page)) {
            // set the owning side to null (unless already changed)
            if ($page->getSite() === $this) {
                $page->setSite(null);
            }
        }

        return $this;
    }

	public function getHostNames():?array
	{
		return $this->hostNames;
	}

	public function setHostNames(?array $hostNames):self
	{
		$this->hostNames = $hostNames;
		return $this;
	}

	public function isPublished(): bool
	{
		return $this->published;
	}

	public function setPublished(bool $published): self
	{
		$this->published = $published;
		return $this;
	}

	public function __toString(): string
	{
		return (string)$this->title;
	}


}
