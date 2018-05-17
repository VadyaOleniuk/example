<?php

namespace Clear\PagesBundle\Controller;

use Clear\PagesBundle\Entity\Pages;
use Clear\PagesBundle\Form\AskType;
use Clear\PagesBundle\Form\FormBuilderType;
use Clear\PagesBundle\Form\HomePagesType;
use Clear\PagesBundle\Form\PageType;
use Clear\PagesBundle\Model\BuilderPage;
use Clear\PagesBundle\Model\HomePages;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Clear\PagesBundle\Deleter\TypeInit;
use Clear\SecurityBundle\Annotations\Securable;

class PagesController extends FOSRestController
{
    private $request;
    private $type;

    private $isUpdate = false;

    /**
     * @Rest\Post("page-list/{type}", requirements={"type" = "page|homepage|ask"})
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @Securable(actionId="create_pages", actionName="Create page")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Page API",
     *  description="Create new content",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\PagesBundle\Entity\Pages",
     *  input="Clear\PagesBundle\Form\FormBuilderType"
     * )
     */
    public function createPagesAction(Request $request, $type)
    {
        $this->request = $request;
        $this->type = $type;
        return $this->validationPage(new Pages());
    }

    /**
     * @Rest\Post("page-list/{type}/{id}", requirements={"type" = "page|homepage|ask"})
     *
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @Securable(actionId="create_pages", actionName="Update page")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Page API",
     *  description="Create new content",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\PagesBundle\Entity\Pages",
     *  input="Clear\PagesBundle\Form\FormBuilderType"
     * )
     */
    public function editPagesAction(Request $request, $type, $id)
    {
        $page = $this->getDoctrine()->getRepository('ClearPagesBundle:Pages')->find($id);
        $this->request = $request;
        $this->type = $type;
        return $this->validationPage($page);

    }

    /**
     * @Rest\Delete("/page-list/{id}")
     * @Rest\View
     * @Securable(actionId="create_pages", actionName="Delete page")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Page API",
     *  description="Create new content",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deletePagesAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $page = $this->getDoctrine()->getRepository('ClearPagesBundle:Pages')->find($id);

        if (!$page) {
            throw $this->createNotFoundException(sprintf("Page with id=%s does not exists", $id));
        }

        $typePage =  BuilderPage::getClass($page->getType(), $this->getDoctrine()->getRepository("ClearPagesBundle:Pages")->createQueryBuilder("pages"));
        if($typePage->isDelete($page)) {
            $manager->remove($page);
            $manager->flush();

            return sprintf("Page with id=%s deleted", $id);
        }else{
            throw $this->createNotFoundException(sprintf($page->getType()." is published id=%s", $id));
        }
    }

    /**
     * @Rest\Get("/page-list")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="create_pages", actionName="Get all pages")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Page API",
     *  description="Create new content",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */

    public function getAllPageAction(Request $request)
    {

        $pages = $this->getDoctrine()->getRepository('ClearPagesBundle:Pages')->createQueryBuilder('pages')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $pages,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );
        if (!$result) {
            throw $this->createNotFoundException(sprintf("Pages does not exists"));
        }

        return $result;
    }

    /**
     * @Rest\Get("/page-list/{id}")
     *
     * @Securable(actionId="create_pages", actionName="Get page")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Page API",
     *  description="Create new content",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getPageAction($id)
    {
        $page = $this->getDoctrine()->getRepository('ClearPagesBundle:Pages')->find($id);
        if (empty($page)) {
            throw $this->createNotFoundException(sprintf("Page with id=%s does not exists", $id));
        }
        return $page;
    }

    private function validationPage($page)
    {
        /** @var $page Pages */

        $typeObject = TypeInit::getPage($this->type, $this->getDoctrine()->getManager(), $this->container);

        if (null !== $page->getId()) {
            $oldNames = $typeObject->getFileNames($page->getContent());
            $this->isUpdate = true;
        }

        $form = $this->createForm(FormBuilderType::class, $page);

        $form->handleRequest($this->request);

        if($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $typePage =  BuilderPage::getClass($this->type, $this->getDoctrine()->getRepository("ClearPagesBundle:Pages")->createQueryBuilder("pages"));
            $contentForm = $this->createForm($typePage->getClassForm(),null, $typePage->getParams($manager));

            $contentForm->submit($page->getContent());

            if($contentForm->isValid()){
                $page->setType($this->type);

                $typePage->getStatus($page);

                $page->setContent($contentForm->getData());

                if (true === $this->isUpdate) {
                    $newNames = $typeObject->getFileNames($page->getContent());
                    $needDelete = $typeObject->getFileNamesForDelete($oldNames, $newNames);
                    $typeObject->deletePageFiles($needDelete);
                }

                $manager->persist($page);
                $manager->flush();
                return $page;
            }
            return $contentForm;
        }
        return $form;
    }

}

