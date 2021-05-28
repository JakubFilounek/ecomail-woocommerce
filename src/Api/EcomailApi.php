<?php

namespace Ecomail\Api;

use WP_REST_Server;
use Ecomail\Plugin;
use EcomailDeps\Wpify\Core\Abstracts\AbstractRest;

/**
 * @property Plugin $plugin
 */
class EcomailApi extends AbstractRest {

	/**
	 * ExampleApi constructor.
	 */
	public function __construct() {
	}

	public function setup() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			$this->plugin->get_api_manager()->get_rest_namespace(),
			'cart',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'track_cart' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	/**
	 * Add box to cart
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Request|\WP_REST_Response | bool
	 */
	public function track_cart( $request ) {
		$woo   = $this->plugin->get_woocommerce();
		$items = $request->get_param( 'items' );
		if ( ! $items ) {
			$items = $woo->get_cart_items();
		}

		$email = $request->get_param( 'email' );
		if ( ! $email ) {
			$email = $this->plugin->get_ecomail()->get_customer_email();
		}

		$result = $this->plugin->get_woocommerce()->track_cart( $items, $email );

		if ( ! is_wp_error( $result ) ) {
			$this->plugin->get_woocommerce()->delete_update_cart_flag();
		}

		return rest_ensure_response( $result );
	}

	/**
	 * Check if a given request has access to create items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		return true;
	}


	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed            $item WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		return array();
	}
}
