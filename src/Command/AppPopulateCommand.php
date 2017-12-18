<?php

namespace App\Command;

use App\Entity\Manufacturer;
use App\Entity\Product;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppPopulateCommand extends Command
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('app:populate')
            ->setDescription('This will populate DB with millions of rows');
    }

    /**
     * @throws DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        $io = new SymfonyStyle($input, $output);
        $this->truncateDb();

        $maxManufacturers = 1000;
        $maxProductsPerManufacturer = 10000;

        $this->insertManufacturers($io, $maxManufacturers);
        $io->writeln('');
        $io->writeln('Manufacturers created');
        $io->writeln('');

        $this->insertProducts($io, $maxProductsPerManufacturer, $maxManufacturers);
        $io->writeln('Products created');
    }

    private function insertManufacturers(SymfonyStyle $io, int $limit): void
    {
        $em = $this->em;
        $progressBar = $io->createProgressBar($limit);
        $faker = Factory::create();

        $batchSize = 20;
        for ($i = 1; $i <= $limit; ++$i) {
            $manufacturer = new Manufacturer($faker->company);
            $em->persist($manufacturer);
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear();
                $progressBar->advance($batchSize);
            }
        }
        $em->flush();
        $em->clear();
        $progressBar->finish();
    }

    private function insertProducts(SymfonyStyle $io, int $limitPerManufacturer, int $maxManufacturers): void
    {
        $em = $this->em;
        $progressBar = $io->createProgressBar($limitPerManufacturer * $maxManufacturers);
        $faker = Factory::create();
        $batchSize = 100;
        $repo = $this->em->getRepository(Manufacturer::class);

        $rows = $this->generateManufacturerIds();
        foreach ($rows as $row) {
            $id = $row[0]['id'];
            $manufacturer = $repo->find($id);
            for ($i = 1; $i <= $limitPerManufacturer; ++$i) {
                $product = new Product($faker->name);
                $manufacturer->addProduct($product);
                $em->persist($product);
                if (($i % $batchSize) === 0) {
                    $em->flush();
                    $em->clear();
                    $manufacturer = $repo->find($id);
                    $progressBar->advance($batchSize);
                }
            }
            $em->flush();
            $em->clear();
        }
        $progressBar->finish();
    }

    /**
     * @throws DBALException
     */
    private function truncateDb()
    {
        $rawSql = '
SET FOREIGN_KEY_CHECKS = 0;
truncate table tbl_manufacturer;
truncate table tbl_product;
SET FOREIGN_KEY_CHECKS = 1;
        ';

        $statement =$this->em->getConnection()->prepare($rawSql);
        $statement->execute();
    }

    private function generateManufacturerIds(): \Iterator
    {
        return $this->em->getRepository(Manufacturer::class)->createQueryBuilder('o')
            ->select('o.id')
            ->getQuery()->iterate(null, Query::HYDRATE_SCALAR);
    }
}



