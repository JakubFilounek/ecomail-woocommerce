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
use EcomailDeps\Monolog\Utils;
use EcomailDeps\Monolog\Formatter\FlowdockFormatter;
use EcomailDeps\Monolog\Formatter\FormatterInterface;
/**
 * Sends notifications through the Flowdock push API
 *
 * This must be configured with a FlowdockFormatter instance via setFormatter()
 *
 * Notes:
 * API token - Flowdock API token
 *
 * @author Dominik Liebler <liebler.dominik@gmail.com>
 * @see https://www.flowdock.com/api/push
 */
class FlowdockHandler extends \EcomailDeps\Monolog\Handler\SocketHandler
{
    /**
     * @var string
     */
    protected $apiToken;
    /**
     * @param string|int $level  The minimum logging level at which this handler will be triggered
     * @param bool       $bubble Whether the messages that are handled can bubble up the stack or not
     *
     * @throws MissingExtensionException if OpenSSL is missing
     */
    public function __construct(string $apiToken, $level = \EcomailDeps\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        if (!\extension_loaded('openssl')) {
            throw new \EcomailDeps\Monolog\Handler\MissingExtensionException('The OpenSSL PHP extension is required to use the FlowdockHandler');
        }
        parent::__construct('ssl://api.flowdock.com:443', $level, $bubble);
        $this->apiToken = $apiToken;
    }
    /**
     * {@inheritdoc}
     */
    public function setFormatter(\EcomailDeps\Monolog\Formatter\FormatterInterface $formatter) : \EcomailDeps\Monolog\Handler\HandlerInterface
    {
        if (!$formatter instanceof \EcomailDeps\Monolog\Formatter\FlowdockFormatter) {
            throw new \InvalidArgumentException('The FlowdockHandler requires an instance of Monolog\\Formatter\\FlowdockFormatter to function correctly');
        }
        return parent::setFormatter($formatter);
    }
    /**
     * Gets the default formatter.
     */
    protected function getDefaultFormatter() : \EcomailDeps\Monolog\Formatter\FormatterInterface
    {
        throw new \InvalidArgumentException('The FlowdockHandler must be configured (via setFormatter) with an instance of Monolog\\Formatter\\FlowdockFormatter to function correctly');
    }
    /**
     * {@inheritdoc}
     *
     * @param array $record
     */
    protected function write(array $record) : void
    {
        parent::write($record);
        $this->closeSocket();
    }
    /**
     * {@inheritdoc}
     */
    protected function generateDataStream(array $record) : string
    {
        $content = $this->buildContent($record);
        return $this->buildHeader($content) . $content;
    }
    /**
     * Builds the body of API call
     */
    private function buildContent(array $record) : string
    {
        return \EcomailDeps\Monolog\Utils::jsonEncode($record['formatted']['flowdock']);
    }
    /**
     * Builds the header of the API Call
     */
    private function buildHeader(string $content) : string
    {
        $header = "POST /v1/messages/team_inbox/" . $this->apiToken . " HTTP/1.1\r\n";
        $header .= "Host: api.flowdock.com\r\n";
        $header .= "Content-Type: application/json\r\n";
        $header .= "Content-Length: " . \strlen($content) . "\r\n";
        $header .= "\r\n";
        return $header;
    }
}
