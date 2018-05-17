<?php

namespace Tests\TestCase;

require_once(__DIR__ . "/../../app/AppKernel.php");

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as PDOSqliteDriver;

abstract class ApiTestCase extends WebTestCase
{

    protected $container;
    protected static $dbCreated = false;

    /**
     * @var Application
     */
    protected static $application;

    use IsolatedTestsTrait;
    /**
     * @return array
     */
    protected function getModelKeys()
    {
        return [];
    }

    /**
     * Calls a URI.
     *
     * @param string $method The request method
     * @param string $uri The URI to fetch
     * @param array $parameters The Request parameters
     * @param array $files The files
     * @param array $server The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
     * @param string $content The raw body data
     * @param bool $changeHistory Whether to update the history or not (only used internally for back(), forward(), and reload())
     *
     * @return Response
     */
    protected function request($method, $uri, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request($method, $uri, $parameters, $files, $server, $content, $changeHistory);

        return $client->getResponse();
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json')
        );
    }

    protected function assertContainsKeys(Response $response)
    {
        $keys = $this->getModelKeys();
        $data = json_decode($response->getContent(), true);

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $data, sprintf('Failed asserting that array has key: %s', $key));
        }
    }

    protected function getResponseData(Response $response)
    {
        return json_decode($response->getContent(), true);
    }

    protected function getAccessToken()
    {
        return static::createClient()->getKernel()->getContainer()->getParameter('test_parameters')['access_token'];
    }

    protected function getHeaders()
    {
        return [
            'HTTP_AUTHORIZATION' => "Bearer {$this->getAccessToken()}",
            'CONTENT_TYPE' => 'application/json',
        ];
    }

    /**
     * Runs 3 console commands: (all with -q and -e=test)
     * doctrine:schema:drop --force
     * doctrine:schema:create
     * doctrine:fixtures:load --no-interaction
     *
     * After successful database rebuild, it will copy it for further reuse
     */
    protected function rebuildDatabase()
    {
        if(!self::$dbCreated) {
            $conn = static::$application->getKernel()->getContainer()->get('doctrine.dbal.default_connection');

            if (!$conn->getDriver() instanceof PDOSqliteDriver) {
                throw new \RuntimeException('It would not work nicely with driver other than PDOSqlite!!!');
            }
            $conn->close();

            // create fresh database (schema and fixtures)
            static::runConsole('doctrine:schema:drop', array('--force' => true));
            static::runConsole('doctrine:schema:create', array());
            static::runConsole('doctrine:fixtures:load', array('-n' => true));


            $conn->connect();
            self::$dbCreated = true;
        }
    }

    protected function copyDatabase()
    {
        $conn = static::$application->getKernel()->getContainer()->get('doctrine.dbal.default_connection');
        $dbPath = $conn->getDatabase();
        static::$fixturesPath = $dbPath . self::$fixturesSuffix;
        copy(static::$fixturesPath, $dbPath);
    }
}