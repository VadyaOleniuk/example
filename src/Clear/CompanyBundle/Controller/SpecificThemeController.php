<?php

namespace Clear\CompanyBundle\Controller;

use Clear\CompanyBundle\Entity\SpecificTheme;
use Clear\CompanyBundle\Form\SpecificThemeType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class SpecificThemeController extends FOSRestController
{
    /**
     * @Rest\Get("specific")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Specific API",
     *  description="Get all specifics",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllSpecificAction(Request $request)
    {
        $specifics = $this->getDoctrine()->getRepository('ClearCompanyBundle:SpecificTheme')->createQueryBuilder('specific')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $specifics,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Specifics does not exists"));
        }

        return $result;

    }


    /**
     * @Rest\Get("specific/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Specific API",
     *  description="Get specific by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getSpecificAction($id)
    {
        $specific = $this->getDoctrine()->getRepository('ClearCompanyBundle:SpecificTheme')->find($id);

        if (!$specific) {
            throw $this->createNotFoundException(sprintf("Specific with id=%s does not exists", $id));
        }

        return  $specific;
    }

    /**
     * @Rest\Delete("/specific/{id}")
     * @Rest\View
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Specific API",
     *  description="Delete specific by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteSpecificAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $specific = $this->getDoctrine()->getRepository('ClearCompanyBundle:SpecificTheme')->find($id);

        if (!$specific) {
            throw $this->createNotFoundException(sprintf("Specific with id=%s does not exists", $id));
        }

        $manager->remove($specific);
        $manager->flush();

        return sprintf("Specific with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/specific")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Specific API",
     *  description="Create new specific",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\CompanyBundle\Entity\SpecificTheme",
     *  input="Clear\CompanyBundle\Form\SpecificThemeType"
     * )
     */

    public function createSpecificAction(Request $request)
    {
        $specific = new SpecificTheme();
        $form = $this->createForm(SpecificThemeType::class, $specific);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($specific);
            $manager->flush();

            return $specific;
        }

        return $form;
    }

    /**
     * @Rest\Put("/specific/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Specific API",
     *  description="Edit specific",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\CompanyBundle\Entity\SpecificTheme",
     *  input="Clear\CompanyBundle\Form\SpecificThemeType"
     * )
     */
    public function editSpecificAction(Request $request, $id)
    {
        $specific = $this->getDoctrine()->getRepository('ClearCompanyBundle:SpecificTheme')->find($id);
        $form = $this->createForm(SpecificThemeType::class, $specific, ['method' => 'Put']);

        $form->handleRequest($request);
        if ($specific) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($specific);
                $manager->flush();

                return $specific;
            }
        } else {
            throw $this->createNotFoundException(sprintf("Specific with id=%s not found", $id));
        }

        return $form;
    }
}
