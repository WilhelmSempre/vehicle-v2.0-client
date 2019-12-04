<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/offline", name="offline_")
 *
 * Class OfflineController
 * @package App\Controller
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class OfflineController extends AbstractController
{


    /**
     * @Route("/index", name="index")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $parameters = [];

        return $this->render('Offline/index.html.twig', $parameters);
    }
}