<?php

namespace Clear\ContentBundle\Controller;

use Clear\ContentBundle\Entity\Tag;
use Clear\ContentBundle\Form\TagType;
use Clear\UserBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Clear\SecurityBundle\Annotations\Securable;

class TagController extends FOSRestController
{
    /**
     * @Rest\Get("tags")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_all_tags", actionName="Get all topic")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Tag API",
     *  description="Get all tags",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllTagsAction(Request $request)
    {
        $tags = $this->getDoctrine()->getRepository('ClearContentBundle:Tag')->getTagsByRoleUser($this->getUser());

            $result['items'] = $tags->getQuery()->getResult();

        /*     $this->get('knp_paginator')->paginate(
            $tags,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );*/

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Tags does not exists"));
        }

        return $result;
    }

    /**
     * @Rest\Get("tag/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_tag_by_id", actionName="Get topic by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Tag API",
     *  description="Get tag by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getTagAction($id)
    {
        $tag = $this->getDoctrine()->getRepository('ClearContentBundle:Tag')->find($id);

        if (!$tag) {
            throw $this->createNotFoundException(sprintf("Tag with id=%s does not exists", $id));
        }

        return $tag;
    }

    /**
     * @Rest\Delete("/tag/{id}")
     * @Rest\View
     * @Securable(actionId="delete_tag_by_id", actionName="Delete topic by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Tag API",
     *  description="Delete tag by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteTagAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $tag = $this->getDoctrine()->getRepository('ClearContentBundle:Tag')->find($id);

        if (!$tag) {
            throw $this->createNotFoundException(sprintf("Tag with id=%s does not exists", $id));
        }

        $manager->remove($tag);
        $manager->flush();

        return sprintf("Tag with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/tag")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="create_tag", actionName="Create topic")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Tag API",
     *  description="Create new tag",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\ContentBundle\Entity\Tag",
     *  input="Clear\ContentBundle\Form\TagType"
     * )
     */

    public function createTagAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($tag);
            $manager->flush();

            return $tag;
        }

        return $form;
    }

    /**
     * @Rest\Put("/tag/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="edit_tag_by_id", actionName="Edit topic by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Tag API",
     *  description="Edit tag",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\ContentBundle\Entity\Tag",
     *  input="Clear\ContentBundle\Form\TagType"
     * )
     */
    public function editTagAction(Request $request, $id)
    {
        $tag = $this->getDoctrine()->getRepository('ClearContentBundle:Tag')->find($id);
        $form = $this->createForm(TagType::class, $tag, ['method' => 'Put']);

        $form->handleRequest($request);
        if ($tag) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($tag);
                $manager->flush();

                return $tag;
            }
        } else {
            throw $this->createNotFoundException(sprintf("Tag with id=%s not found", $id));
        }

        return $form;
    }

    /**
     * @Rest\Get("/search-tags")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="search_tag", actionName="Search topic")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Tag API",
     *  description="Get searched tags",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getSearchTagsAction(Request $request)
    {
        $tags = $this->getSerchByRoleUser($this->getUser(), $request);
        $result = $this->get('knp_paginator')->paginate(
            $tags,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Tags does not exists"));
        }

        return $result;
    }

    public function getSerchByRoleUser(User $user, Request $request) 
    {
        $repository = $this->getDoctrine()->getRepository('ClearContentBundle:Tag');

        if (in_array('Admin', $this->get('util.data')->getRoles($this->getUser()))) {
            $tags = $repository->createQueryBuilder('tag')
                ->where('tag.name LIKE :query')
                ->setParameter('query', $request->get('query') . '%')
                ->getQuery();
        } elseif (in_array('User', $this->get('util.data')->getRoles($this->getUser()))) {
            $tags = $repository->createQueryBuilder('tag')
                ->where('tag.name LIKE :query')
                ->andWhere('tag.isActive = :active')
                ->setParameters(['query' => $request->get('query') . '%', 'active' => true])
                ->getQuery();
        } else {
            $tags = $repository->createQueryBuilder('tag')
                ->where('tag.name LIKE :query')
                ->setParameter('query', $request->get('query') . '%')
                ->getQuery();
        }

        return $tags;
    }
}
