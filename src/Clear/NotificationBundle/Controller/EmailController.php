<?php

namespace Clear\NotificationBundle\Controller;

use Clear\NotificationBundle\Entity\Email;
use Clear\NotificationBundle\Form\EmailType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Clear\UserBundle\Entity\User;

class EmailController extends FOSRestController
{
    /**
     * @Rest\Get("email")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Email API",
     *  description="Get all emails",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllEmailsAction(Request $request)
    {
        $emails = $this->getDoctrine()->getRepository('ClearNotificationBundle:Email')->createQueryBuilder('email')->getQuery();

        if (!$emails) {
            throw $this->createNotFoundException(sprintf("Emails does not exists"));
        }

        $result = $this->get('knp_paginator')->paginate(
            $emails,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 50)
        );

        return $result;
    }

    /**
     * @Rest\Get("email/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Email API",
     *  description="Get email by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getEmailAction($id)
    {
        $email = $this->getDoctrine()->getRepository('ClearNotificationBundle:Email')->find($id);

        if (!$email) {
            throw $this->createNotFoundException(sprintf("Email with id=%s does not exists", $id));
        }

        return $email;
    }

    /**
     * @Rest\Delete("/email/{id}")
     * @Rest\View
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Email API",
     *  description="Delete email by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteEmailAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $email = $this->getDoctrine()->getRepository('ClearNotificationBundle:Email')->find($id);

        if (!$email) {
            throw $this->createNotFoundException(sprintf("$email with id=%s does not exists", $id));
        }

        $manager->remove($email);
        $manager->flush();

        return sprintf("Email with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/email")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Email API",
     *  description="Create new email",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\NotificationBundle\Entity\Email",
     *  input="Clear\NotificationBundle\Form\EmailType"
     * )
     */

    public function createEmailAction(Request $request)
    {
        $email = new Email();
        $form = $this->createForm(EmailType::class, $email);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($email);
            $manager->flush();

            return $email;
        }

        return $form;
    }

    /**
     * @Rest\Put("/email/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Email API",
     *  description="Edit email",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\NotificationBundle\Entity\Email",
     *  input="Clear\NotificationBundle\Form\EmailType"
     * )
     */
    public function editTagAction(Request $request, $id)
    {
        $email = $this->getDoctrine()->getRepository('ClearNotificationBundle:Email')->find($id);
        $form = $this->createForm(EmailType::class, $email, ['method' => 'Put']);

        if (!$email) {
            throw $this->createNotFoundException(sprintf("Email with id=%s not found", $id));
        }

        $form->handleRequest($request);
        if ($email) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($email);
                $manager->flush();

                return $email;
            }
        }

        return $form;
    }

    /**
     * @Rest\Get("/send-email/{name}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     */
    public function sendEmailAction(Request $request, $name)
    {
        /** @var  Email $email */
        $email = $this->getDoctrine()->getManager()->getRepository('ClearNotificationBundle:Email')->findOneBy(['name' => $name]);

        if (!$email) {
            throw $this->createNotFoundException(sprintf('Email with name=%s not found', $name));
        }

        $body = str_replace('{{ url }}', $request->getSchemeAndHttpHost(), $email->getText());


        $users = $this->getDoctrine()->getManager()->getRepository('ClearUserBundle:User')->getActiveUsers();

        if (!$users) {
            throw $this->createNotFoundException(sprintf('User not found'));
        }

        $emails = [];
        /** @var User $user */
        foreach ($users as $user) {
            $emails[] = $user->getEmail();
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($email->getSubject())
            ->setFrom(['noreply@clearassured.co.uk' => 'Clear Assured'])
            ->setTo($emails)
            ->setContentType("text/html")
            ->setBody($body);

        $this->get('mailer')->send($message);
    }
}
