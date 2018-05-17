<?php

namespace Clear\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Clear\SecurityBundle\Annotations\Securable;

class DefaultController extends Controller
{
    /**
     * @Route("/test", name="test_action")
     * @Securable(actionId="default_action", actionName="Default action")
     */
    public function indexAction()
    {
        return new Response("status 200", Response::HTTP_OK);
    }
}
