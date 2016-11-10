<?php

namespace EduardoLedo\ServerSyncBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('EduardoLedoServerSyncBundle:Default:index.html.twig');
    }
}
