<?php
namespace Maintenance;

use Maintenance\Listener\MaintenanceListener;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface, 
    ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'MaintenanceConfig' => 'Maintenance\Service\MaintenanceConfigFactory',
            )
        );
    }

    public function onBootstrap(EventInterface $e)
    {
        if (!$e instanceof MvcEvent) {
            return;
        }

        $events = $e->getApplication()->getEventManager();
        $events->attachAggregate(new MaintenanceListener());
    }
}