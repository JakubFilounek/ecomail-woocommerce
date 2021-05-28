<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Source;

use EcomailDeps\DI\Definition\ObjectDefinition;
use EcomailDeps\DI\Definition\ObjectDefinition\MethodInjection;
use EcomailDeps\DI\Definition\Reference;
use ReflectionNamedType;
/**
 * Reads DI class definitions using reflection.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectionBasedAutowiring implements \EcomailDeps\DI\Definition\Source\DefinitionSource, \EcomailDeps\DI\Definition\Source\Autowiring
{
    public function autowire(string $name, \EcomailDeps\DI\Definition\ObjectDefinition $definition = null)
    {
        $className = $definition ? $definition->getClassName() : $name;
        if (!\class_exists($className) && !\interface_exists($className)) {
            return $definition;
        }
        $definition = $definition ?: new \EcomailDeps\DI\Definition\ObjectDefinition($name);
        // Constructor
        $class = new \ReflectionClass($className);
        $constructor = $class->getConstructor();
        if ($constructor && $constructor->isPublic()) {
            $constructorInjection = \EcomailDeps\DI\Definition\ObjectDefinition\MethodInjection::constructor($this->getParametersDefinition($constructor));
            $definition->completeConstructorInjection($constructorInjection);
        }
        return $definition;
    }
    public function getDefinition(string $name)
    {
        return $this->autowire($name);
    }
    /**
     * Autowiring cannot guess all existing definitions.
     */
    public function getDefinitions() : array
    {
        return [];
    }
    /**
     * Read the type-hinting from the parameters of the function.
     */
    private function getParametersDefinition(\ReflectionFunctionAbstract $constructor) : array
    {
        $parameters = [];
        foreach ($constructor->getParameters() as $index => $parameter) {
            // Skip optional parameters
            if ($parameter->isOptional()) {
                continue;
            }
            $parameterType = $parameter->getType();
            if (!$parameterType) {
                // No type
                continue;
            }
            if (!$parameterType instanceof \ReflectionNamedType) {
                // Union types are not supported
                continue;
            }
            if ($parameterType->isBuiltin()) {
                // Primitive types are not supported
                continue;
            }
            $parameters[$index] = new \EcomailDeps\DI\Definition\Reference($parameterType->getName());
        }
        return $parameters;
    }
}
