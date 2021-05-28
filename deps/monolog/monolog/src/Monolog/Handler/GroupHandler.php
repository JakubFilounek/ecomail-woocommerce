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
use EcomailDeps\Monolog\ResettableInterface;
/**
 * Forwards records to multiple handlers
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 */
class GroupHandler extends \EcomailDeps\Monolog\Handler\Handler implements \EcomailDeps\Monolog\Handler\ProcessableHandlerInterface, \EcomailDeps\Monolog\ResettableInterface
{
    use ProcessableHandlerTrait;
    /** @var HandlerInterface[] */
    protected $handlers;
    protected $bubble;
    /**
     * @param HandlerInterface[] $handlers Array of Handlers.
     * @param bool               $bubble   Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(array $handlers, bool $bubble = \true)
    {
        foreach ($handlers as $handler) {
            if (!$handler instanceof \EcomailDeps\Monolog\Handler\HandlerInterface) {
                throw new \InvalidArgumentException('The first argument of the GroupHandler must be an array of HandlerInterface instances.');
            }
        }
        $this->handlers = $handlers;
        $this->bubble = $bubble;
    }
    /**
     * {@inheritdoc}
     */
    public function isHandling(array $record) : bool
    {
        foreach ($this->handlers as $handler) {
            if ($handler->isHandling($record)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function handle(array $record) : bool
    {
        if ($this->processors) {
            $record = $this->processRecord($record);
        }
        foreach ($this->handlers as $handler) {
            $handler->handle($record);
        }
        return \false === $this->bubble;
    }
    /**
     * {@inheritdoc}
     */
    public function handleBatch(array $records) : void
    {
        if ($this->processors) {
            $processed = [];
            foreach ($records as $record) {
                $processed[] = $this->processRecord($record);
            }
            $records = $processed;
        }
        foreach ($this->handlers as $handler) {
            $handler->handleBatch($records);
        }
    }
    public function reset()
    {
        $this->resetProcessors();
        foreach ($this->handlers as $handler) {
            if ($handler instanceof \EcomailDeps\Monolog\ResettableInterface) {
                $handler->reset();
            }
        }
    }
    public function close() : void
    {
        parent::close();
        foreach ($this->handlers as $handler) {
            $handler->close();
        }
    }
    /**
     * {@inheritdoc}
     */
    public function setFormatter(\EcomailDeps\Monolog\Formatter\FormatterInterface $formatter) : \EcomailDeps\Monolog\Handler\HandlerInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof \EcomailDeps\Monolog\Handler\FormattableHandlerInterface) {
                $handler->setFormatter($formatter);
            }
        }
        return $this;
    }
}
