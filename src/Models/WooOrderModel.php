<?php

namespace Ecomail\Models;

use EcomailDeps\Wpify\Core\Abstracts\AbstractWooOrderModel;

class WooOrderModel extends AbstractWooOrderModel {

	private $ic;
	private $dic;

	/**
	 * @return mixed
	 */
	public function get_ic() {
		if ( $this->ic ) {
			return $this->ic;
		}
		$this->ic = $this->get_wc_order()->get_meta( '_billing_ic' );

		return $this->ic;
	}

	/**
	 * @return mixed
	 */
	public function get_dic() {
		if ( $this->dic ) {
			return $this->dic;
		}

		$this->dic = $this->get_wc_order()->get_meta( '_billing_dic' );

		return $this->dic;
	}

	/**
	 * @return array
	 */
	public function get_subscriber_data(): array {
		$wc_order = $this->get_wc_order();
		$data     = array(
			'email' => $wc_order->get_billing_email(),
		);

		$settings = $this->plugin->get_settings();
		$fields   = $settings->get_setting( 'woocommerce_checkout_subscribe_fields' );

		if ( in_array( 'first_name', $fields ) ) {
			$data['name'] = $wc_order->get_billing_first_name();
		}
		if ( in_array( 'last_name', $fields ) ) {
			$data['surname'] = $wc_order->get_billing_last_name();
		}
		if ( in_array( 'company', $fields ) ) {
			$data['company'] = $wc_order->get_billing_company();
		}
		if ( in_array( 'city', $fields ) ) {
			$data['city'] = $wc_order->get_billing_city();
		}
		if ( in_array( 'street', $fields ) ) {
			$data['street'] = $wc_order->get_billing_address_1();
		}
		if ( in_array( 'postcode', $fields ) ) {
			$data['zip'] = $wc_order->get_billing_postcode();
		}
		if ( in_array( 'country', $fields ) ) {
			$data['country'] = $wc_order->get_billing_country();
		}
		if ( in_array( 'phone', $fields ) ) {
			$data['phone'] = $wc_order->get_billing_phone();
		}

		if ( $settings->get_setting( 'api_source' ) ) {
			$data['source'] = $settings->get_setting( 'api_source' );
		}

		return apply_filters( 'ecomail_order_subscriber_data', $data, $this, $wc_order );
	}

	public function get_transaction_data() {
		$wc_order = $this->get_wc_order();
		$data     = array(
			'transaction' => array(
				'order_id'  => $this->get_id(),
				'email'     => $wc_order->get_billing_email(),
				'shop'      => site_url(),
				'amount'    => $wc_order->get_total() - $wc_order->get_total_tax(),
				'tax'       => $wc_order->get_total_tax(),
				'shipping'  => $wc_order->get_shipping_total(),
				'city'      => $wc_order->get_billing_city(),
				'country'   => $wc_order->get_billing_country(),
				'timestamp' => $wc_order->get_date_created()->getTimestamp(),
			),
		);

		foreach ( $this->get_line_items() as $item ) {
			$category = '';
			foreach ( wp_get_post_terms( get_the_id(), 'product_cat' ) as $term ) {
				$category = $term->name;
				break;
			}
			$data['transaction_items'][] = array(
				'code'     => $item->get_sku() ?? $item->get_product_id(),
				'title'    => $item->get_name(),
				'category' => $category,
				'price'    => $item->get_unit_price_tax_included(),
				'amount'   => $item->get_quantity(),
			);
		}

		return apply_filters( 'ecomail_order_transaction_data', $data, $this, $wc_order );
	}
}
