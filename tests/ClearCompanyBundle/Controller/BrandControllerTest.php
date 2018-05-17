<?php

namespace Clear\CompanyBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class BrandControllerTest extends ApiTestCase
{

    public function testGetBrands()
    {
        $response = $this->request('GET', 'api/v1/brand', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetBrand()
    {
        $response = $this->request('GET', 'api/v1/brand/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostBrand()
    {
        $response = $this->request('POST', 'api/v1/brand', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPutBrand()
    {
        $response = $this->request('PUT', 'api/v1/brand/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetBrands()
    {
        $response = $this->request('GET', 'api/v1/brand-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetBrand()
    {
        $response = $this->request('GET', 'api/v1/brand-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPutBrand()
    {
        $response = $this->request('PUT', 'api/v1/brand-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteBrand()
    {
        $response = $this->request('DELETE', 'api/v1/brand-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}