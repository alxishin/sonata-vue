<?php

namespace SonataVue\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $path;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $publishedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $publishedUntil;

    #[ORM\Column(type: 'json', nullable: true)]
    private $requirements = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private $defaults = [];

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'pages')]
    #[ORM\JoinColumn(nullable: false)]
    private $site;

    #[ORM\ManyToOne(targetEntity: PageTemplate::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $template;

	#[ORM\Column(type: 'json', nullable: true)]
	private $slotsOptions = [];

	public function __construct()
	{
		$this->createdAt = new \DateTimeImmutable();
		$this->slotsOptions = [];
	}


	public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getPublishedUntil(): ?\DateTimeImmutable
    {
        return $this->publishedUntil;
    }

    public function setPublishedUntil(?\DateTimeImmutable $publishedUntil): self
    {
        $this->publishedUntil = $publishedUntil;

        return $this;
    }

    public function getRequirements(): ?array
    {
        return $this->requirements;
    }

    public function setRequirements(?array $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getDefaults(): ?array
    {
        return $this->defaults;
    }

    public function setDefaults(?array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getTemplate(): ?PageTemplate
    {
        return $this->template;
    }

    public function setTemplate(?PageTemplate $template): self
    {
        $this->template = $template;

        return $this;
    }

	public function getSlotsOptions(): ?array
	{
		return $this->slotsOptions;
	}

	private function setSlotsOptions(?array $slotsOptions): self
	{
		$this->slotsOptions = $slotsOptions;

		return $this;
	}

	public function getSlotMap(){
		return $this;
	}
	public function getSlotConfig(string $slot){
		return $this->getSlotsOptions()[$slot] ?? [['config'=>[],'service'=>null]];
	}

	public function setSlotConfig(string $slot, string $service, int $num, array $config){
		$options = $this->getSlotsOptions();
		$options[$slot][$num] = ['service'=>$service, 'config'=>$config];
		$this->setSlotsOptions($options);
		return $this;
	}

	public function removeFromSlot(string $slot, int $num):self{
		$options = $this->getSlotsOptions();
		unset($options[$slot][$num]);
		$this->setSlotsOptions($options);
		return $this;
	}

	public function changeNum(string $slot, int $oldNum, int $newNum):self{
		$options = $this->getSlotsOptions();
		$config = $options[$slot][$oldNum];
		unset($options[$slot][$oldNum]);
		$options[$slot][$newNum] = $config;
		$this->setSlotsOptions($options);
		return $this;
	}
}
