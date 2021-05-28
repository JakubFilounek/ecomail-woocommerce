<?php

namespace EcomailDeps;

use EcomailDeps\WpifyCustomFields\WpifyCustomFields;
if (!\function_exists('EcomailDeps\\get_wpify_custom_fields')) {
    /**
     * Gets an instance of the WCF plugin
     *
     * @return WpifyCustomFields
     */
    function get_wpify_custom_fields() : \EcomailDeps\WpifyCustomFields\WpifyCustomFields
    {
        static $plugin;
        if (empty($plugin)) {
            $plugin = new \EcomailDeps\WpifyCustomFields\WpifyCustomFields();
        }
        return $plugin;
    }
}
