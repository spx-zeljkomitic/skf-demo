<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table("tbl_product", indexes={@ORM\Index(name="name_idx", columns={"name"})})
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var Manufacturer
     * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $manufacturer;

    public function __construct(string $name = null)
    {
        $this->name = (string)$name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return (string)$this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getManufacturer(): Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(Manufacturer $manufacturer): void
    {
        if ($this->manufacturer === $manufacturer) {
            return;
        }
        $this->manufacturer = $manufacturer;
        $manufacturer->addProduct($this);
    }
}

