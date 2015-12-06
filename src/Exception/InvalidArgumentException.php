<?php
/**
 * Maintenance mode component for ZF2
 *
 * @author    Juan Pedro Gonzalez Gutierrez
 * @link      http://github.com/zend-modules/maintenance
 * @copyright Copyright (c) 2015 Juan Pedro Gonzalez Gutierrez
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPL v3
 */

namespace Maintenance\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
}