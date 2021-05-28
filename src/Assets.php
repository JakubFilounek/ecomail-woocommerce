<?php

namespace Ecomail;

use EcomailDeps\Wpify\Core\Abstracts\AbstractAssets;

/**
 * @property Plugin $plugin
 */
class Assets extends AbstractAssets {

	/**
	 * Enqueue frontend assets
	 *
	 * @return array
	 */
	public function assets(): array {
		$js = $this->plugin->get_webpack_manifest()->get_assets(
			'plugin.js',
			'ecomail',
			array(
				'ecomailArgs' => array(
					'restUrl'             => $this->plugin->get_api_manager()->get_rest_url(),
					'cartTrackingEnabled' => boolval( $this->plugin->get_settings()->get_setting( 'woocommerce_cart_tracking' ) ),
					'emailExists'         => boolval( $this->plugin->get_ecomail()->get_customer_email() ),
				),
			),
			array( 'jquery' )
		);
		foreach ( $js as $key => $item ) {
			$js[ $key ]['in_footer'] = true;
		}

		return $js;
	}
}
