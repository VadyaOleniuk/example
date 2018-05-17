<?php

namespace Clear\ContentBundle\Controller;

use Clear\ContentBundle\Entity\ContentType;
use Clear\ContentBundle\Form\ContentTypeType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Clear\SecurityBundle\Annotations\Securable;

class ContentTypeController extends FOSRestController
{
    /**
     * @Rest\Get("contentTypes")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_all_content-types", actionName="Get all content-types")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content type API",
     *  description="Get all content types",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllContentTypesAction(Request $request)
    {
        $contentTypes = $this->getDoctrine()->getRepository('ClearContentBundle:ContentType')->createQueryBuilder('contentType')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $contentTypes,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("ContentTypes does not exists"));
        }

        return $result;
    }

    /**
     * @Rest\Get("contentType/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_content-type_by_id", actionName="Get content-type by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content type API",
     *  description="Get  content type by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getContentTypeAction($id)
    {
        $contentType = $this->getDoctrine()->getRepository('ClearContentBundle:ContentType')->find($id);

        if (!$contentType) {
            throw $this->createNotFoundException(sprintf("ContentType with id=%s does not exists", $id));
        }

        return $contentType;
    }

    /**
     * @Rest\Delete("/contentType/{id}")
     * @Rest\View
     * @Securable(actionId="delete_content-type_by_id", actionName="Delete content-type by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content type API",
     *  description="Delete content type by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteContentTypeAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $contentType = $this->getDoctrine()->getRepository('ClearContentBundle:ContentType')->find($id);

        if (!$contentType) {
            throw $this->createNotFoundException(sprintf("ContentType with id=%s does not exists", $id));
        }

        $manager->remove($contentType);
        $manager->flush();

        return sprintf("ContentType with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/contentType")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="create_content-type", actionName="Create content-type")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content type API",
     *  description="Create new content type",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\ContentBundle\Entity\ContentType",
     *  input="Clear\ContentBundle\Form\ContentTypeType"
     * )
     */

    public function createContentTypeAction(Request $request)
    {
        $contentType = new ContentType();
        $form = $this->createForm(ContentTypeType::class, $contentType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($contentType);
            $manager->flush();

            return $contentType;
        }

        return $form;
    }

    /**
     * @Rest\Put("/contentType/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="edit_content-type_by_id", actionName="Edit content-type by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content type API",
     *  description="Edit content type",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\ContentBundle\Entity\ContentType",
     *  input="Clear\ContentBundle\Form\ContentTypeType"
     * )
     */
    public function editContentTypeAction(Request $request, $id)
    {
        $contentType = $this->getDoctrine()->getRepository('ClearContentBundle:ContentType')->find($id);
        $form = $this->createForm(ContentTypeType::class, $contentType, ['method' => 'Put']);

        $form->handleRequest($request);
        if ($contentType) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($contentType);
                $manager->flush();

                return $contentType;
            }
        } else {
            throw $this->createNotFoundException(sprintf("Content type with id=%s not found", $id));
        }

        return $form;
    }

}
