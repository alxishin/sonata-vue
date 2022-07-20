<?php

namespace SonataVue\Entity;

use App\Repository\PageTemplateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageTemplateRepository::class)]
class PageTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'json')]
    private $slots = [];

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

    public function getSlots(): ?array
    {
        return $this->slots;
    }

    public function setSlots(array $slots): self
    {
        $this->slots = $slots;

        return $this;
    }

	public function __toString(): string
	{
		return (string)$this->title;
	}


}
