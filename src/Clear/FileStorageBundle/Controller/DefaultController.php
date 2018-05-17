<?php

namespace Clear\FileStorageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/file-storage", name="test_action_file")
     */
    public function indexAction()
    {
        return new Response("status 200", Response::HTTP_OK);
    }
}