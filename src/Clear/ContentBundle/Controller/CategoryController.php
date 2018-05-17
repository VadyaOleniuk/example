<?php

namespace Clear\ContentBundle\Controller;

use Clear\ContentBundle\Entity\Category;
use Clear\ContentBundle\Form\CategoryType;
use Clear\UserBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Clear\SecurityBundle\Annotations\Securable;

class CategoryController extends FOSRestController
{
    /**
     * @Rest\Get("categories")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_all_categories", actionName="Get all categories")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Category API",
     *  description="Get all Categories",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Access denied",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllCategoriesAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('ClearContentBundle:Category');

        if (in_array('Admin', $this->get('util.data')->getRoles($this->getUser()))) {
            $categories = $repository->createQueryBuilder('category')->getQuery();
        } elseif (in_array('User', $this->get('util.data')->getRoles($this->getUser()))) {
            $categories = $repository->createQueryBuilder('category')
                ->where('category.isActive = :isActive')
                ->setParameter('isActive', true)
                ->getQuery();
        } else {
            $categories = $repository->createQueryBuilder('category')->getQuery();
        }


        $result['items'] = $categories->getResult();
          /*  $this->get('knp_paginator')->paginate(
            $categories,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );*/

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Categories does not exists"));
        }

        return $result;
    }

    /**
     * @Rest\Get("category/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_category_by_id", actionName="Get category by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Category API",
     *  description="Get category by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getCategoryAction($id)
    {
        $category = $this->getDoctrine()->getRepository('ClearContentBundle:Category')->find($id);

        if (!$category) {
            throw $this->createNotFoundException(sprintf("Category with id=%s does not exists", $id));
        }

        return $category;
    }

    /**
     * @Rest\Delete("/category/{id}")
     * @Rest\View
     * @Securable(actionId="delete_category", actionName="Delete category")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Category API",
     *  description="Delete categori by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteCategoryAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getRepository('ClearContentBundle:Category')->find($id);

        if (!$category) {
            throw $this->createNotFoundException(sprintf("Category with id=%s does not exists", $id));
        }

        $manager->remove($category);
        $manager->flush();

        return sprintf("Category with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/category")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="create_category", actionName="Create category")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Category API",
     *  description="Create new category",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\ContentBundle\Entity\Category",
     *  input="Clear\ContentBundle\Form\CategoryType"
     * )
     */

    public function createCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            return $category;
        }

        return $form;
    }

    /**
     * @Rest\Put("/category/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="Edit_category", actionName="Edit category")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Category API",
     *  description="Edit category",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\ContentBundle\Entity\Category",
     *  input="Clear\ContentBundle\Form\CategoryType"
     * )
     */
    public function editCategoryAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository('ClearContentBundle:Category')->find($id);
        $form = $this->createForm(CategoryType::class, $category, ['method' => 'Put']);

        $form->handleRequest($request);
        if ($category) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($category);
                $manager->flush();

                return $category;
            }
        } else {
            throw $this->createNotFoundException(sprintf("Category with id=%s not found", $id));
        }

        return $form;
    }

    /**
     * @Rest\Get("/categories-with-count")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Category API",
     *  description="Get categories with count for content",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getCategoriesWithCountAction()
    {

        $repository = $this->getDoctrine()->getRepository('ClearContentBundle:Category');

        $query = $repository->createQueryBuilder('category')
            ->select('COUNT(category) as total, category')
            ->leftJoin('category.contents', 'content')
            ->where('content <> 0')
            ->groupBy('category.id')
            ->getQuery()->getArrayResult();

        return $query;
    }

    /**
     * @Rest\Get("/parent-categories")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Category API",
     *  description="Get parent categories",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getParentCategoriesAction()
    {
        $repository = $this->getDoctrine()->getRepository('ClearContentBundle:Category');

        $query = $repository->createQueryBuilder('category')
            ->select('category')
            ->where('category.parent IS NULL')
            ->getQuery()->getArrayResult();

        return $query;
    }

    /**
     * @Rest\Get("/search-categories")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="search_categories", actionName="Search categories")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Category API",
     *  description="Get searched categories",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getSearchCategoriesAction(Request $request)
    {
        $categories = $this->getSerchByRoleUser($this->getUser(), $request);

        $result = $this->get('knp_paginator')->paginate(
            $categories,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Categories does not exists"));
        }

        return $result;
    }

    /**
     * @param User    $user
     * @param Request $request
     * @return \Doctrine\ORM\Query
     */
    public function getSerchByRoleUser(User $user, Request $request) 
    {
        $repository = $this->getDoctrine()->getRepository('ClearContentBundle:Category');

        if (in_array('Admin', $this->get('util.data')->getRoles($this->getUser()))) {
            $categories = $repository->createQueryBuilder('category')
                ->where('category.title LIKE :query')
                ->setParameter('query', $request->get('query') . '%')
                ->getQuery();
        } elseif (in_array('User', $this->get('util.data')->getRoles($this->getUser()))) {
            $categories = $repository->createQueryBuilder('category')
                ->where('category.title LIKE :query')
                ->andWhere('category.isActive = :active')
                ->setParameters(['query' => $request->get('query') . '%', 'active' => true])
                ->getQuery();
        } else {
            $categories = $repository->createQueryBuilder('category')
                ->where('category.title LIKE :query')
                ->setParameter('query', $request->get('query') . '%')
                ->getQuery();
        }

        return $categories;
    }
}
