<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Resolver;

use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\DI\Definition\EnvironmentVariableDefinition;
use EcomailDeps\DI\Definition\Exception\InvalidDefinition;
/**
 * Resolves a environment variable definition to a value.
 *
 * @author James Harris <james.harris@icecave.com.au>
 */
class EnvironmentVariableResolver implements \EcomailDeps\DI\Definition\Resolver\DefinitionResolver
{
    /**
     * @var DefinitionResolver
     */
    private $definitionResolver;
    /**
     * @var callable
     */
    private $variableReader;
    public function __construct(\EcomailDeps\DI\Definition\Resolver\DefinitionResolver $definitionResolver, $variableReader = null)
    {
        $this->definitionResolver = $definitionResolver;
        $this->variableReader = $variableReader ?? [$this, 'getEnvVariable'];
    }
    /**
     * Resolve an environment variable definition to a value.
     *
     * @param EnvironmentVariableDefinition $definition
     */
    public function resolve(\EcomailDeps\DI\Definition\Definition $definition, array $parameters = [])
    {
        $value = \call_user_func($this->variableReader, $definition->getVariableName());
        if (\false !== $value) {
            return $value;
        }
        if (!$definition->isOptional()) {
            throw new \EcomailDeps\DI\Definition\Exception\InvalidDefinition(\sprintf("The environment variable '%s' has not been defined", $definition->getVariableName()));
        }
        $value = $definition->getDefaultValue();
        // Nested definition
        if ($value instanceof \EcomailDeps\DI\Definition\Definition) {
            return $this->definitionResolver->resolve($value);
        }
        return $value;
    }
    public function isResolvable(\EcomailDeps\DI\Definition\Definition $definition, array $parameters = []) : bool
    {
        return \true;
    }
    protected function getEnvVariable(string $variableName)
    {
        if (isset($_ENV[$variableName])) {
            return $_ENV[$variableName];
        } elseif (isset($_SERVER[$variableName])) {
            return $_SERVER[$variableName];
        }
        return \getenv($variableName);
    }
}
