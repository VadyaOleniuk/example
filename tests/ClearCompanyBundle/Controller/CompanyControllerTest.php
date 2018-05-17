<?php

namespace Clear\CompanyBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class CompanyControllerTest extends ApiTestCase
{

    public function testGetCompanies()
    {
        $response = $this->request('GET', 'api/v1/company', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetCompany()
    {
        $response = $this->request('GET', 'api/v1/company/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostCompany()
    {
        $response = $this->request('POST', 'api/v1/company', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPutCompany()
    {
        $response = $this->request('PUT', 'api/v1/company/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetCompanies()
    {
        $response = $this->request('GET', 'api/v1/company-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetCompany()
    {
        $response = $this->request('GET', 'api/v1/company-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPutCompany()
    {
        $response = $this->request('PUT', 'api/v1/company-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteCompany()
    {
        $response = $this->request('DELETE', 'api/v1/company-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}