<?php

namespace Clear\UserBundle\Controller;

use Clear\UserBundle\Entity\Role;
use Clear\UserBundle\Form\RoleType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Clear\SecurityBundle\Annotations\Securable;

class RoleController extends FOSRestController
{
    /**
     * @Rest\Get("roles")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_all_roles", actionName="Get all roles")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Role API",
     *  description="Get all roles",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllRolesAction(Request $request)
    {
        $roles = $this->getDoctrine()->getRepository('ClearUserBundle:Role')->createQueryBuilder('role')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $roles,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("$roles does not exists"));
        }

        return $result;
    }


    /**
     * @Rest\Get("role/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_role_by_id", actionName="Get role by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Role API",
     *  description="Get role by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getRoleAction($id)
    {
        $role = $this->getDoctrine()->getRepository('ClearUserBundle:Role')->find($id);

        if (!$role) {
            throw $this->createNotFoundException(sprintf("Role with id=%s does not exists", $id));
        }

        return $role;
    }

    /**
     * @Rest\Delete("/role/{id}")
     * @Rest\View
     * @Securable(actionId="delete_role_by_id", actionName="Delete role by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Role API",
     *  description="Delete role by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteRoleAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $role = $this->getDoctrine()->getRepository('ClearUserBundle:Role')->find($id);

        if (!$role) {
            throw $this->createNotFoundException(sprintf("Role with id=%s does not exists", $id));
        }

        $manager->remove($role);
        $manager->flush();

        return sprintf("Role with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/role")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="create_role", actionName="Create role")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Role API",
     *  description="Create new role",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\UserBundle\Entity\Role",
     *  input="Clear\UserBundle\Form\RoleType"
     * )
     */

    public function createRoleAction(Request $request)
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($role);
            $manager->flush();

            return $role;
        }

        return $form;
    }

    /**
     * @Rest\Put("/role/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="edit_role_by_id", actionName="Edit role by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Role API",
     *  description="Edit role",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\UserBundle\Entity\Role",
     *  input="Clear\UserBundle\Form\RoleType"
     * )
     */
    public function editRoleAction(Request $request, $id)
    {
        $role = $this->getDoctrine()->getRepository('ClearUserBundle:Role')->find($id);
        $form = $this->createForm(RoleType::class, $role, ['method' => 'PUT']);

        $form->handleRequest($request);
        if ($role) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($role);
                $manager->flush();

                return $role;
            }
        } else {
            throw $this->createNotFoundException(sprintf("Role with id=%s not found", $id));
        }

        return $form;
    }

    /**
     * @Rest\Get("/getUserRoles/{token}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getUserRolesAction($token) 
    {

        $user = $this->getDoctrine()->getRepository('ClearOAuthBundle:AccessToken')->findOneBy(['token' => $token]);


        $roles = [];
        /** @var  $role Role */
        foreach ($user->getUser()->getRoleUsers() as $role) {
            $roles[] = $role->getName();
        }

        return $roles;
    }
}
