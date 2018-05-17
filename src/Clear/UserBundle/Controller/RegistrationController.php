<?php

namespace Clear\UserBundle\Controller;

use Clear\UserBundle\Entity\User;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\Form\FormInterface;
use JMS\Serializer\SerializationContext;

class RegistrationController extends BaseController
{
    /**
     * @Route("/register", name="user_register")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Method("POST")
     */
    public function registerAction(Request $request)
    {
        /** @var \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var  $user User */
        $user = $userManager->createUser();

        $role = $this->getDoctrine()->getRepository('ClearUserBundle:Role')->findOneBy(['name' => 'User']);
        $user->addRoleUser($role);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm(array('csrf_protection' => false));
        $form->setData($user);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(
                FOSUserEvents::REGISTRATION_SUCCESS, $event
            );

            $this->sendEmail($user);
            $userManager->updateUser($user);

            return $user;
        } else {
            $errorsArray = [];
            foreach ($form->getErrors(true) as $error) {
                $errorsArray[] = $error;
            }
            $response = new Response($this->serialize($errorsArray), Response::HTTP_BAD_REQUEST);
        }

        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/register-company-user", name="register-company-user")
     * @Method("POST")
     */
    public function registerCompanyUserAction(Request $request)
    {

        /** @var \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var  $user User */
        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm(array('csrf_protection' => false));
        $form->setData($user);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(
                FOSUserEvents::REGISTRATION_SUCCESS, $event
            );

            $this->sendEmail($user);
            $userManager->updateUser($user);

            $response = new Response($this->serialize(['User created.']), Response::HTTP_CREATED);
        } else {
            $errorsArray = [];
            foreach ($form->getErrors(true) as $error) {
                $errorsArray[] = $error;
            }
            $response = new Response($this->serialize($errorsArray), Response::HTTP_BAD_REQUEST);
        }

        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/register-admin/{id}", name="admin_register")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Method("POST")
     */
    public function registerAdminAction(Request $request, $id)
    {
        /** @var \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();

        /** @var  $user User */
        $role = $this->getDoctrine()->getRepository('ClearUserBundle:Role')->find($id);
        if($role) {
            $user->addRoleUser($role);
            $user->setEnabled(true);

            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            $form = $formFactory->createForm(array('csrf_protection' => false));
            $form->setData($user);
            $this->processForm($request, $form);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(
                    FOSUserEvents::REGISTRATION_SUCCESS,
                    $event
                );
                $this->sendEmail($user);
                $userManager->updateUser($user);

                return $user;
            } else {
                $errorsArray = [];
                foreach ($form->getErrors(true) as $error) {
                    $errorsArray[] = $error;
                }
                $response = new Response($this->serialize($errorsArray), Response::HTTP_BAD_REQUEST);
            }

            return $this->setBaseHeaders($response);
        }
        throw new BadRequestHttpException();
    }
    /**
     * @param  Request $request
     * @param  FormInterface $form
     */
    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException();
        }

        if (isset($data['plainPassword']['first'], $data['plainPassword']['second'], $data['email'])) {
            $data['plainPassword']['first'] = trim($data['plainPassword']['first']);
            $data['plainPassword']['second'] = trim($data['plainPassword']['second']);
            $data['email'] = trim($data['email']);
        }

        $form->submit($data);
    }

    /**
     * Data serializing via JMS serializer.
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    private function serialize($data)
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $this->get('jms_serializer')
            ->serialize($data, 'json', $context);
    }

    /**
     * Set base HTTP headers.
     *
     * @param Response $response
     *
     * @return Response
     */
    private function setBaseHeaders(Response $response)
    {
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @param User $user
     */
    public function sendEmail(User $user)
    {
        $mailer = $this->get('mailer');

        $message = \Swift_Message::newInstance()
            ->setSubject('Welcome to Clear Assured')
            ->setFrom(['noreply@clearassured.co.uk' => 'Clear Assured'])
            ->setTo([$user->getEmail()])
            ->setBody(
                $this->renderView(
                    'Emails/invitation.html.twig',
                    ['user' => $user,
                        'datetime' => new \DateTime('now')  ]
                ),
                'text/html'
            );

        $mailer->send($message);
    }
}