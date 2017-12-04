<?php

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateOauthClientCommand extends ContainerAwareCommand
{

    private $clientManager;

    protected function configure()
    {
        $this
            ->setName('oauth:client:create')
            ->setDescription('Create an OAuth2 client')
            ->addOption('grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets allowed grant type for client. Use this option multiple times to set multiple grant types.', null)
            ->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->clientManager = $this->getApplication()->getKernel()->getContainer()->get('fos_oauth_server.client_manager.default');
        $this->createOauthClient($input->getOption('redirect-uri'), $input->getOption('grant-type'));
    }

    private function createOauthClient($redirectUri, $grantType)
    {
        $client = $this->clientManager->createClient();
        $client->setRedirectUris($redirectUri);
        $client->setAllowedGrantTypes($grantType);
        $this->clientManager->updateClient($client);
        echo "Added a new client with ID {$client->getPublicId()}\n";
    }
}