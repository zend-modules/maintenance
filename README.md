# Maintenance Mode Component for ZF2 #

## Installation ##

### Main Setup ###

#### With composer (Recomended) ####

1. Add this project in your composer.json:

    ```json
    "require": {
        "zend-modules/maintenance": "dev-master"
    }
    ```

2. Now tell composer to download the maintenance mode component by running the command:

    ```bash
    $ php composer.phar update
    ```

#### By cloning the project ####

1. Clone this project into your `./vendor/` directory.

**Warning** This installation type will only allow the component to be installed as a module. 

### Post installation ###

There are two ways to set this component.

#### As a component ####

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        // ...
        'service_manager' => array(
            'factories' => array(
                'MaintenanceConfig' => 'Maintenance\Service\MaintenanceConfigFactory',
            ),
            'invokables' => array(
                'MaintenanceListener => 'Maintenance\Service\MaintenanceListener',
            ),
        ),
        'listeners' => array(
            'MaintenanceListener',
        ),
    );
    ```

#### As a module ####

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'Maintenance',
        ),
        // ...
    );
    ```

### Configuration ###

The configuration must be made in your `application.config.php` file. This is so as the maintenance module will take effect over all your application. The main entry will be `maintenance_mode`.

#### Enable Maintenance Mode ###

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        // ...
        'maintenance_mode' => array(
            'enabled' => true,
        ),
        // ...
    );
    ```
The default value for `enabled` is `false`. Therefore, to disable you may comment out the line or set it to `false`.

#### Enabled Maintenance Mode Access ####

You may enable certain IP addresses to access yur site during maintenance mode. To do so you must define the whitelist of IP addresses.

1. Set the whitelist in your `application.config.php`file.

    ```php
    <?php
    return array(
        // ...
        'maintenance_mode' => array(
            'enabled'   => true,
            'whitelist' => array(
                '127.0.0.1',
            ),
        ),
        // ...
    );
    ```

#### Setting a custom template ####

1. Set the template path in your `application.config.php`file.

    ```php
    <?php
    return array(
        // ...
        'maintenance_mode' => array(
            'enabled'   => true,
            'template' => dirname(__DIR__) . '/views/layout/maintenance.phtml',
        ),
        // ...
    );
    ```

#### HTTP Status Code ####

By default, the server will return a 503 (Service Unavailable) HTTP status code when in maintenance mode. If you wish to change the HTTP status code for any reason you may do so.

1. Set the desired HTTP status code in your `application.config.php`file.

    ```php
    <?php
    return array(
        // ...
        'maintenance_mode' => array(
            'status_code' => 500,
        ),
        // ...
    );
    ```

### Runtime Configuration ###

Sometimes we may wish to set the maintenance mode options from another source such as a database backend. This can be done with no problem as the maintenance mode configuration is stored in the service manager. Simply make your changes on the bootstrap event. As an example:

    ```php
    <?php
    namespace Application;
    
    use Zend\EventManager\EventInterface;
    
    class Module
    {
        public function onBootstrap(EventInterface $e)
        {
            if (!$e instanceof MvcEvent) {
               return;
            }
    
            $serviceManager    = $e->getApplication()->getServiceManager();
            $maintenanceConfig = $serviceManager->get('MaintenanceConfig');
            $maintenanceConfig->setEnabled(true);
        }
    }
    ```