<?php

namespace EcomailDeps\WpifyCustomFields;

use EcomailDeps\WpifyCustomFields\Implementations\GutenbergBlock;
use EcomailDeps\WpifyCustomFields\Implementations\Metabox;
use EcomailDeps\WpifyCustomFields\Implementations\Options;
use EcomailDeps\WpifyCustomFields\Implementations\ProductOptions;
use EcomailDeps\WpifyCustomFields\Implementations\Taxonomy;
use EcomailDeps\WpifyCustomFields\Implementations\WooCommerceSettings;
/**
 * Class WpifyCustomFields
 * @package WpifyCustomFields
 */
final class WpifyCustomFields
{
    /** @var Assets */
    private $assets;
    /** @var Sanitizer */
    private $sanitizer;
    /** @var Parser */
    private $parser;
    /** @var Api */
    private $api;
    /**
     * WpifyCustomFields constructor.
     */
    public function __construct(string $wcf_url = '')
    {
        $assets_path = \realpath(__DIR__ . '/../build');
        $this->assets = new \EcomailDeps\WpifyCustomFields\Assets($assets_path, $wcf_url);
        $this->sanitizer = new \EcomailDeps\WpifyCustomFields\Sanitizer();
        $this->parser = new \EcomailDeps\WpifyCustomFields\Parser();
        $this->api = new \EcomailDeps\WpifyCustomFields\Api();
    }
    /**
     * @param array $args
     *
     * @return Options
     */
    public function add_options_page($args = array())
    {
        return new \EcomailDeps\WpifyCustomFields\Implementations\Options($args, $this);
    }
    /**
     * @param array $args
     *
     * @return Metabox
     */
    public function add_metabox($args = array())
    {
        return new \EcomailDeps\WpifyCustomFields\Implementations\Metabox($args, $this);
    }
    /**
     * @param array $args
     *
     * @return ProductOptions
     */
    public function add_product_options($args = array())
    {
        return new \EcomailDeps\WpifyCustomFields\Implementations\ProductOptions($args, $this);
    }
    /**
     * @param array $args
     *
     * @return Taxonomy
     */
    public function add_taxonomy_options($args = array())
    {
        return new \EcomailDeps\WpifyCustomFields\Implementations\Taxonomy($args, $this);
    }
    /**
     * @param array $args
     *
     * @return WooCommerceSettings
     */
    public function add_woocommerce_settings($args = array())
    {
        return new \EcomailDeps\WpifyCustomFields\Implementations\WooCommerceSettings($args, $this);
    }
    public function add_gutenberg_block($args = array())
    {
        return new \EcomailDeps\WpifyCustomFields\Implementations\GutenbergBlock($args, $this);
    }
    /**
     * @return Parser
     */
    public function get_parser() : \EcomailDeps\WpifyCustomFields\Parser
    {
        return $this->parser;
    }
    /**
     * @return Sanitizer
     */
    public function get_sanitizer() : \EcomailDeps\WpifyCustomFields\Sanitizer
    {
        return $this->sanitizer;
    }
    /**
     * @return Api
     */
    public function get_api() : \EcomailDeps\WpifyCustomFields\Api
    {
        return $this->api;
    }
    /**
     * @return Assets
     */
    public function get_assets() : \EcomailDeps\WpifyCustomFields\Assets
    {
        return $this->assets;
    }
}
