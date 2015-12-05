<?php
/**
 * Maintenance mode component for ZF2
 *
 * @author    Juan Pedro Gonzalez Gutierrez
 * @link      http://github.com/zend-modules/maintenance
 * @copyright Copyright (c) 2015 Juan Pedro Gonzalez Gutierrez
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPL v3
 */

namespace Maintenance;

class Config
{
    /**
     * Maintenance mode enabled/disabled
     * @var bool
     */
    protected $enabled = false;

    /**
     * HTTP status code for maintenance mode.
     *  
     * @var int
     */
    protected $statusCode = 503;

    /**
     * The layout template.
     * 
     * @var string|null
     */
    protected $template = null;
    
    /**
     * Whitelist for maintenance mode.
     * 
     * @var array
     */
    protected $whitelist = array();

    public function __construct($config = array())
    {
        if (isset($config['enabled'])) {
            $this->setEnabled($config['enabled']);
        }

        if (isset($config['status_code'])) {
            $this->setStatusCode($config['status_code']);
        }

        if (isset($config['template'])) {
            $this->setTemplate($config['template']);
        }

        if (isset($config['whitelist'])) {
            $this->setWhitelist($config['whitelist']);
        }
    }

    /**
     * Get the HTTP status code for maintenance mode.
     * 
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get the layout template.
     * 
     * @return string
     */
    public function getTemplate()
    {
        if (null === $this->template) {
            $this->template = dirname(__DIR__) . '/view/maintenance.phtml';
        }
        return $this->template;
    }

    /**
     * Get the whitelist.
     * 
     * @return array
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }

    /**
     * Check if the maintenance mode is enabled.
     * 
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable or disable the maintenance mode.
     * 
     * @param bool $enabled
     */
    public function setEnabled($enabled = true)
    {
        $this->enabled = (bool)$enabled;
        return $this;
    }
 
    /**
     * Set the HTTP status code for maintenance mode.
     * 
     * @param int $code
     * @return MaintenanceConfig
     */
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Set the layout template.
     * 
     * @param string|null $template
     */
    public function setTemplate($template = null)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Set the whitelist.
     * 
     * @param array $whitelist
     * @return MaintenanceConfig
     */
    public function setWhitelist($whitelist)
    {
        $this->whitelist = $whitelist;
        return $this;
    }
}