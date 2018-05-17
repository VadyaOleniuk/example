<?php

namespace Clear\ContentBundle\Controller;

use Clear\ContentBundle\Entity\Content;
use Clear\ContentBundle\Form\ContentType;
use Clear\ContentBundle\Form\ContentTypeType;
use Clear\ContentBundle\Form\StatusType;
use Clear\ContentBundle\Form\TypeSpecificContentType;
use Clear\ContentBundle\Model\FormsConstructor;
use Clear\FileStorageBundle\Entity\FileStorage;
use Clear\UserBundle\Entity\Role;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Clear\SecurityBundle\Annotations\Securable;
use Clear\UserBundle\Helper\Access;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Clear\UserBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Clear\LogBundle\Events\ContentEvent;
use Clear\PagesBundle\Deleter\TypeInit;


class ContentController extends FOSRestController
{

    const PUBLISHED = 1;
    const ARCHIVED = 2;
    const DRAFT = 0;


    /**
     * @Rest\Get("content")
     * @Rest\View( serializerEnableMaxDepthChecks=true, serializerGroups={"details"})
     * @Securable(actionId="get_all_contents", actionName="Get all contents")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content API",
     *  description="Get all contents",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllContentAction(Request $request)
    {
        $contentsDoctrine = $this->getDoctrine()->getRepository('ClearContentBundle:Content');
        // $contents = $this->getDoctrine()->getRepository('ClearContentBundle:Content')->createQueryBuilder('content')->getQuery();
        $contents = $contentsDoctrine->filterContentByRole($this->getUser());

        $result = $this->get('knp_paginator')->paginate(
            $contents,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 50)
        );
        if (!$result) {
            throw $this->createNotFoundException(sprintf("Contents does not exists"));
        }

        return $result;
    }

    /**
     * @Rest\Get("content/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_content_by_id", actionName="Get content by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content API",
     *  description="Get  content by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getContentAction($id)    
    {

        $content = $this->getDoctrine()->getRepository('ClearContentBundle:Content')->filterContentByRole($this->getUser(), $id);
        $content = $content->getQuery()->getOneOrNullResult();
        if (empty($content)) {
            throw $this->createNotFoundException(sprintf("Content with id=%s does not exists", $id));
        }

        //        $access = new Access();
        //        if (!$access->hasAccess($this->getUser(), $content)) {
        //            throw new AccessDeniedHttpException();
        //        }

        return $content;
    }

    /**
     * @Rest\Delete("/content/{id}")
     * @Rest\View
     * @Securable(actionId="delete_content_by_id", actionName="Delete content by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content API",
     *  description="Delete content by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteContentAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $content = $this->getDoctrine()->getRepository('ClearContentBundle:Content')->find($id);

        if (!$content) {
            throw $this->createNotFoundException(sprintf("Content with id=%s does not exists", $id));
        }

        $this->container->get('event_dispatcher')->dispatch(
            ContentEvent::CONTENT_DELETED,
            new ContentEvent($content)
        );

        $manager->remove($content);
        $manager->flush();

        return sprintf("Content with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/content")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="create_content", actionName="Create content")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content API",
     *  description="Create new content",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\ContentBundle\Entity\Content",
     *  input="Clear\ContentBundle\Form\ContentType"
     * )
     */

