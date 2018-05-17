<?php

namespace Clear\ContentBundle\Tests\Controller;


use Tests\TestCase\ApiTestCase;

class ContentTypeControllerTest extends ApiTestCase
{

    public function testGetContentTypes()
    {
        $response = $this->request('GET', 'api/v1/contentTypes', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetContentType()
    {
        $response = $this->request('GET', 'api/v1/contentType/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostContentType()
    {
        $response = $this->request('POST', 'api/v1/contentType', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPatchContentType()
    {
        $response = $this->request('PUT', 'api/v1/contentType/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetContentTypes()
    {
        $response = $this->request('GET', 'api/v1/contentType-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetContentType()
    {
        $response = $this->request('GET', 'api/v1/contentType-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPatchContentType()
    {
        $response = $this->request('PUT', 'api/v1/contentType-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteTag()
    {
        $response = $this->request('DELETE', 'api/v1/contentType-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}
