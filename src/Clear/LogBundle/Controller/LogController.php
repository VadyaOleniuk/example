<?php

namespace Clear\LogBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Clear\LogBundle\Entity\Log;
use Symfony\Component\HttpFoundation\Request;

class LogController extends FOSRestController
{
    /**
     * @Rest\Get("get-log-data/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getLogDataForContentAction(Request $request, $id)
    {
        $logsData = $this->getDoctrine()->getRepository(Log::class)->getAllLogsOrderByTime($id);

        $result = $this->get('knp_paginator')->paginate(
            $logsData,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Logs for content with id=%s does not exists", $id));
        }

        return $result;
    }
}
