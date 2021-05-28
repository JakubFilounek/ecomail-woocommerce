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
namespace EcomailDeps\Monolog\Test;

use EcomailDeps\Monolog\Logger;
use EcomailDeps\Monolog\DateTimeImmutable;
use EcomailDeps\Monolog\Formatter\FormatterInterface;
/**
 * Lets you easily generate log records and a dummy formatter for testing purposes
 * *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class TestCase extends \EcomailDeps\PHPUnit\Framework\TestCase
{
    /**
     * @return array Record
     */
    protected function getRecord($level = \EcomailDeps\Monolog\Logger::WARNING, $message = 'test', array $context = []) : array
    {
        return ['message' => (string) $message, 'context' => $context, 'level' => $level, 'level_name' => \EcomailDeps\Monolog\Logger::getLevelName($level), 'channel' => 'test', 'datetime' => new \EcomailDeps\Monolog\DateTimeImmutable(\true), 'extra' => []];
    }
    protected function getMultipleRecords() : array
    {
        return [$this->getRecord(\EcomailDeps\Monolog\Logger::DEBUG, 'debug message 1'), $this->getRecord(\EcomailDeps\Monolog\Logger::DEBUG, 'debug message 2'), $this->getRecord(\EcomailDeps\Monolog\Logger::INFO, 'information'), $this->getRecord(\EcomailDeps\Monolog\Logger::WARNING, 'warning'), $this->getRecord(\EcomailDeps\Monolog\Logger::ERROR, 'error')];
    }
    protected function getIdentityFormatter() : \EcomailDeps\Monolog\Formatter\FormatterInterface
    {
        $formatter = $this->createMock(\EcomailDeps\Monolog\Formatter\FormatterInterface::class);
        $formatter->expects($this->any())->method('format')->will($this->returnCallback(function ($record) {
            return $record['message'];
        }));
        return $formatter;
    }
}
