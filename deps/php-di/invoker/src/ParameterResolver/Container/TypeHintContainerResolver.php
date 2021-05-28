<?php

declare (strict_types=1);
namespace EcomailDeps\Invoker\ParameterResolver\Container;

use EcomailDeps\Invoker\ParameterResolver\ParameterResolver;
use EcomailDeps\Psr\Container\ContainerInterface;
use ReflectionFunctionAbstract;
use ReflectionNamedType;
/**
 * Inject entries from a DI container using the type-hints.
 */
class TypeHintContainerResolver implements \EcomailDeps\Invoker\ParameterResolver\ParameterResolver
{
    /** @var ContainerInterface */
    private $container;
    /**
     * @param ContainerInterface $container The container to get entries from.
     */
    public function __construct(\EcomailDeps\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function getParameters(\ReflectionFunctionAbstract $reflection, array $providedParameters, array $resolvedParameters) : array
    {
        $parameters = $reflection->getParameters();
        // Skip parameters already resolved
        if (!empty($resolvedParameters)) {
            $parameters = \array_diff_key($parameters, $resolvedParameters);
        }
        foreach ($parameters as $index => $parameter) {
            $parameterType = $parameter->getType();
            if (!$parameterType) {
                // No type
                continue;
            }
            if ($parameterType->isBuiltin()) {
                // Primitive types are not supported
                continue;
            }
            if (!$parameterType instanceof \ReflectionNamedType) {
                // Union types are not supported
                continue;
            }
            $parameterClass = $parameterType->getName();
            if ($parameterClass === 'self') {
                $parameterClass = $parameter->getDeclaringClass()->getName();
            }
            if ($this->container->has($parameterClass)) {
                $resolvedParameters[$index] = $this->container->get($parameterClass);
            }
        }
        return $resolvedParameters;
    }
}
