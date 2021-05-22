<?php


namespace Avasoft\Component\Consul\Entity;


class Service
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $tag;

    /**
     * @var array
     */
    private array $tags;

    /**
     * @var string
     */
    private string $address;

    /**
     * @var int
     */
    private int $port;

    /**
     * @var array
     */
    private array $checks;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        if(empty($this->tags)) {
            $this->tags = [];
        }
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @param Check $check
     */
    public function addCheck(Check $check):void
    {
        $this->checks[$check->getHttp()]  = $check;
    }

    /**
     * @param Check $check
     */
    public function removeChecks(Check $check):void
    {
        if(array_key_exists($check->getHttp(), $this->getChecks())) {
            unset($this->checks[$check->getHttp()]);
        }
    }

    /**
     * @return array
     */
    public function getChecks(): array
    {
        $checks = [];
        if(!empty($this->checks)) {
            foreach($this->checks as $i => $check) {
                $checks[$i] = $check->getCheck();
            }
            $checks = array_values($checks);
        }

        return $checks;
    }
}