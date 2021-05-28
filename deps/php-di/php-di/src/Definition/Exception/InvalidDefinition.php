<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Exception;

use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\Psr\Container\ContainerExceptionInterface;
/**
 * Invalid DI definitions.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InvalidDefinition extends \Exception implements \EcomailDeps\Psr\Container\ContainerExceptionInterface
{
    public static function create(\EcomailDeps\DI\Definition\Definition $definition, string $message, \Exception $previous = null) : self
    {
        return new self(\sprintf('%s' . \PHP_EOL . 'Full definition:' . \PHP_EOL . '%s', $message, (string) $definition), 0, $previous);
    }
}
