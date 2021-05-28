<?php
// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

/*
 * Plugin Name:       Ecomail
 * Description:       Official Ecomail integration for WordPress and WooCommerce
 * Version:           2.0.0
 * Requires PHP:      7.3.0
 * Requires at least: 5.3.0
 * Author:            WPify
 * Author URI:        https://www.wpify.io/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ecomail
 * Domain Path: /languages
 * WC requires at least: 4.5
 * WC tested up to:      5.2
*/

use Ecomail\Plugin;
use EcomailDeps\DI;
use EcomailDeps\DI\Definition\Helper\AutowireDefinitionHelper;
use EcomailDeps\Wpify\Core\Container;
use EcomailDeps\Wpify\Core\WebpackManifest;

if ( ! defined( 'ECOMAIL_MIN_PHP_VERSION' ) ) {
	define( 'ECOMAIL_MIN_PHP_VERSION', '7.3.0' );
}

/**
 * Singleton instance function. We will not use a global at all as that defeats the purpose of a singleton
 * and is a bad design overall
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @return Ecomail\Plugin
 * @throws Exception
 */
function ecomail(): Plugin {
	return ecomail_container()->get( Plugin::class );
}

/**
 * This container singleton enables you to setup unit testing by passing an environment file to map classes in Dice
 *
 * @param string $env
 *
 * @return DI\Container
 * @throws Exception
 */
function ecomail_container(): EcomailDeps\DI\Container {
	static $container;
	if ( empty( $container ) ) {
		$wpify_container = Container::getInstance();
		$container       = $wpify_container->add_container(
				'ecomail',
				array(
						Plugin::class          => ( new AutowireDefinitionHelper( Plugin::class ) ),
						WebpackManifest::class => ( new AutowireDefinitionHelper() )
								->constructor( 'build/assets-manifest.json', 'ecomail~' ),
				)
		);
	}

	return $container;
}

/**
 * Init function shortcut
 */
function ecomail_init() {
	ecomail()->init();
}

/**
 * Activate function shortcut
 */
function ecomail_activate( $network_wide ) {
	register_uninstall_hook( __FILE__, 'ecomail_uninstall' );
	ecomail()->init();
	ecomail()->activate( $network_wide );
}

/**
 * Deactivate function shortcut
 */
function ecomail_deactivate( $network_wide ) {
	ecomail()->deactivate( $network_wide );
}

/**
 * Uninstall function shortcut
 */
function ecomail_uninstall() {
	ecomail()->uninstall();
}

/**
 * Error for older php
 */
function ecomail_php_upgrade_notice() {
	$info = get_plugin_data( __FILE__ );
	_e(
			sprintf(
					'
      <div class="error notice">
        <p>
          Opps! %s requires a minimum PHP version of ' . ECOMAIL_MIN_PHP_VERSION . '. Your current version is: %s.
          Please contact your host to upgrade.
        </p>
      </div>
      ',
					$info['Name'],
					PHP_VERSION
			)
	);
}

/**
 * Error if vendors autoload is missing
 */
function ecomail_php_vendor_missing() {
	$info = get_plugin_data( __FILE__ );
	_e(
			sprintf(
					'
      <div class="error notice">
        <p>Opps! %s is corrupted it seems, please re-install the plugin.</p>
      </div>
      ',
					$info['Name']
			)
	);
}

/**
 * Load plugin textdomain.
 */
function ecomail_load_textdomain() {
	load_plugin_textdomain( 'ecomail', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * WooCommerce not active notice
 */
function ecomail_woocommerce_not_active() {
	?>
	<div class="error notice">
		<p><?php
			_e( 'This plugin requires WooCommerce. Please install and activate it first.', 'ecomail' ); ?></p>
	</div>
	<?php
}


/*
 * We want to use a fairly modern php version, feel free to increase the minimum requirement
 */
if ( version_compare( PHP_VERSION, ECOMAIL_MIN_PHP_VERSION ) < 0 ) {
	add_action( 'admin_notices', 'ecomail_php_upgrade_notice' );
} elseif ( ! in_array( 'woocommerce/woocommerce.php', (array) get_option( 'active_plugins', array() ), true ) ) {
	add_action( 'admin_notices', 'ecomail_woocommerce_not_active' );
} else {
	if ( file_exists( __DIR__ . '/deps/scoper-autoload.php' ) ) {
		ecomail_load_textdomain();
		include_once __DIR__ . '/deps/scoper-autoload.php';
		include_once __DIR__ . '/vendor/autoload.php';

		add_action( 'plugins_loaded', 'ecomail_init', 11 );
		register_activation_hook( __FILE__, 'ecomail_activate' );
		register_deactivation_hook( __FILE__, 'ecomail_deactivate' );
	} else {
		add_action( 'admin_notices', 'ecomail_php_vendor_missing' );
	}
}
