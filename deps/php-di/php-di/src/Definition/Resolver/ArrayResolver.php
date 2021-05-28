<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Resolver;

use EcomailDeps\DI\Definition\ArrayDefinition;
use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\DI\DependencyException;
use Exception;
/**
 * Resolves an array definition to a value.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ArrayResolver implements \EcomailDeps\DI\Definition\Resolver\DefinitionResolver
{
    /**
     * @var DefinitionResolver
     */
    private $definitionResolver;
    /**
     * @param DefinitionResolver $definitionResolver Used to resolve nested definitions.
     */
    public function __construct(\EcomailDeps\DI\Definition\Resolver\DefinitionResolver $definitionResolver)
    {
        $this->definitionResolver = $definitionResolver;
    }
    /**
     * Resolve an array definition to a value.
     *
     * An array definition can contain simple values or references to other entries.
     *
     * @param ArrayDefinition $definition
     */
    public function resolve(\EcomailDeps\DI\Definition\Definition $definition, array $parameters = []) : array
    {
        $values = $definition->getValues();
        // Resolve nested definitions
        \array_walk_recursive($values, function (&$value, $key) use($definition) {
            if ($value instanceof \EcomailDeps\DI\Definition\Definition) {
                $value = $this->resolveDefinition($value, $definition, $key);
            }
        });
        return $values;
    }
    public function isResolvable(\EcomailDeps\DI\Definition\Definition $definition, array $parameters = []) : bool
    {
        return \true;
    }
    private function resolveDefinition(\EcomailDeps\DI\Definition\Definition $value, \EcomailDeps\DI\Definition\ArrayDefinition $definition, $key)
    {
        try {
            return $this->definitionResolver->resolve($value);
        } catch (\EcomailDeps\DI\DependencyException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \EcomailDeps\DI\DependencyException(\sprintf('Error while resolving %s[%s]. %s', $definition->getName(), $key, $e->getMessage()), 0, $e);
        }
    }
}
