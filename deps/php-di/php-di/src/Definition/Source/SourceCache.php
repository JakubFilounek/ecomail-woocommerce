<?php

namespace EcomailDeps\DI\Definition\Source;

use EcomailDeps\DI\Definition\AutowireDefinition;
use EcomailDeps\DI\Definition\Definition;
use EcomailDeps\DI\Definition\ObjectDefinition;
/**
 * Decorator that caches another definition source.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SourceCache implements \EcomailDeps\DI\Definition\Source\DefinitionSource, \EcomailDeps\DI\Definition\Source\MutableDefinitionSource
{
    /**
     * @var string
     */
    const CACHE_KEY = 'php-di.definitions.';
    /**
     * @var DefinitionSource
     */
    private $cachedSource;
    /**
     * @var string
     */
    private $cacheNamespace;
    public function __construct(\EcomailDeps\DI\Definition\Source\DefinitionSource $cachedSource, string $cacheNamespace = '')
    {
        $this->cachedSource = $cachedSource;
        $this->cacheNamespace = $cacheNamespace;
    }
    public function getDefinition(string $name)
    {
        $definition = \apcu_fetch($this->getCacheKey($name));
        if ($definition === \false) {
            $definition = $this->cachedSource->getDefinition($name);
            // Update the cache
            if ($this->shouldBeCached($definition)) {
                \apcu_store($this->getCacheKey($name), $definition);
            }
        }
        return $definition;
    }
    /**
     * Used only for the compilation so we can skip the cache safely.
     */
    public function getDefinitions() : array
    {
        return $this->cachedSource->getDefinitions();
    }
    public static function isSupported() : bool
    {
        return \function_exists('apcu_fetch') && \ini_get('apc.enabled') && !('cli' === \PHP_SAPI && !\ini_get('apc.enable_cli'));
    }
    public function getCacheKey(string $name) : string
    {
        return self::CACHE_KEY . $this->cacheNamespace . $name;
    }
    public function addDefinition(\EcomailDeps\DI\Definition\Definition $definition)
    {
        throw new \LogicException('You cannot set a definition at runtime on a container that has caching enabled. Doing so would risk caching the definition for the next execution, where it might be different. You can either put your definitions in a file, remove the cache or ->set() a raw value directly (PHP object, string, int, ...) instead of a PHP-DI definition.');
    }
    private function shouldBeCached(\EcomailDeps\DI\Definition\Definition $definition = null) : bool
    {
        return $definition === null || $definition instanceof \EcomailDeps\DI\Definition\ObjectDefinition || $definition instanceof \EcomailDeps\DI\Definition\AutowireDefinition;
    }
}
