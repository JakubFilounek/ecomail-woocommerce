<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Source;

use EcomailDeps\DI\Definition\Exception\InvalidDefinition;
use EcomailDeps\DI\Definition\ObjectDefinition;
/**
 * Implementation used when autowiring is completely disabled.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class NoAutowiring implements \EcomailDeps\DI\Definition\Source\Autowiring
{
    public function autowire(string $name, \EcomailDeps\DI\Definition\ObjectDefinition $definition = null)
    {
        throw new \EcomailDeps\DI\Definition\Exception\InvalidDefinition(\sprintf('Cannot autowire entry "%s" because autowiring is disabled', $name));
    }
}
