<?php

namespace Clear\UserBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testGetUsers()
    {
        $response = $this->request('GET', 'api/v1/users', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetUser()
    {
        $response = $this->request('GET', 'api/v1/user/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetUsers()
    {
        $response = $this->request('GET', 'api/v1/user-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetUser()
    {
        $response = $this->request('GET', 'api/v1/user-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPatchUser()
    {
        $response = $this->request('PUT', 'api/v1/user-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteUser()
    {
        $response = $this->request('DELETE', 'api/v1/user-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}
