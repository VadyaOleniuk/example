<?php

namespace Clear\CompanyBundle\Controller;

use Clear\CompanyBundle\Entity\Brand;
use Clear\CompanyBundle\Form\BrandType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class BrandController extends FOSRestController
{
    /**
     * @Rest\Get("brand")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Brand API",
     *  description="Get all brands",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllBrandsAction(Request $request)
    {
        $brands = $this->getDoctrine()->getRepository('ClearCompanyBundle:Brand')->createQueryBuilder('brand')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $brands,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Brands does not exists"));
        }

        return $result;
    }


    /**
     * @Rest\Get("brand/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Brand API",
     *  description="Get brand by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getBrandAction($id)
    {
        $brand = $this->getDoctrine()->getRepository('ClearCompanyBundle:Brand')->find($id);

        if (!$brand) {
            throw $this->createNotFoundException(sprintf("Brand with id=%s does not exists", $id));
        }

        return  $brand;
    }

    /**
     * @Rest\Delete("/brand/{id}")
     * @Rest\View
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Brand API",
     *  description="Delete brand by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteBrandAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $brand = $this->getDoctrine()->getRepository('ClearCompanyBundle:Brand')->find($id);

        if (!$brand) {
            throw $this->createNotFoundException(sprintf("Brand with id=%s does not exists", $id));
        }

        $manager->remove($brand);
        $manager->flush();

        return sprintf("Brand with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/brand")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Brand API",
     *  description="Create new brand",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\CompanyBundle\Entity\Brand",
     *  input="Clear\CompanyBundle\Form\BrandType"
     * )
     */

    public function createBrandAction(Request $request)
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($brand);
            $manager->flush();

            return $brand;
        }

        return $form;
    }

    /**
     * @Rest\Put("/brand/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Brand API",
     *  description="Edit brand",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\CompanyBundle\Entity\Brand",
     *  input="Clear\CompanyBundle\Form\BrandType"
     * )
     */
    public function editBrandAction(Request $request, $id)
    {
        $brand = $this->getDoctrine()->getRepository('ClearCompanyBundle:Brand')->find($id);
        $form = $this->createForm(BrandType::class, $brand, ['method' => 'Put']);

        $form->handleRequest($request);
        if ($brand) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($brand);
                $manager->flush();

                return $brand;
            }
        } else {
            throw $this->createNotFoundException(sprintf("Brand with id=%s not found", $id));
        }

        return $form;
    }
}
