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

use EcomailDeps\Monolog\Logger;
use EcomailDeps\Monolog\Formatter\NormalizerFormatter;
use EcomailDeps\Monolog\Formatter\FormatterInterface;
use EcomailDeps\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \EcomailDeps\Monolog\Handler\AbstractProcessingHandler
{
    private $client;
    public function __construct(\EcomailDeps\Doctrine\CouchDB\CouchDBClient $client, $level = \EcomailDeps\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        $this->client = $client;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        $this->client->postDocument($record['formatted']);
    }
    protected function getDefaultFormatter() : \EcomailDeps\Monolog\Formatter\FormatterInterface
    {
        return new \EcomailDeps\Monolog\Formatter\NormalizerFormatter();
    }
}
