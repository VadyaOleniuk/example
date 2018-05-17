<?php

namespace Clear\CompanyBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class SpecificThemeControllerTest extends ApiTestCase
{

    public function testGetSpecificies()
    {
        $response = $this->request('GET', 'api/v1/specific', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetSpecific()
    {
        $response = $this->request('GET', 'api/v1/specific/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostSpecific()
    {
        $response = $this->request('POST', 'api/v1/specific', [], [], $this->getHeaders());


        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPutSpecific()
    {
        $response = $this->request('PUT', 'api/v1/specific/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetSpecifics()
    {
        $response = $this->request('GET', 'api/v1/specific-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetSpecific()
    {
        $response = $this->request('GET', 'api/v1/specific-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPutSpecific()
    {
        $response = $this->request('PUT', 'api/v1/specific-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteSpecific()
    {
        $response = $this->request('DELETE', 'api/v1/specific-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}