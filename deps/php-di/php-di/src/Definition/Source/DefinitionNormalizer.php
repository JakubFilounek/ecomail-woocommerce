<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Source;

use EcomailDeps\DI\Definition\ArrayDefinition;
use EcomailDeps\DI\Definition\AutowireDefinition;
use EcomailDeps\DI\Definition\DecoratorDefinition;
use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\DI\Definition\Exception\InvalidDefinition;
use EcomailDeps\DI\Definition\FactoryDefinition;
use EcomailDeps\DI\Definition\Helper\DefinitionHelper;
use EcomailDeps\DI\Definition\ObjectDefinition;
use EcomailDeps\DI\Definition\ValueDefinition;
/**
 * Turns raw definitions/definition helpers into definitions ready
 * to be resolved or compiled.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DefinitionNormalizer
{
    /**
     * @var Autowiring
     */
    private $autowiring;
    public function __construct(\EcomailDeps\DI\Definition\Source\Autowiring $autowiring)
    {
        $this->autowiring = $autowiring;
    }
    /**
     * Normalize a definition that is *not* nested in another one.
     *
     * This is usually a definition declared at the root of a definition array.
     *
     * @param mixed $definition
     * @param string $name The definition name.
     * @param string[] $wildcardsReplacements Replacements for wildcard definitions.
     *
     * @throws InvalidDefinition
     */
    public function normalizeRootDefinition($definition, string $name, array $wildcardsReplacements = null) : \EcomailDeps\DI\Definition\Definition
    {
        if ($definition instanceof \EcomailDeps\DI\Definition\Helper\DefinitionHelper) {
            $definition = $definition->getDefinition($name);
        } elseif (\is_array($definition)) {
            $definition = new \EcomailDeps\DI\Definition\ArrayDefinition($definition);
        } elseif ($definition instanceof \Closure) {
            $definition = new \EcomailDeps\DI\Definition\FactoryDefinition($name, $definition);
        } elseif (!$definition instanceof \EcomailDeps\DI\Definition\Definition) {
            $definition = new \EcomailDeps\DI\Definition\ValueDefinition($definition);
        }
        // For a class definition, we replace * in the class name with the matches
        // *Interface -> *Impl => FooInterface -> FooImpl
        if ($wildcardsReplacements && $definition instanceof \EcomailDeps\DI\Definition\ObjectDefinition) {
            $definition->replaceWildcards($wildcardsReplacements);
        }
        if ($definition instanceof \EcomailDeps\DI\Definition\AutowireDefinition) {
            $definition = $this->autowiring->autowire($name, $definition);
        }
        $definition->setName($name);
        try {
            $definition->replaceNestedDefinitions([$this, 'normalizeNestedDefinition']);
        } catch (\EcomailDeps\DI\Definition\Exception\InvalidDefinition $e) {
            throw \EcomailDeps\DI\Definition\Exception\InvalidDefinition::create($definition, \sprintf('Definition "%s" contains an error: %s', $definition->getName(), $e->getMessage()), $e);
        }
        return $definition;
    }
    /**
     * Normalize a definition that is nested in another one.
     *
     * @param mixed $definition
     * @return mixed
     *
     * @throws InvalidDefinition
     */
    public function normalizeNestedDefinition($definition)
    {
        $name = '<nested definition>';
        if ($definition instanceof \EcomailDeps\DI\Definition\Helper\DefinitionHelper) {
            $definition = $definition->getDefinition($name);
        } elseif (\is_array($definition)) {
            $definition = new \EcomailDeps\DI\Definition\ArrayDefinition($definition);
        } elseif ($definition instanceof \Closure) {
            $definition = new \EcomailDeps\DI\Definition\FactoryDefinition($name, $definition);
        }
        if ($definition instanceof \EcomailDeps\DI\Definition\DecoratorDefinition) {
            throw new \EcomailDeps\DI\Definition\Exception\InvalidDefinition('Decorators cannot be nested in another definition');
        }
        if ($definition instanceof \EcomailDeps\DI\Definition\AutowireDefinition) {
            $definition = $this->autowiring->autowire($name, $definition);
        }
        if ($definition instanceof \EcomailDeps\DI\Definition\Definition) {
            $definition->setName($name);
            // Recursively traverse nested definitions
            $definition->replaceNestedDefinitions([$this, 'normalizeNestedDefinition']);
        }
        return $definition;
    }
}
