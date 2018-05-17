<?php

namespace Clear\NotificationBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class NotificationControllerTest extends ApiTestCase
{

    public function testGetNotifications()
    {
        $response = $this->request('GET', 'api/v1/notification', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testGetNotification()
    {
        $response = $this->request('GET', 'api/v1/notification/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPostNotification()
    {
        $response = $this->request('POST', 'api/v1/notification', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testPutNotification()
    {
        $response = $this->request('PUT', 'api/v1/notification/1', [], [], $this->getHeaders());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }

    public function testFailGetNotifications()
    {
        $response = $this->request('GET', 'api/v1/notification-fail', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailGetNotification()
    {
        $response = $this->request('GET', 'api/v1/notification-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailPutNotification()
    {
        $response = $this->request('PUT', 'api/v1/notification-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFailDeleteNotification()
    {
        $response = $this->request('DELETE', 'api/v1/notification-fail/10', [], [], $this->getHeaders());
        $this->assertEquals(404, $response->getStatusCode());
    }
}