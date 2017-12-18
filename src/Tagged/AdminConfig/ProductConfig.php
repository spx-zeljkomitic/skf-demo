<?php

namespace App\Tagged\AdminConfig;

use App\Model\AdminConfigInterface;
use App\Repository\ProductRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class ProductConfig implements AdminConfigInterface
{
    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getName(): string
    {
        return 'products';
    }

    public function getPagination(): Pagerfanta
    {
        $qb = $this->productRepository->createQueryBuilder('o');
        $adapter = new DoctrineORMAdapter($qb);

        return new Pagerfanta($adapter);
    }

    public function getColumns(): array
    {
        return [
            'id',
            'name',
            'manufacturer',
        ];
    }
}

