<?php

namespace Ecomail;

use Ecomail\Managers\ApiManager;
use Ecomail\Managers\PostTypesManager;
use Ecomail\Managers\RepositoriesManager;
use EcomailDeps\Wpify\Core\Abstracts\AbstractPlugin;
use EcomailDeps\Wpify\Core\Exceptions\ContainerInvalidException;
use EcomailDeps\Wpify\Core\Exceptions\ContainerNotExistsException;
use EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface;
use EcomailDeps\Wpify\Core\WebpackManifest;
use EcomailDeps\Wpify\Core\WordPressTemplate;
use EcomailDeps\WpifyCustomFields\WpifyCustomFields;
use Exception;

/**
 * Class Plugin
 *
 * @package Wpify
 */
class Plugin extends AbstractPlugin {
	/** Plugin version */
	public const VERSION = '2.0.0';

	/** Plugin slug name */
	public const PLUGIN_SLUG = 'ecomail';

	/** Plugin namespace */
	public const PLUGIN_NAMESPACE = '\\' . __NAMESPACE__;

	/** @var PostTypesManager */
	private $post_types_manager;

	/** @var RepositoriesManager */
	private $repositories_manager;

	/** @var ApiManager */
	private $api_manager;

	/** @var Settings */
	private $settings;

	/** @var Assets */
	private $assets;

	/** @var WebpackManifest */
	private $webpack_manifest;

	/** @var WordPressTemplate */
	private $template;
	/**
	 * @var Ecomail
	 */
	private $ecomail;

	/**
	 * @var EcomailApi
	 */
	private $ecomail_api;
	/**
	 * @var WooCommerce
	 */
	private $woocommerce;
	/**
	 * @var WpifyCustomFields
	 */
	private $custom_fields;

	/**
	 * @var Admin
	 */
	private $admin;

	/**
	 * Plugin constructor.
	 *
	 * @param RepositoriesManager $repositories_manager
	 * @param ApiManager $api_manager
	 * @param Settings $settings
	 * @param PostTypesManager $post_types_manager
	 * @param Assets $assets
	 * @param WebpackManifest $webpack_manifest
	 * @param WordPressTemplate $template
	 * @param Ecomail $ecomail
	 * @param EcomailApi $ecomail_api
	 * @param WooCommerce $woocommerce
	 * @param WpifyCustomFields $custom_fields
	 *
	 * @throws ContainerInvalidException
	 * @throws ContainerNotExistsException
	 */
	public function __construct(
		RepositoriesManager $repositories_manager,
		ApiManager $api_manager,
		Settings $settings,
		PostTypesManager $post_types_manager,
		Assets $assets,
		WebpackManifest $webpack_manifest,
		WordPressTemplate $template,
		Ecomail $ecomail,
		EcomailApi $ecomail_api,
		WooCommerce $woocommerce,
		WpifyCustomFields $custom_fields,
		Admin $admin
	) {
		$this->post_types_manager   = $post_types_manager;
		$this->repositories_manager = $repositories_manager;
		$this->api_manager          = $api_manager;
		$this->settings             = $settings;
		$this->assets               = $assets;
		$this->webpack_manifest     = $webpack_manifest;
		$this->template             = $template;
		$this->ecomail_api          = $ecomail_api;
		$this->ecomail              = $ecomail;
		$this->woocommerce          = $woocommerce;
		$this->custom_fields        = $custom_fields;
		$this->admin                = $admin;
		parent::__construct();
	}

	public function get_repositories_manager(): RepositoriesManager {
		return $this->repositories_manager;
	}

	/**
	 * @param string $class
	 *
	 * @return RepositoryInterface
	 */
	public function get_repository( string $class ) {
		return $this->repositories_manager->get_module( $class );
	}

	public function get_api_manager(): ApiManager {
		return $this->api_manager;
	}

	public function get_api( string $class ) {
		return $this->api_manager->get_module( $class );
	}

	public function get_settings(): Settings {
		return $this->settings;
	}

	public function get_post_types_manager(): PostTypesManager {
		return $this->post_types_manager;
	}

	public function get_post_type( string $class ) {
		return $this->post_types_manager->get_module( $class );
	}


	public function get_assets(): Assets {
		return $this->assets;
	}

	/** @return WebpackManifest */
	public function get_webpack_manifest(): WebpackManifest {
		return $this->webpack_manifest;
	}

	/**
	 * Print styles in theme
	 *
	 * @param $handles
	 */
	public function print_assets( string ...$handles ) {
		$this->assets->print_assets( $handles );
	}

	/**
	 * Plugin activation and upgrade
	 *
	 * @param $network_wide
	 *
	 * @return void
	 */
	public function activate( $network_wide ) {
	}

	/**
	 * Plugin de-activation
	 *
	 * @param $network_wide
	 *
	 * @return void
	 */
	public function deactivate( $network_wide ) {
	}

	/**
	 * Plugin uninstall
	 *
	 * @return void
	 */
	public function uninstall() {
	}

	/**
	 * @return WordPressTemplate
	 */
	public function get_template(): WordPressTemplate {
		return $this->template;
	}

	/**
	 * @return EcomailApi
	 */
	public function get_ecomail_api(): EcomailApi {
		return $this->ecomail_api;
	}

	/**
	 * @return WooCommerce
	 */
	public function get_woocommerce(): WooCommerce {
		return $this->woocommerce;
	}

	/**
	 * @return WpifyCustomFields
	 */
	public function get_custom_fields(): WpifyCustomFields {
		return $this->custom_fields;
	}

	/**
	 * @return WpifyCustomFields
	 */
	public function get_admin(): Admin {
		return $this->admin;
	}

	/**
	 * @return Ecomail
	 */
	public function get_ecomail(): Ecomail {
		return $this->ecomail;
	}

	/**
	 * Method to check if plugin has its dependencies. If not, it silently aborts
	 *
	 * @return bool
	 */
	protected function get_dependencies_exist() {
		return true;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	protected function load_components() {
		// Conditionally lazy load components with $this->load()
		return true;
	}
}
