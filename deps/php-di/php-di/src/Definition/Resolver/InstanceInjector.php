<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Resolver;

use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\DI\Definition\InstanceDefinition;
use EcomailDeps\DI\DependencyException;
use EcomailDeps\Psr\Container\NotFoundExceptionInterface;
/**
 * Injects dependencies on an existing instance.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InstanceInjector extends \EcomailDeps\DI\Definition\Resolver\ObjectCreator
{
    /**
     * Injects dependencies on an existing instance.
     *
     * @param InstanceDefinition $definition
     */
    public function resolve(\EcomailDeps\DI\Definition\Definition $definition, array $parameters = [])
    {
        try {
            $this->injectMethodsAndProperties($definition->getInstance(), $definition->getObjectDefinition());
        } catch (\EcomailDeps\Psr\Container\NotFoundExceptionInterface $e) {
            $message = \sprintf('Error while injecting dependencies into %s: %s', \get_class($definition->getInstance()), $e->getMessage());
            throw new \EcomailDeps\DI\DependencyException($message, 0, $e);
        }
        return $definition;
    }
    public function isResolvable(\EcomailDeps\DI\Definition\Definition $definition, array $parameters = []) : bool
    {
        return \true;
    }
}
