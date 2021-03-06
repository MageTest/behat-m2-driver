<?php

namespace MageTest\Magento2Driver\Driver;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use MageTest\Magento2Driver\ServiceContainer\Magento2DriverExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class Magento2Factory implements DriverFactory
{
    /**
     * {@inheritdoc}
     */
    public function getDriverName()
    {
        return 'magento2';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsJavascript()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->arrayNode('server_parameters')
                    ->useAttributeAsKey('key')
                    ->prototype('variable')->end()
                ->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDriver(array $config)
    {
        if (!class_exists('Behat\Mink\Driver\BrowserKitDriver')) {
            throw new \RuntimeException(
                'Install MinkBrowserKitDriver in order to use goutte driver.'
            );
        }
        
        return new Definition(MageAppDriver::class, array(
            new Reference(Magento2DriverExtension::KERNEL_ID),
            '%mink.base_url%',
        ));
    }
}
