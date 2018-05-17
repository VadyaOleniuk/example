<?php

namespace Clear\UserBundle\Controller;

use Clear\ContentBundle\Entity\Content;
use Clear\UserBundle\Entity\User;
use Clear\UserBundle\Form\EditType;
use Clear\UserBundle\Form\RegistrationType;
use Clear\UserBundle\Form\EnabledType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Clear\UserBundle\Form\ProfileType;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;
use Clear\SecurityBundle\Annotations\Securable;


class UserController extends FOSRestController
{
    /**
     * @Rest\Get("users")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_all_users", actionName="Get all users")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="User API",
     *  description="Get all users",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllUsersAction(Request $request)
    {
        $users = $this->getDoctrine()->getRepository('ClearUserBundle:User')->createQueryBuilder('user')->getQuery();

        $result['data'] = $this->get('knp_paginator')->paginate(
            $users,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );


        if (!$result) {
            throw $this->createNotFoundException(sprintf("Users does not exists"));
        }
        $result['count'] = $this->getCountUser();


        return $result;
    }

    /**
     * @Rest\Get("user/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_user_by_id", actionName="Get user by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="User API",
     *  description="Get user by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('ClearUserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(sprintf("User with id=%s does not exists", $id));
        }

        return $user;
    }

    /**
     * @Rest\Delete("/user/{id}")
     * @Rest\View
     * @Securable(actionId="delete_user_by_id", actionName="Delete user by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="User API",
     *  description="Delete user by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteUserAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository('ClearUserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(sprintf("User with id=%s does not exists", $id));
        }

        if ($user->getName() === $this->getParameter('user_not_delete')) {
            throw $this->createNotFoundException(sprintf("User with id=%s can not be deleted", $id));
        }

        $userChange = $this->getDoctrine()->getRepository('ClearUserBundle:User')->findOneBy(['name' => $this->getParameter('user_not_delete')]);
        $contents = $this->getDoctrine()->getRepository('ClearContentBundle:Content')->findBy(['user' => $user]);

        /** @var Content $content */
        foreach ($contents as $content) {
            $content->setUser($userChange);
            $manager->persist($content);
        }

        $manager->remove($user);
        $manager->flush();

        $data['count'] = $this->getCountUser();
        return $data;
    }

    /**
     * @Rest\Put("/enabled/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="set_enabled_user_by_id", actionName="Set enabled  user by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="User API",
     *  description="Set or remove enabled",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\UserBundle\Entity\User",
     *  input="Clear\UserBundle\Form\EnabledType"
     * )
     */
    public function setEnabledAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('ClearUserBundle:User')->find($id);
        $form = $this->createForm(EnabledType::class, $user, ['method' => 'Put']);

        $form->handleRequest($request);
        if ($user) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();

                return $user;
            }
        } else {
            throw $this->createNotFoundException(sprintf("User with id=%s not found", $id));
        }

        return $form;
    }

    /**
     * @Rest\Post("/user-search")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="user_search", actionName="User search")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="User API",
     *  description="User search",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getSearchUser(Request $request)
    {
        $users = $this->getDoctrine()->getRepository('ClearUserBundle:User')->getSearchResult(
            $request->request,
            $this->getUser()
        );

        $result = $this->get('knp_paginator')->paginate(
            $users,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Users does not exists"));
        }
        return $result;
    }

    /**
     * @Rest\Put("/user/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function editUserAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('ClearUserBundle:User')->find($id);
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        /* @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');
        $form = $this->createForm(EditType::class, $user, ['method' => 'Put']);
        $form->setData($user);

        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException();
        }
        $form->submit($data);
        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updatePassword($user);
            $userManager->updateUser($user);
            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Successfully updated');
            $result['data'] = $user;
            $result['count'] = $this->getCountUser();

            return $result;
        }

        return $form;
    }

    /**
     * @Rest\Put("/profile/user")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function editUserProfileAction(Request $request)
    {
        /** @var  $user User */
        $user = $this->getUser();

        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }



        $data = json_decode($request->getContent(), true);
        if ($data===null || !sizeof($data)) {
            return $user;
        }

        if (isset($data['current_password']) && !$this->validPassword($data['current_password'], $user)) {
            throw new HttpException(400, 'The entered password is invalid');
        }
        if(isset($data['current_password'])) {

            $form = $this->createForm(ProfileType::class, $user, ['method' => 'Put', "pass" => true]);
        }else{
            unset($data["plainPassword"]);
            $form = $this->createForm(ProfileType::class, $user, ['method' => 'Put']);
        }

        $form->setData($user);
        $form->submit($data);

        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            $this->sendEmail($user);

            return $user;
        }

        return $form;
    }

    /**
     * @param $password
     * @param User $user
     * @return bool
     */
    public function validPassword($password, User $user)
    {
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();

        return $encoder->isPasswordValid($user->getPassword(), $password, $salt);
    }

    /**
     * @param User $user
     */
    public function sendEmail(User $user)
    {
        $mailer = $this->get('mailer');

        $message = \Swift_Message::newInstance()
            ->setSubject('Change of personal data')
            ->setFrom(['noreply@clearassured.co.uk' => 'Clear Assured'])
            ->setTo([$user->getEmail()])
            ->setBody(
                $this->renderView(
                    'Emails/confirm.html.twig',
                    ['user' => $user]
                ),
                'text/html'
            );

        $mailer->send($message);
    }

    /**
     * @Rest\Put("user-last-login/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="User API",
     *  description="Last login",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function setLastLogin($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(sprintf("User with id=%s not found", $id));
        }

        $user->setLastLogin(new \DateTime());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return $user;
    }

    private function getCountUser()
    {
        return $this->getDoctrine()->getRepository('ClearUserBundle:Role')->createQueryBuilder('role')
            ->select('COUNT(users.id) AS c, role.name ')
            ->leftJoin('role.users ', 'users ')
            ->groupBy('role.id')
            ->getQuery()->getResult();
    }

}
