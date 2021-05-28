<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace EcomailDeps\Monolog\Handler;

use EcomailDeps\Monolog\Formatter\FormatterInterface;
use EcomailDeps\Monolog\Formatter\NormalizerFormatter;
use EcomailDeps\Monolog\Logger;
/**
 * Handler sending logs to Zend Monitor
 *
 * @author  Christian Bergau <cbergau86@gmail.com>
 * @author  Jason Davis <happydude@jasondavis.net>
 */
class ZendMonitorHandler extends \EcomailDeps\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * Monolog level / ZendMonitor Custom Event priority map
     *
     * @var array
     */
    protected $levelMap = [];
    /**
     * @param  string|int                $level  The minimum logging level at which this handler will be triggered.
     * @param  bool                      $bubble Whether the messages that are handled can bubble up the stack or not.
     * @throws MissingExtensionException
     */
    public function __construct($level = \EcomailDeps\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        if (!\function_exists('EcomailDeps\\zend_monitor_custom_event')) {
            throw new \EcomailDeps\Monolog\Handler\MissingExtensionException('You must have Zend Server installed with Zend Monitor enabled in order to use this handler');
        }
        //zend monitor constants are not defined if zend monitor is not enabled.
        $this->levelMap = [\EcomailDeps\Monolog\Logger::DEBUG => \EcomailDeps\ZEND_MONITOR_EVENT_SEVERITY_INFO, \EcomailDeps\Monolog\Logger::INFO => \EcomailDeps\ZEND_MONITOR_EVENT_SEVERITY_INFO, \EcomailDeps\Monolog\Logger::NOTICE => \EcomailDeps\ZEND_MONITOR_EVENT_SEVERITY_INFO, \EcomailDeps\Monolog\Logger::WARNING => \EcomailDeps\ZEND_MONITOR_EVENT_SEVERITY_WARNING, \EcomailDeps\Monolog\Logger::ERROR => \EcomailDeps\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \EcomailDeps\Monolog\Logger::CRITICAL => \EcomailDeps\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \EcomailDeps\Monolog\Logger::ALERT => \EcomailDeps\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \EcomailDeps\Monolog\Logger::EMERGENCY => \EcomailDeps\ZEND_MONITOR_EVENT_SEVERITY_ERROR];
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritdoc}
     */
    protected function write(array $record) : void
    {
        $this->writeZendMonitorCustomEvent(\EcomailDeps\Monolog\Logger::getLevelName($record['level']), $record['message'], $record['formatted'], $this->levelMap[$record['level']]);
    }
    /**
     * Write to Zend Monitor Events
     * @param string $type      Text displayed in "Class Name (custom)" field
     * @param string $message   Text displayed in "Error String"
     * @param array  $formatted Displayed in Custom Variables tab
     * @param int    $severity  Set the event severity level (-1,0,1)
     */
    protected function writeZendMonitorCustomEvent(string $type, string $message, array $formatted, int $severity) : void
    {
        zend_monitor_custom_event($type, $message, $formatted, $severity);
    }
    /**
     * {@inheritdoc}
     */
    public function getDefaultFormatter() : \EcomailDeps\Monolog\Formatter\FormatterInterface
    {
        return new \EcomailDeps\Monolog\Formatter\NormalizerFormatter();
    }
    public function getLevelMap() : array
    {
        return $this->levelMap;
    }
}
