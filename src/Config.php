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

use DateTime;
use Maintenance\Exception;

class Config
{
    /**
     * Maintenance mode enabled/disabled
     * @var bool
     */
    protected $enabled = false;

    /**
     * The retry-after time
     * 
     * @var DateTime
     */
    protected $retryAfter = null;

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

        if (isset($config['retry_after'])) {
            $this->setRetryAfter($config['retry_after']);
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
     * Get the retry-after date.
     * 
     * @return DateTime|null
     */
    public function getRetryAfter()
    {
        if ($this->retryAfter instanceof DateTime) {
            $current = new DateTime();
            if ($this->retryAfter > $current) {
                return $this->retryAfter;
            }
        }
        return null;
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
     * @return Config
     */
    public function setEnabled($enabled = true)
    {
        $this->enabled = (bool)$enabled;
        return $this;
    }
 
    /**
     * Set the retry-after date.
     * 
     * @param string|int|DateTime $date
     * @return Config
     */
    public function setRetryAfter($date)
    {
        if (is_string($date)) {
            $this->retryAfter = new \DateTime($date);
        } elseif (is_int($date)) {
            $this->retryAfter = new \DateTime('@' . $date);
        } elseif ($date instanceof \DateTime) {
            $this->retryAfter = $date;
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Date should be a string, integer or ' .
                'instance of \DateTime; received "%s"',
                __NAMESPACE__,
                (is_object($date) ? get_class($date) : gettype($date))
            ));
        }
        
        return $this;
    }

    /**
     * Set the HTTP status code for maintenance mode.
     * 
     * @param int $code
     * @return Config
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