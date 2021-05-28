<?php

declare (strict_types=1);
namespace EcomailDeps\DI\Definition;

/**
 * A definition that extends a previous definition with the same name.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ExtendsPreviousDefinition extends \EcomailDeps\DI\Definition\Definition
{
    public function setExtendedDefinition(\EcomailDeps\DI\Definition\Definition $definition);
}
