<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use Exception;
use ReflectionException;
use ReflectionProperty;
use EcomailDeps\Wpify\Core\Exceptions\PluginException;
use EcomailDeps\Wpify\Core\Interfaces\ComponentInterface;
use EcomailDeps\Wpify\Core\Traits\ComponentTrait;
/**
 * @property AbstractPlugin    $plugin
 * @property AbstractComponent $parent
 */
abstract class AbstractComponent implements \EcomailDeps\Wpify\Core\Interfaces\ComponentInterface
{
    use ComponentTrait;
    /**
     * The super init method magic happens
     * @return bool|Exception
     * @throws PluginException
     * @throws ReflectionException|PluginException
     */
    public function init()
    {
        try {
            if ($this->is_error($result = $this->link_components())) {
                return $result;
            }
        } catch (\Exception $e) {
            return $e;
        }
        if ($this->is_error($result = $this->init_components())) {
            return $result;
        }
        try {
            new \ReflectionProperty($this, 'plugin');
            $this->get_plugin();
        } catch (\ReflectionException $e) {
        }
        /**
         * @noinspection DynamicInvocationViaScopeResolutionInspection
         */
        if ($this->is_inited()) {
            return \true;
        }
        $setup = static::setup();
        if (!$this->is_error($setup)) {
            $this->inited = \true;
        }
        return $setup;
    }
}
