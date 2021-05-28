<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition\Source;

use EcomailDeps\DI\Definition\Definition;
/**
 * Describes a definition source to which we can add new definitions.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface MutableDefinitionSource extends \EcomailDeps\DI\Definition\Source\DefinitionSource
{
    public function addDefinition(\EcomailDeps\DI\Definition\Definition $definition);
}
