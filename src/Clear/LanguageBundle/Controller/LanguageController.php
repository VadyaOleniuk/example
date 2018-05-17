<?php

namespace Clear\LanguageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Clear\SecurityBundle\Annotations\Securable;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;


class LanguageController extends FOSRestController
{
    /**
     * @Rest\Get("language")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_all_languages", actionName="Get all languages")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Languages API",
     *  description="Get all languages",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function indexAction(Request $request)
    {
        $language = $this->getDoctrine()->getRepository('ClearLanguageBundle:Language')->createQueryBuilder('language')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $language,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 50)
        );
        if (!$result) {
            throw $this->createNotFoundException(sprintf("Language does not exists"));
        }

        return $result;
    }
}
