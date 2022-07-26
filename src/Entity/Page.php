<?php

namespace SonataVue\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\HasLifecycleCallbacks]
class Page
{
	const RESPONSE_TYPE_JSON = 0;
	const RESPONSE_TYPE_HTML = 1;
	const RESPONSE_TYPE_TEXT = 2;
	const RESPONSE_TYPE_MANUAL = 3;

	const RESPONSE_TYPES = [
		'json'=>self::RESPONSE_TYPE_JSON,
		'html'=>self::RESPONSE_TYPE_HTML,
		'text'=>self::RESPONSE_TYPE_TEXT,
		'manual'=>self::RESPONSE_TYPE_MANUAL,
	];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $path;

	#[ORM\Column(type: 'boolean', nullable: false)]
	private bool $published;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $requirements = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private array $defaults = [];

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private \DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'pages')]
    #[ORM\JoinColumn(nullable: false)]
    private Site $site;

    #[ORM\ManyToOne(targetEntity: PageTemplate::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $template;

	#[ORM\Column(type: 'smallint', nullable: true)]
	private ?int $responseType;

	#[ORM\Column(type: 'json', nullable: true)]
	private ?array $slotsOptions = [];

	public function __construct()
	{
		$this->createdAt = new \DateTimeImmutable();
		$this->slotsOptions = [];
		$this->published = true;
		$this->responseType = self::RESPONSE_TYPE_JSON;
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

	public function isPublished(): bool
	{
		return $this->published;
	}

	public function setPublished(bool $published): self
	{
		$this->published = $published;
		return $this;
	}

	public function getResponseType():?int
	{
		return $this->responseType;
	}

	public function setResponseType(int $responseType):self
	{
		$this->responseType = $responseType;
		return $this;
	}

	#[ORM\PrePersist]
	#[ORM\PreUpdate]
	public function setUpdatedAtValue(): void
	{
		$this->updatedAt = new \DateTimeImmutable();
	}

	#[ORM\PrePersist]
	public function setCreatedAtValue(): void
	{
		$this->createdAt = new \DateTimeImmutable();
	}

	public function getSlotConfig(string $slot){
		$config = $this->getSlotsOptions()[$slot] ?? null;
		if(!is_null($config)){
			ksort($config);
		}
		return $config;
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
		if($oldNum===$newNum){
			return $this;
		}
		if(isset($options[$slot][$newNum])){
			throw new \Exception('Номер блока занят');
		}
		$config = $options[$slot][$oldNum];
		unset($options[$slot][$oldNum]);
		$options[$slot][$newNum] = $config;
		$this->setSlotsOptions($options);
		return $this;
	}

	private function prepareParams(array $params = null){
		if(is_null($params)){
			return [];
		}
		$result = [];
		foreach ($params as $param){
			$paramArray = explode(': ', $param);
			$paramArrayTmp = $paramArray;
			unset($paramArrayTmp[0]);
			$result[$paramArray[0]] = implode(': ', $paramArrayTmp);
			$result[$paramArray[0]] = $result[$paramArray[0]] ==='null' ? null : $result[$paramArray[0]];
		}
		return $result;
	}

	public function getPreparedRequirements(){
		return $this->prepareParams($this->getRequirements());
	}

	public function getPreparedDefaults(){
		return $this->prepareParams($this->getDefaults());
	}

}
