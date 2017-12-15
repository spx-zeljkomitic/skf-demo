<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     * @Method("GET")
     *
     */
    public function defaultAction()
    {
        echo 'GET';die;
    }

    /**
     * @Route("/")
     * @Method("POST")
     */
    public function postAction()
    {
        echo 'post';die;
    }
}