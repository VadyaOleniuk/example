<?php

namespace Clear\UserBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class RoleControllerTest extends ApiTestCase
{

    public function testGetRoles()
    {
        $response = $this->request('GET', 'api/v1/roles', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetRole()
    {
        $response = $this->request('GET', 'api/v1/role/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostRole()
    {
        $response = $this->request('POST', 'api/v1/role', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPutRole()
    {
        $response = $this->request('PUT', 'api/v1/role/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetRoles()
    {
        $response = $this->request('GET', 'api/v1/roles-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetRole()
    {
        $response = $this->request('GET', 'api/v1/role-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPutRole()
    {
        $response = $this->request('PUT', 'api/v1/role-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteRole()
    {
        $response = $this->request('DELETE', 'api/v1/role-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}
