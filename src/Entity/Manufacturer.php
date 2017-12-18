<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManufacturerRepository")
 * @ORM\Table("tbl_manufacturer")
 */
class Manufacturer
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
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="manufacturer", fetch="EXTRA_LAZY")
     */
    private $products;

    /**
     * @ORM\Column(type="integer", name="agg_products_count")
     */
    private $productsCount = 0;

    public function __construct(string $name = null)
    {
        $this->name = (string)$name;
        $this->products = new ArrayCollection();
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

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products->toArray();
    }

    public function addProduct(Product $product): void
    {
        if ($this->products->contains($product)) {
            return;
        }
        $this->products->add($product);
        $product->setManufacturer($this);
        $this->productsCount ++;
    }

    public function getProductsCount(): int
    {
        return $this->productsCount;
    }
}

