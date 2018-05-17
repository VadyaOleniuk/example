<?php

namespace Clear\ContentBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class TagControllerTest extends ApiTestCase
{
    public function testGetTags()
    {
        $response = $this->request('GET', 'api/v1/tags', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetTag()
    {
        $response = $this->request('GET', 'api/v1/tag/2', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostTag()
    {
        $response = $this->request('POST', 'api/v1/tag', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPatchTag()
    {
        $response = $this->request('PUT', 'api/v1/tag/2', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetTags()
    {
        $response = $this->request('GET', 'api/v1/tags-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetTag()
    {
        $response = $this->request('GET', 'api/v1/tag-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPatchTag()
    {
        $response = $this->request('PUT', 'api/v1/tag-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteTag()
    {
        $response = $this->request('DELETE', 'api/v1/tag-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}
