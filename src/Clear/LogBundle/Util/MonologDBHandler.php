<?php

namespace Clear\LogBundle\Util;

use Clear\LogBundle\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class MonologDBHandler extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * MonologDBHandler constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->manager = $manager;
    }


    /**
     * Called when writing to our database
     *
     * @param array $record
     */
    protected function write(array $record)
    {
        $log = new Log();
        $log->setMessage($record['message']);

        $log->setAction($record['action']);
        $log->setUser($record['context']['entity']['user']);
        $log->setContent($record['context']['entity']['content']);

        $log->setUserIdInt($record['context']['entity']['user']->getId());
        $log->setContentIdInt($record['context']['entity']['content']->getId());


        $this->manager->persist($log);
        $this->manager->flush();
    }
}