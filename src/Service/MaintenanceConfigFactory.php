<?php
/**
 * Maintenance mode component for ZF2
 *
 * @author    Juan Pedro Gonzalez Gutierrez
 * @link      http://github.com/zend-modules/maintenance
 * @copyright Copyright (c) 2015 Juan Pedro Gonzalez Gutierrez
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPL v3
 */

namespace Maintenance\Service;

use Maintenance\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\View\Http\ViewManager as HttpViewManager;

class MaintenanceConfigFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->has('ApplicationConfig') ? $serviceLocator->get('ApplicationConfig') : array();
        $config = isset($config['maintenance_mode']) ? $config['maintenance_mode'] : array();

        if (!isset($config['template'])) {
            $view_manager = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
            $view_manager = isset($view_manager['view_manager']) ? $view_manager['view_manager'] : array();
 
            // Check for a maintenance template
            if (isset($view_manager['maintenance_template'])) {
                $maintenance_template = $view_manager['maintenance_template'];
                if (isset($view_manager['template_map'][$maintenance_template])) {
                    $config['template'] = $view_manager['template_map'][$maintenance_template];
                } elseif ($serviceLocator->has('ViewManager')) {
                    $view_manager = $serviceLocator->get('ViewManager');
                    if ($view_manager instanceof HttpViewManager) {
                        $resolver = $view_manager->getResolver();
                        $template = $resolver->resolve($maintenance_template);
                        if ($template) {
                            $config['template'] = $template;
                        }
                    }
                }
            }
        }

        return new Config($config);
    }
}