<?php

namespace Ecomail;

use EcomailDeps\Wpify\Core\Abstracts\AbstractComponent;
use WP_Error;

class EcomailApi extends AbstractComponent {

	private $api_key = '';
	/**
	 * @var \EcomailDeps\Ecomail $api
	 */
	private $api;
	/**
	 * @var Settings
	 */
	private $settings;

	public function __construct( Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Initialize the API
	 */
	public function initialize() {
		static $initialized;
		if ( ! $initialized ) {
			$this->api_key = $this->settings->get_setting( 'api_key' );
			$this->api     = new \EcomailDeps\Ecomail( $this->api_key );
			$initialized   = true;
		}
	}

	/**
	 * Get Ecomail Lists
	 *
	 * @return WP_Error
	 */
	public function get_lists() {
		$this->initialize();

		return $this->handle_response( $this->api->getListsCollection() );
	}

	/**
	 * Add Subscriber
	 *
	 * @param       $list_id
	 * @param array   $data
	 *
	 * @return WP_Error
	 */
	public function add_subscriber( $list_id, array $data ) {
		$this->initialize();

		return $this->handle_response( $this->api->addSubscriber( $list_id, $data ) );
	}

	/**
	 * Add transaction
	 *
	 * @param array $data
	 *
	 * @return WP_Error
	 */
	public function add_transaction( array $data ) {
		$this->initialize();

		return $this->handle_response( $this->api->createNewTransaction( $data ) );
	}

	/**
	 * Update the cart
	 *
	 * @param $email
	 * @param $products
	 *
	 * @return WP_Error
	 */
	public function update_cart( $email, $products ) {
		$this->initialize();

		$value = array(
			'data' => array(
				'data' => array(
					'action'   => 'Basket',
					'products' => $products,
				),
			),
		);
		$data  = array(
			'email'    => $email,
			'category' => 'ue',
			'action'   => 'Basket',
			'label'    => 'Basket',
			'value'    => json_encode( $value ),
		);

		return $this->handle_response( $this->api->addEvent( array( 'event' => $data ) ) );
	}

	/**
	 * @return string
	 */
	public function get_api_key(): string {
		return $this->api_key;
	}

	/**
	 * Handle API response
	 *
	 * @param $response
	 *
	 * @return WP_Error
	 */
	public function handle_response( $response ) {
		if ( ! empty( $response['error'] ) ) {
			return new WP_Error( $response['error'], sprintf( 'Error code %s', $response['error'] ) );
		}

		return $response;
	}
}
