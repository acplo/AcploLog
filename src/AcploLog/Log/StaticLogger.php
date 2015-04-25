<?php
/**
 * Development logger
 *
 * @package   AcploLog\Log
 * @author    Abel Lopes <abel@abellpes.eti.br>
* @link      http://www.abellpes.eti.br Development Blog
 * @link      http://github.com/acplo/AcploLog for the canonical source repository
 * @copyright Copyright (c) 2015-2020 Abel Lopes (http://www.abellpes.eti.br)
 * @license   http://www.abellpes.eti.br/licenca-bsd New BSD license
 */
namespace AcploLog\Log;

/**
 * Development logger
 *
 * @package   AcploLog\Log
 * @author    Abel Lopes <abel@abellpes.eti.br>
* @link      http://www.abellpes.eti.br Development Blog
 * @link      http://github.com/acplo/AcploLog for the canonical source repository
 * @copyright Copyright (c) 2015-2020 Abel Lopes (http://www.abellpes.eti.br)
 * @license   http://www.abellpes.eti.br/licenca-bsd New BSD license
 */
class StaticLogger extends AbstractLogger
{
    /**
     * Logger instance
     *
     * @var \AcploLog\Log\StaticLogger
     */
    protected static $instance;

    /**
     * Saves a message to a logfile
     *
     * @param mixed  $message
     * @param string $logFile
     * @param string $logDir
     */
    public static function save($message, $logFile = 'static.log', $logDir = 'data/logs')
    {
        if ($logFile === null) {
            // Useful for just Z-Ray logging
            return;
        }
        $logger = static::getInstance($logFile, $logDir);
        if (is_object($message) && $message instanceof LoggableObject) {
            $message = json_encode($message->acploLogMe());
        }
        $logger->debug($message);
    }

    /**
     * Gets an instance of this logger and sets the log directory and filename
     *
     * @param  string                   $logFile
     * @param  string                   $logDir
     * @return \AcploLog\Log\StaticLogger
     */
    public static function getInstance($logFile = 'static.log', $logDir = 'data/logs')
    {
        if (static::$instance instanceof StaticLogger) {
            return static::$instance;
        }
        static::$instance = new self($logFile, $logDir);

        return static::$instance;
    }
}
