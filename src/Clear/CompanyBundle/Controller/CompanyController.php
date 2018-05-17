<?php

namespace Clear\CompanyBundle\Controller;

use Clear\CompanyBundle\Entity\Company;
use Clear\CompanyBundle\Form\CompanyType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Clear\SecurityBundle\Annotations\Securable;

class CompanyController extends FOSRestController
{
    /**
     * @Rest\Get("company")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_all_companies", actionName="Get all companies")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Company API",
     *  description="Get all companies",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getAllCompaniesAction(Request $request)
    {
        $company = $this->getDoctrine()->getRepository('ClearCompanyBundle:Company')->createQueryBuilder('company')->getQuery();

        $result = $this->get('knp_paginator')->paginate(
            $company,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        if (!$result) {
            throw $this->createNotFoundException(sprintf("Company does not exists"));
        }

        return $result;
    }

    /**
     * @Rest\Get("company/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="get_company_by_id", actionName="Get company by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Company API",
     *  description="Get company by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function getCompanyAction($id)
    {
        $company = $this->getDoctrine()->getRepository('ClearCompanyBundle:Company')->find($id);

        if (!$company) {
            throw $this->createNotFoundException(sprintf("Company with id=%s does not exists", $id));
        }

        return  $company;
    }

    /**
     * @Rest\Delete("/company/{id}")
     * @Rest\View
     * @Securable(actionId="delete_company_by_id", actionName="Delete company by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Company API",
     *  description="Delete company by id",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  }
     * )
     */
    public function deleteCompanyAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $company = $this->getDoctrine()->getRepository('ClearCompanyBundle:Company')->find($id);

        if (!$company) {
            throw $this->createNotFoundException(sprintf("Company with id=%s does not exists", $id));
        }

        $manager->remove($company);
        $manager->flush();

        return sprintf("Company with id=%s deleted", $id);
    }

    /**
     * @Rest\Post("/company")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="create_company", actionName="Create company")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Company API",
     *  description="Create new company",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\CompanyBundle\Entity\Company",
     *  input="Clear\CompanyBundle\Form\CompanyType"
     * )
     */

    public function createBrandAction(Request $request)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($company);
            $manager->flush();

            return $company;
        }

        return $form;
    }

    /**
     * @Rest\Put("/company/{id}")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Securable(actionId="edit_company_by_id", actionName="Edit company by id")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Company API",
     *  description="Edit company",
     *  statusCodes={
     *     200 = "Successful",
     *     400 = "Form has errors",
     *     401 = "Not authenticated",
     *     403 = "Not having permissions",
     *     404 = "Not found"
     *  },
     *  output="Clear\CompanyBundle\Entity\Company",
     *  input="Clear\CompanyBundle\Form\CompanyType"
     * )
     */
    public function editBrandAction(Request $request, $id)
    {
        $company = $this->getDoctrine()->getRepository('ClearCompanyBundle:Company')->find($id);
        $form = $this->createForm(CompanyType::class, $company, ['method' => 'Put']);

        $form->handleRequest($request);
        if ($company) {
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($company);
                $manager->flush();

                return $company;
            }
        } else {
            throw $this->createNotFoundException(sprintf("Company with id=%s not found", $id));
        }

        return $form;
    }
}
