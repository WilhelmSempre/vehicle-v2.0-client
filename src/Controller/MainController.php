<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package App\Controller
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $parameters = [];

        return $this->render('Main/index.html.twig', $parameters);
    }
}