<?php

namespace Clear\UserBundle\Controller;

use Doctrine\Common\Util\Debug;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Clear\SecurityBundle\Annotations\Securable;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Clear\UserBundle\Entity\Disallowed;
use Clear\UserBundle\Form\DisallowedType;

class DisallowedController extends FOSRestController
{
    /**
     * @Rest\Get("disallowed")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getAllActionsAction(Request $request)
    {
        $disallowed = $this->getDoctrine()->getRepository('ClearUserBundle:Disallowed')->createQueryBuilder('disallowed')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $disallowed,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 50)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Disallowed does not exists"));
        }

        return $result;
    }

    /**
     * @Rest\Post("/disallowed")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */

    public function changeDisallowedAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $requestData = [
            'roleUser' => $data['disallowed']['roleUser'],
            'actionId' => $data['disallowed']['actionId']
        ];
        $manager = $this->getDoctrine()->getManager();

        $prohibitions = $this->getDoctrine()->getRepository('ClearUserBundle:Disallowed')->findOneByParameters($requestData);

        if (null === $prohibitions) {
            $prohibitions = new Disallowed();
            $form = $this->createForm(DisallowedType::class, $prohibitions);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $manager->persist($prohibitions);
                $manager->flush();

                return $prohibitions;
            }

            return $form;
        }

        $manager->remove($prohibitions);
        $manager->flush();

        return [];
    }


    /**
     * @Rest\Get("get-actions")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getAnnotationsAction()
    {
        $annotationReader = new AnnotationReader();
        $routes = $this->container->get('router')->getRouteCollection()->all();
        $this->container->set('request', new Request(), 'request');
        $securableAnnotation = [];
        $bundles = $this->container->getParameter('kernel.bundles');

        foreach ($bundles as $name => $class) {
            $bundlePrefix = 'Clear';
            if (substr($name, 0, strlen($bundlePrefix)) != $bundlePrefix) { continue;
            }

            $namespaceParts = explode('\\', $class);

            array_pop($namespaceParts);
            $bundleNamespace = implode('\\', $namespaceParts);
            $rootPath = $this->container->get('kernel')->getRootDir().'/../src/';
            $controllerDir = $rootPath.  $bundleNamespace.'/Controller';
            $controllerDir = str_replace("\\", '/', $controllerDir);

            $files = scandir($controllerDir);
            foreach ($files as $file) {
                list($filename, $ext) = explode('.', $file);
                if ($ext != 'php') { continue;
                }

                $class = $bundleNamespace.'\\Controller\\'.$filename;
                $reflectedClass = new \ReflectionClass($class);

                foreach ($reflectedClass->getMethods() as $reflectedMethod) {
                    $annotations = $annotationReader->getMethodAnnotations($reflectedMethod);
                    if (!empty($annotations)) {
                        foreach ($annotations as $annotation) {
                            if (isset($annotation->actionId) && isset($annotation->actionName)) {
                                $securableAnnotation[] = ['actionId' => $annotation->actionId, 'actionName' => $annotation->actionName];
                            }
                        }
                    }
                }
            }
        }

        return $securableAnnotation;
    }

    /**
     * @Rest\Get("/permissions")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getPermissions()
    {
        $repository = $this->getDoctrine()->getRepository('ClearUserBundle:Disallowed');

        $query = $repository->createQueryBuilder('d')
            ->select('d, r')
            ->innerJoin('d.roleUser', 'r')
            ->getQuery()->getArrayResult();

        return $query;
    }
}