    public function createContentAction(Request $request)
    {
        $content = new Content();

        $form = $this->createForm(ContentType::class, $content);

        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $manager = $this->getDoctrine()->getManager();

                $formType = $this->createForm(TypeSpecificContentType::class, $content, ['contentType'=>  $content->getContentType(),'entityManager' => $manager]);

                $formType->handleRequest($request);
                if($formType->isValid()) {
                    $content->setUser($this->getUser());
                    $manager->persist($content);
                    $manager->flush();

                    $this->container->get('event_dispatcher')->dispatch(
                        ContentEvent::CONTENT_ADDED,
                        new ContentEvent($content)
                    );

                    return $content;
                }
                return $formType;
            }
        } catch (\Exception $exception) {
            $massage = $exception->getMessage();

            throw new HttpException(400, $massage);
        }


        return $form;
    }

    /**
     * @Rest\Post("/content/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="edit_content_by_id", actionName="Edit content by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content API",
     *  description="Edit content",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\ContentBundle\Entity\Content",
     *  input="Clear\ContentBundle\Form\ContentType"
     * )
     */
    public function editContentAction(Request $request, $id)
    {
        $content = $this->getDoctrine()->getRepository('ClearContentBundle:Content')->find($id);
        $arrayBefore = $this->getDoctrine()->getRepository('ClearContentBundle:Content')->toArray($id);

        if(!$content) {
            throw $this->createNotFoundException(sprintf("Content with id=%s not found", $id));
        }

        $form = $this->createForm(ContentType::class, $content);

        $typeObject = TypeInit::getPage('content', $this->getDoctrine()->getManager(), $this->container);
        $oldNames = $typeObject->getFileNames($content->getTypeValues());

        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();

                $formType = $this->createForm(
                    TypeSpecificContentType::class, $content,
                    ['contentType'=>  $content->getContentType(),
                        'entityManager' => $manager,
                        'content' => $content,
                    ]
                );
                $formType->handleRequest($request);

                if ($formType->isValid()) {

                    $content->setUser($this->getUser());
                    $file = $request->files->get('content');
                    if(!isset($file)) {
                        $this->get('vich_uploader.upload_handler')->remove($content, 'imageFile');
                        $content->setImageName(null);
                    }

                    $newNames = $typeObject->getFileNames($content->getTypeValues());
                    $needDelete = $typeObject->getFileNamesForDelete($oldNames, $newNames);
                    $typeObject->deletePageFiles($needDelete);

                    $manager->persist($content);
                    $manager->flush();

                    $arrayAfter = $this->getDoctrine()->getRepository('ClearContentBundle:Content')->toArray($content->getId());

                    $this->get('log.data')->logContent($content, $arrayBefore, $arrayAfter);

                    return $content;
                }
                return $formType;
            }
        } catch (\Exception $exception) {
            $massage = $exception->getMessage();

            throw new HttpException(400, $massage);
        }

        return $form;
    }

    /**
     * @Rest\Post("/content-search")
     * @Rest\View( serializerEnableMaxDepthChecks=true, serializerGroups={"details"})
     * @Securable(actionId="search_content", actionName="Search content")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content API",
     *  description="Get filtered contents",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getSearchResult(Request $request)
    {
        $contentsDoctrine =  $this->getDoctrine()->getRepository('ClearContentBundle:Content');
        $contents = $contentsDoctrine->getSearchResult($request->request, $this->getUser());
        $result['data'] = $this->get('knp_paginator')->paginate(
            $contents,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );
        if (!$result) {
            throw $this->createNotFoundException(sprintf("Contents does not exists"));
        }

        $result['status'] = $this->getCountStatus($request->request);


        /**
 * @var Content $content 
*/
        //        foreach ($result['data'] as $content) {
        //
        //            $text = preg_replace ('/<img.*>/Uis', '', $content->getContent());
        //            $content->setContent($text);
        //        }

        return $result;
    }


    private function getCountStatus($request)
    {
        $status = [
            'Published' => self::PUBLISHED,
            'Archived' => self::ARCHIVED,
            'Draft' => self::DRAFT
        ];
        $result = [];
        foreach ($status as $key => $val) {

            $contents = $this->getDoctrine()->getRepository('ClearContentBundle:Content')
                ->createQueryBuilder('content')
                ->select('count(content.id)');
            if ($request->has('isArticle')) {
                $contents->where('content.isArticle = :isArticle')
                    ->setParameter('isArticle', $request->get('isArticle'));
            }

            $result[$key]['count'] = $contents->andWhere('content.status = :status')
                ->setParameter('status', $val)
                ->getQuery()
                ->getSingleScalarResult();
            $result[$key]['id'] = $val;
        }
        if(isset($result)) {
            return $result;
        }
        return null;
    }

    /**
     * @Rest\Post("/change-status/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="change_status", actionName="Change status")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Content API",
     *  description="Change status",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  input="Clear\ContentBundle\Form\StatusType"
     * )
     */
    public function changeStatusAction(Request $request, $id)
    {
        $content = $this->getDoctrine()->getRepository('ClearContentBundle:Content')->find($id);

        if(!$content) {
            throw $this->createNotFoundException(sprintf("Content with id=%s not found", $id));
        }

        $form = $this->createForm(StatusType::class, $content);
        $form->handleRequest($request);

        $arrayContent = [];
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($content);
            $manager->flush();

            $arrayContent['data'] = $content;
            $arrayContent['statuses'] = $this->getCountStatus($request->request);
            return $arrayContent;
        }

        return $form;
    }
}
