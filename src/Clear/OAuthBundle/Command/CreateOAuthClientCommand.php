<?php

namespace Clear\OAuthBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateOAuthClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oauth:client:create')
            ->setDescription('Create OAuth Client')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Client Name?'
            )
            ->addArgument(
                'redirectUri',
                InputArgument::REQUIRED,
                'Redirect URI?'
            )
            ->addArgument(
                'grantType',
                InputArgument::REQUIRED,
                'Grant Type?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $name = $input->getArgument('name');
        $redirectUri = $input->getArgument('redirectUri');
        $grantType = $input->getArgument('grantType');

        $clientManager = $container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setName($name);
        $client->setRedirectUris([$redirectUri]);
        $client->setAllowedGrantTypes([$grantType]);
        $clientManager->updateClient($client);

        $output->writeln(
            sprintf(
                "<info>The client <comment>%s</comment> was created with <comment>%s</comment> as public id and <comment>%s</comment> as secret</info>",
                $client->getName(),
                $client->getPublicId(),
                $client->getSecret()
            )
        );
    }
}
// Example
// bin/console oauth:client:create client1 www.client1.com password

