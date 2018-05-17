<?php
namespace Clear\LogBundle\EventSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * Class AbstractSubscriber
 * @package Clear\LogBundle\EventSubscriber
*/
abstract class AbstractSubscriber
{
    /**
     * Default action for logs
     */
    const UNKNOWN_ACTION = 'unknown_action';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractSubscriber constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $action
     * @param array  $entityFields
     */
    protected function logEntity($action = self::UNKNOWN_ACTION, array $entityFields)
    {
        $this->container->get('monolog.logger.db')->info(
            $action, [
            'entity' => $entityFields
            ]
        );
    }
}