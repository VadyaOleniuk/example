<?php

namespace Clear\NotificationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ServerUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('server:update')
            ->setDescription('Command for server auto-update')
            ->addArgument('env', InputArgument::OPTIONAL, 'environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $env = $input->getArgument('env');
        $env = $env ? $env : 'prod';

        $gitCommand = 'git pull';
        $gitProcess = new Process($gitCommand);
        $gitProcess->run();
        $gitOutput =  $gitProcess->getOutput();
        $output->writeln($gitOutput);


        $updateCommand = 'bin/console doctrine:schema:update --force';
        $updateProcess = new Process($updateCommand);
        $updateProcess->run();
        $updateOutput = $updateProcess->getOutput();
        $output->writeln($updateOutput);


        $chmodCommand = 'sudo chmod -R 777 var';
        $chmodProcess = new Process($chmodCommand);
        $chmodProcess->run();
        $chmodOutput = $chmodProcess->getOutput();
        $output->writeln($chmodOutput);


        $cacheCommand = 'bin/console cache:clear --no-warmup --env=' . $env;
        $cacheProcess = new Process($cacheCommand);
        $cacheProcess->run();
        $cacheOutput = $cacheProcess->getOutput();
        $output->writeln($cacheOutput);
    }
}
