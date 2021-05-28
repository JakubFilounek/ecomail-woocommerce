<?php

declare (strict_types=1);
namespace EcomailDeps\Invoker;

use EcomailDeps\Invoker\Exception\NotCallableException;
use EcomailDeps\Invoker\Exception\NotEnoughParametersException;
use EcomailDeps\Invoker\ParameterResolver\AssociativeArrayResolver;
use EcomailDeps\Invoker\ParameterResolver\DefaultValueResolver;
use EcomailDeps\Invoker\ParameterResolver\NumericArrayResolver;
use EcomailDeps\Invoker\ParameterResolver\ParameterResolver;
use EcomailDeps\Invoker\ParameterResolver\ResolverChain;
use EcomailDeps\Invoker\Reflection\CallableReflection;
use EcomailDeps\Psr\Container\ContainerInterface;
use ReflectionParameter;
/**
 * Invoke a callable.
 */
class Invoker implements \EcomailDeps\Invoker\InvokerInterface
{
    /** @var CallableResolver|null */
    private $callableResolver;
    /** @var ParameterResolver */
    private $parameterResolver;
    /** @var ContainerInterface|null */
    private $container;
    public function __construct(?\EcomailDeps\Invoker\ParameterResolver\ParameterResolver $parameterResolver = null, ?\EcomailDeps\Psr\Container\ContainerInterface $container = null)
    {
        $this->parameterResolver = $parameterResolver ?: $this->createParameterResolver();
        $this->container = $container;
        if ($container) {
            $this->callableResolver = new \EcomailDeps\Invoker\CallableResolver($container);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function call($callable, array $parameters = [])
    {
        if ($this->callableResolver) {
            $callable = $this->callableResolver->resolve($callable);
        }
        if (!\is_callable($callable)) {
            throw new \EcomailDeps\Invoker\Exception\NotCallableException(\sprintf('%s is not a callable', \is_object($callable) ? 'Instance of ' . \get_class($callable) : \var_export($callable, \true)));
        }
        $callableReflection = \EcomailDeps\Invoker\Reflection\CallableReflection::create($callable);
        $args = $this->parameterResolver->getParameters($callableReflection, $parameters, []);
        // Sort by array key because call_user_func_array ignores numeric keys
        \ksort($args);
        // Check all parameters are resolved
        $diff = \array_diff_key($callableReflection->getParameters(), $args);
        $parameter = \reset($diff);
        if ($parameter && \assert($parameter instanceof \ReflectionParameter) && !$parameter->isVariadic()) {
            throw new \EcomailDeps\Invoker\Exception\NotEnoughParametersException(\sprintf('Unable to invoke the callable because no value was given for parameter %d ($%s)', $parameter->getPosition() + 1, $parameter->name));
        }
        return \call_user_func_array($callable, $args);
    }
    /**
     * Create the default parameter resolver.
     */
    private function createParameterResolver() : \EcomailDeps\Invoker\ParameterResolver\ParameterResolver
    {
        return new \EcomailDeps\Invoker\ParameterResolver\ResolverChain([new \EcomailDeps\Invoker\ParameterResolver\NumericArrayResolver(), new \EcomailDeps\Invoker\ParameterResolver\AssociativeArrayResolver(), new \EcomailDeps\Invoker\ParameterResolver\DefaultValueResolver()]);
    }
    /**
     * @return ParameterResolver By default it's a ResolverChain
     */
    public function getParameterResolver() : \EcomailDeps\Invoker\ParameterResolver\ParameterResolver
    {
        return $this->parameterResolver;
    }
    public function getContainer() : ?\EcomailDeps\Psr\Container\ContainerInterface
    {
        return $this->container;
    }
    /**
     * @return CallableResolver|null Returns null if no container was given in the constructor.
     */
    public function getCallableResolver() : ?\EcomailDeps\Invoker\CallableResolver
    {
        return $this->callableResolver;
    }
}
