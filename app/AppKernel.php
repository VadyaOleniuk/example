<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            //Vendors
            new FOS\ElasticaBundle\FOSElasticaBundle(),

            // Project Bundles
            new Clear\ContentBundle\ClearContentBundle(),

            //Upload Files
            new Vich\UploaderBundle\VichUploaderBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),

            //ApiDoc
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),

            //Pagination
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Clear\SearchBundle\ClearSearchBundle(),

            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new Clear\OAuthBundle\ClearOAuthBundle(),
            new Clear\UserBundle\ClearUserBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Clear\SecurityBundle\ClearSecurityBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Clear\CompanyBundle\ClearCompanyBundle(),
            new Clear\NotificationBundle\ClearNotificationBundle(),
            new Clear\FileStorageBundle\ClearFileStorageBundle(),
            new Clear\LanguageBundle\ClearLanguageBundle(),
            new Clear\LogBundle\ClearLogBundle(),
            new Clear\PagesBundle\ClearPagesBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            $bundles[] = new Bazinga\Bundle\FakerBundle\BazingaFakerBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
