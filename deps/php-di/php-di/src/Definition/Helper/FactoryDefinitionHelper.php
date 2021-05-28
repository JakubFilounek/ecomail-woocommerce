<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Helper;

use EcomailDeps\DI\Definition\DecoratorDefinition;
use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\DI\Definition\FactoryDefinition;
/**
 * Helps defining how to create an instance of a class using a factory (callable).
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FactoryDefinitionHelper implements \EcomailDeps\DI\Definition\Helper\DefinitionHelper
{
    /**
     * @var callable
     */
    private $factory;
    /**
     * @var bool
     */
    private $decorate;
    /**
     * @var array
     */
    private $parameters = [];
    /**
     * @param callable $factory
     * @param bool $decorate Is the factory decorating a previous definition?
     */
    public function __construct($factory, bool $decorate = \false)
    {
        $this->factory = $factory;
        $this->decorate = $decorate;
    }
    /**
     * @param string $entryName Container entry name
     * @return FactoryDefinition
     */
    public function getDefinition(string $entryName) : \EcomailDeps\DI\Definition\Definition
    {
        if ($this->decorate) {
            return new \EcomailDeps\DI\Definition\DecoratorDefinition($entryName, $this->factory, $this->parameters);
        }
        return new \EcomailDeps\DI\Definition\FactoryDefinition($entryName, $this->factory, $this->parameters);
    }
    /**
     * Defines arguments to pass to the factory.
     *
     * Because factory methods do not yet support annotations or autowiring, this method
     * should be used to define all parameters except the ContainerInterface and RequestedEntry.
     *
     * Multiple calls can be made to the method to override individual values.
     *
     * @param string $parameter Name or index of the parameter for which the value will be given.
     * @param mixed  $value     Value to give to this parameter.
     *
     * @return $this
     */
    public function parameter(string $parameter, $value)
    {
        $this->parameters[$parameter] = $value;
        return $this;
    }
}
