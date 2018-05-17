<?php

namespace Clear\ContentBundle\Tests\Controller;


use Tests\TestCase\ApiTestCase;

class ContentControllerTest extends ApiTestCase
{

    public function testGetContents()
    {
        $response = $this->request('GET', 'api/v1/content', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetContent()
    {
        $response = $this->request('GET', 'api/v1/content/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostContent()
    {
        $response = $this->request('POST', 'api/v1/content', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPatchContent()
    {
        $response = $this->request('POST', 'api/v1/content/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetContents()
    {
        $response = $this->request('GET', 'api/v1/content-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetContent()
    {
        $response = $this->request('GET', 'api/v1/content-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPatchContent()
    {
        $response = $this->request('PUT', 'api/v1/content-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteContent()
    {
        $response = $this->request('DELETE', 'api/v1/content-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}
