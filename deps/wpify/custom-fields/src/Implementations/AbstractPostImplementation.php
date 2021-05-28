<?php

namespace EcomailDeps\WpifyCustomFields\Implementations;

/**
 * Class AbstractPostImplementation
 * @package WpifyCustomFields\Implementations
 */
abstract class AbstractPostImplementation extends \EcomailDeps\WpifyCustomFields\Implementations\AbstractImplementation
{
    /**
     * @param number $post_id
     *
     * @return void
     */
    abstract function set_post($post_id);
}
