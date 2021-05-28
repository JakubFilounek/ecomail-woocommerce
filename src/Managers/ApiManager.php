<?php

namespace Ecomail\Managers;

use Ecomail\Api\EcomailApi;
use Ecomail\Plugin;
use EcomailDeps\Wpify\Core\Abstracts\AbstractManager;

/**
 * Class ApiManager
 *
 * @package Ecomail\Managers
 * @property Plugin $plugin
 */
class ApiManager extends AbstractManager {

	public const REST_NAMESPACE = 'ecomail/v1';
	public const NONCE_ACTION   = 'wp_rest';

	protected $modules = array(
		EcomailApi::class,
	);

	public function get_rest_url() {
		return rest_url( $this->get_rest_namespace() );
	}

	public function get_rest_namespace() {
		return $this::REST_NAMESPACE;
	}

	public function get_nonce_action() {
		return $this::NONCE_ACTION;
	}

	public function setup() {
		add_action( 'init', array( $this, 'enable_wc_frontend_in_rest' ) );
	}

	public function enable_wc_frontend_in_rest() {
		if ( ! WC()->is_rest_api_request() ) {
			return;
		}

		WC()->frontend_includes();

		if ( null === WC()->cart && function_exists( 'wc_load_cart' ) ) {
			wc_load_cart();
		}

		WC()->session->set_customer_session_cookie( true );
	}

}
