<?php
/**
 * Maintenance mode component for ZF2
 *
 * @author    Juan Pedro Gonzalez Gutierrez
 * @link      http://github.com/zend-modules/maintenance
 * @copyright Copyright (c) 2015 Juan Pedro Gonzalez Gutierrez
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPL v3
 */

namespace Maintenance\Listener;

use Maintenance\Config;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Header\RetryAfter;
use Zend\Http\PhpEnvironment\Request as PhpRequest;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;


class MaintenanceListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -9999);
    }

    public function onRoute(MvcEvent $e)
    {
        $request = $e->getRequest();
        if (!$request instanceof HttpRequest) {
            return;
        }

        $application    = $e->getApplication();
        $serviceLocator = $application->getServiceManager();

        // Load the configuration for maintenance mode
        if ($serviceLocator->has('MaintenanceConfig')) {
            $config = $serviceLocator->get('MaintenanceConfig');
        } else {
            $config = new Config();
        }

        if (!$config->isEnabled()) {
            // Maintenance mode is disabled.
            return;
        }

        // Check the white list
        if ($request instanceof PhpRequest) {
            $address = $request->getServer('REMOTE_ADDR', null);
        } else {
            $address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        }

        if (!empty($address)) {
            if (in_array($address, $config->getWhitelist())) {
                return;
            }
        }
        
        // Render the maintenance layout
        $renderer = new PhpRenderer();
        if ($serviceLocator->has('ViewHelperManager')) {
            $renderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));
        }

        $resolver = new TemplateMapResolver();
        $resolver->add('maintenance', $config->getTemplate());
        $renderer->setResolver($resolver);
        $content = $renderer->render('maintenance');

        // Set the response
        $response = $e->getResponse();
        if (!$response instanceof HttpResponse) {
            $response = new HttpResponse();
        }
        
        $response->setStatusCode( $config->getStatusCode() );
        if (!$response->getHeaders()->has('Retry-After')) {
            $retryAfter = new RetryAfter();
            $response->getHeaders()->addHeader($retryAfter);
        }
        $response->setContent($content);
        $e->setResponse($response);

        // Return the response
        return $response;
    }
}