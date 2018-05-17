<?php

namespace Clear\ContentBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class CategoryControllerTest extends ApiTestCase
{

    public function testGetCategories()
    {
        $response = $this->request('GET', 'api/v1/categories', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetCategory()
    {
        $response = $this->request('GET', 'api/v1/category/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostCategory()
    {
        $response = $this->request('POST', 'api/v1/category', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPatchCategory()
    {
        $response = $this->request('PUT', 'api/v1/category/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetCategories()
    {
        $response = $this->request('GET', 'api/v1/categories-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetCategory()
    {
        $response = $this->request('GET', 'api/v1/category-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPatchCategory()
    {
        $response = $this->request('PUT', 'api/v1/category-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteCategory()
    {
        $response = $this->request('DELETE', 'api/v1/category-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}