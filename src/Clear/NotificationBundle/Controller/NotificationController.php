<?php

namespace Clear\NotificationBundle\Controller;

use Clear\NotificationBundle\Entity\Notification;
use Clear\NotificationBundle\Form\NotificationType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class NotificationController extends FOSRestController
{
    /**
     * @Rest\Get("notification")
     * @Rest\View
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Notification API",
     *  description="Get all notifications",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllNotificationsAction(Request $request)
    {
        $notification = $this->getDoctrine()->getRepository('ClearNotificationBundle:Notification')->createQueryBuilder('notification')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $notification,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Notification does not exists"));
        }

        return $result;
    }

    /**
     * @Rest\Get("notification/{id}")
     * @Rest\View
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Notification API",
     *  description="Get notification by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getNotificationAction($id)
    {
        $notification = $this->getDoctrine()->getRepository('ClearNotificationBundle:Notification')->find($id);

        if (!$notification) {
            throw $this->createNotFoundException(sprintf("Notification with id=%s does not exists", $id));
        }

        return $notification;
    }

    /**
     * @Rest\Delete("/notification/{id}")
     * @Rest\View
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Notification API",
     *  description="Delete notification by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteNotificationAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $notification = $this->getDoctrine()->getRepository('ClearNotificationBundle:Notification')->find($id);

        if (!$notification) {
            throw $this->createNotFoundException(sprintf("Notification with id=%s does not exists", $id));
        }

        $manager->remove($notification);
        $manager->flush();

        return sprintf("Notification with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/notification")
     * @Rest\View
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Notification API",
     *  description="Create new notification",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\NotificationBundle\Entity\Notification",
     *  input="Clear\NotificationBundle\Form\NotificationType"
     * )
     */

    public function createNotificationAction(Request $request)
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($notification);
            $manager->flush();

            return $notification;
        }

        return $form;
    }

    /**
     * @Rest\Put("/notification/{id}")
     * @Rest\View
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Notification API",
     *  description="Edit notification",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\NotificationBundle\Entity\Notification",
     *  input="Clear\NotificationBundle\Form\NotificationType"
     * )
     */
    public function editNotificationAction(Request $request, $id)
    {
        $notification = $this->getDoctrine()->getRepository('ClearNotificationBundle:Notification')->find($id);
        $form = $this->createForm(NotificationType::class, $notification, ['method' => 'Put']);

        $form->handleRequest($request);
        if ($notification) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($notification);
                $manager->flush();

                return $notification;
            }
        } else {
            throw $this->createNotFoundException(sprintf("Notification with id=%s not found", $id));
        }

        return $form;
    }
}
