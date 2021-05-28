<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition;

use EcomailDeps\Psr\Container\ContainerInterface;
/**
 * Definition of a value for dependency injection.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ValueDefinition implements \EcomailDeps\DI\Definition\Definition, \EcomailDeps\DI\Definition\SelfResolvingDefinition
{
    /**
     * Entry name.
     * @var string
     */
    private $name = '';
    /**
     * @var mixed
     */
    private $value;
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
    public function resolve(\EcomailDeps\Psr\Container\ContainerInterface $container)
    {
        return $this->getValue();
    }
    public function isResolvable(\EcomailDeps\Psr\Container\ContainerInterface $container) : bool
    {
        return \true;
    }
    public function replaceNestedDefinitions(callable $replacer)
    {
        // no nested definitions
    }
    public function __toString()
    {
        return \sprintf('Value (%s)', \var_export($this->value, \true));
    }
}
