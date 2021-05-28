<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Resolver;

use EcomailDeps\DI\Definition\ArrayDefinition;
use EcomailDeps\DI\Definition\DecoratorDefinition;
use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\DI\Definition\EnvironmentVariableDefinition;
use EcomailDeps\DI\Definition\Exception\InvalidDefinition;
use EcomailDeps\DI\Definition\FactoryDefinition;
use EcomailDeps\DI\Definition\InstanceDefinition;
use EcomailDeps\DI\Definition\ObjectDefinition;
use EcomailDeps\DI\Definition\SelfResolvingDefinition;
use EcomailDeps\DI\Proxy\ProxyFactory;
use EcomailDeps\Psr\Container\ContainerInterface;
/**
 * Dispatches to more specific resolvers.
 *
 * Dynamic dispatch pattern.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ResolverDispatcher implements \EcomailDeps\DI\Definition\Resolver\DefinitionResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ProxyFactory
     */
    private $proxyFactory;
    private $arrayResolver;
    private $factoryResolver;
    private $decoratorResolver;
    private $objectResolver;
    private $instanceResolver;
    private $envVariableResolver;
    public function __construct(\EcomailDeps\Psr\Container\ContainerInterface $container, \EcomailDeps\DI\Proxy\ProxyFactory $proxyFactory)
    {
        $this->container = $container;
        $this->proxyFactory = $proxyFactory;
    }
    /**
     * Resolve a definition to a value.
     *
     * @param Definition $definition Object that defines how the value should be obtained.
     * @param array      $parameters Optional parameters to use to build the entry.
     *
     * @throws InvalidDefinition If the definition cannot be resolved.
     *
     * @return mixed Value obtained from the definition.
     */
    public function resolve(\EcomailDeps\DI\Definition\Definition $definition, array $parameters = [])
    {
        // Special case, tested early for speed
        if ($definition instanceof \EcomailDeps\DI\Definition\SelfResolvingDefinition) {
            return $definition->resolve($this->container);
        }
        $definitionResolver = $this->getDefinitionResolver($definition);
        return $definitionResolver->resolve($definition, $parameters);
    }
    public function isResolvable(\EcomailDeps\DI\Definition\Definition $definition, array $parameters = []) : bool
    {
        // Special case, tested early for speed
        if ($definition instanceof \EcomailDeps\DI\Definition\SelfResolvingDefinition) {
            return $definition->isResolvable($this->container);
        }
        $definitionResolver = $this->getDefinitionResolver($definition);
        return $definitionResolver->isResolvable($definition, $parameters);
    }
    /**
     * Returns a resolver capable of handling the given definition.
     *
     * @throws \RuntimeException No definition resolver was found for this type of definition.
     */
    private function getDefinitionResolver(\EcomailDeps\DI\Definition\Definition $definition) : \EcomailDeps\DI\Definition\Resolver\DefinitionResolver
    {
        switch (\true) {
            case $definition instanceof \EcomailDeps\DI\Definition\ObjectDefinition:
                if (!$this->objectResolver) {
                    $this->objectResolver = new \EcomailDeps\DI\Definition\Resolver\ObjectCreator($this, $this->proxyFactory);
                }
                return $this->objectResolver;
            case $definition instanceof \EcomailDeps\DI\Definition\DecoratorDefinition:
                if (!$this->decoratorResolver) {
                    $this->decoratorResolver = new \EcomailDeps\DI\Definition\Resolver\DecoratorResolver($this->container, $this);
                }
                return $this->decoratorResolver;
            case $definition instanceof \EcomailDeps\DI\Definition\FactoryDefinition:
                if (!$this->factoryResolver) {
                    $this->factoryResolver = new \EcomailDeps\DI\Definition\Resolver\FactoryResolver($this->container, $this);
                }
                return $this->factoryResolver;
            case $definition instanceof \EcomailDeps\DI\Definition\ArrayDefinition:
                if (!$this->arrayResolver) {
                    $this->arrayResolver = new \EcomailDeps\DI\Definition\Resolver\ArrayResolver($this);
                }
                return $this->arrayResolver;
            case $definition instanceof \EcomailDeps\DI\Definition\EnvironmentVariableDefinition:
                if (!$this->envVariableResolver) {
                    $this->envVariableResolver = new \EcomailDeps\DI\Definition\Resolver\EnvironmentVariableResolver($this);
                }
                return $this->envVariableResolver;
            case $definition instanceof \EcomailDeps\DI\Definition\InstanceDefinition:
                if (!$this->instanceResolver) {
                    $this->instanceResolver = new \EcomailDeps\DI\Definition\Resolver\InstanceInjector($this, $this->proxyFactory);
                }
                return $this->instanceResolver;
            default:
                throw new \RuntimeException('No definition resolver was configured for definition of type ' . \get_class($definition));
        }
    }
}
