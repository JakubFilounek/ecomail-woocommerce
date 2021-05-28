<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Invoker;

use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\DI\Definition\Helper\DefinitionHelper;
use EcomailDeps\DI\Definition\Resolver\DefinitionResolver;
use EcomailDeps\Invoker\ParameterResolver\ParameterResolver;
use ReflectionFunctionAbstract;
/**
 * Resolves callable parameters using definitions.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DefinitionParameterResolver implements \EcomailDeps\Invoker\ParameterResolver\ParameterResolver
{
    /**
     * @var DefinitionResolver
     */
    private $definitionResolver;
    public function __construct(\EcomailDeps\DI\Definition\Resolver\DefinitionResolver $definitionResolver)
    {
        $this->definitionResolver = $definitionResolver;
    }
    public function getParameters(\ReflectionFunctionAbstract $reflection, array $providedParameters, array $resolvedParameters) : array
    {
        // Skip parameters already resolved
        if (!empty($resolvedParameters)) {
            $providedParameters = \array_diff_key($providedParameters, $resolvedParameters);
        }
        foreach ($providedParameters as $key => $value) {
            if ($value instanceof \EcomailDeps\DI\Definition\Helper\DefinitionHelper) {
                $value = $value->getDefinition('');
            }
            if (!$value instanceof \EcomailDeps\DI\Definition\Definition) {
                continue;
            }
            $value = $this->definitionResolver->resolve($value);
            if (\is_int($key)) {
                // Indexed by position
                $resolvedParameters[$key] = $value;
            } else {
                // Indexed by parameter name
                // TODO optimize?
                $reflectionParameters = $reflection->getParameters();
                foreach ($reflectionParameters as $reflectionParameter) {
                    if ($key === $reflectionParameter->name) {
                        $resolvedParameters[$reflectionParameter->getPosition()] = $value;
                    }
                }
            }
        }
        return $resolvedParameters;
    }
}
