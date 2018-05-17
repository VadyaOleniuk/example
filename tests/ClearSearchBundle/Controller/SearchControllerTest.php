<?php

namespace Clear\SearchBundle\Tests\Controller;

use Tests\TestCase\ApiTestCase;

class SearchControllerTest extends ApiTestCase
{
    public function testGetSearchAction()
    {
        $response = $this->request('GET', 'api/v1/search?search=d', [], [], $this->getHeaders());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response);
    }
}
