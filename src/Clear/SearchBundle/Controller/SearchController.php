<?php

namespace Clear\SearchBundle\Controller;


use Clear\SearchBundle\Form\SearchType;
use Clear\SearchBundle\Model\Search;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SearchController extends FOSRestController
{
    /**
     * @Rest\Get("search")
     * @Rest\View(serializerEnableMaxDepthChecks=true, serializerGroups={"details"})

     *
     *
     * @ApiDoc(
     * resource=true,
     * section="Search API",
     *  description="This is a description of your API method",
     *  input="Clear\SearchBundle\Form\SearchType",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized to say hello",
     *         404={
     *           "Returned when the user is not found",
     *           "Returned when something else is not found"
     *         }
     *     }
     * )
     */
    public function getSearchAction(Request $request)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if($form->isValid()) {
            $search = $this->get('clear_search.factory')->getSearcher();
            return ['data' => $search->search()];
        }else{
            throw new HttpException(400, $form->getErrors());
        }
    }
}
