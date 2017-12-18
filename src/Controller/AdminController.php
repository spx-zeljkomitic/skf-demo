<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\AdminConfigCollector;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Elastica\Query;

class AdminController
{
    /**
     * @Route("/", methods={"GET"})
     * @Template("admin_base.html.twig")
     */
    public function defaultAction()
    {
        return [];
    }

    /**
     * @Route("/{segment}", name="admin_segment")
     * @Method("GET")
     * @Template("admin_segment.html.twig")
     */
    public function segmentAction(AdminConfigCollector $adminConfigCollector, Request $request, TransformedFinder $productsFinder)
    {
        $fieldQuery = new Query\Match();
        $fieldQuery->setField('manufacturer', '1');


        $query = new Query(
            $fieldQuery
        );
        $query->setSort(['id' => ['order' => 'asc']]);

        $pagerFanta = $productsFinder->findPaginated($query);
        $segment = $request->attributes->getAlnum('segment');
        $config = $adminConfigCollector->getConfigForSegment($segment);

//        $pagerFanta = $productsFinder->findPaginated($fieldQuery);

//        $pagerFanta = $config->getPagination();
        $page = $request->query->getInt('page', 1);
        $pagerFanta->setMaxPerPage(10);
        $pagerFanta->setCurrentPage($page);

        $columns = $config->getColumns();

        return [
            'columns' => $columns,
            'pager' => $pagerFanta,
        ];
    }
}

