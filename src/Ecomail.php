<?php

namespace Ecomail;

use EcomailDeps\Wpify\Core\Abstracts\AbstractComponent;

/**
 * Class Ecomail
 *
 * @package Ecomail
 * @property Plugin $plugin
 */
class Ecomail extends AbstractComponent {

	/**
	 * @var EcomailApi
	 */
	private $ecomail_api;
	/**
	 * @var WooCommerce
	 */
	private $woocommerce;
	/**
	 * @var Settings
	 */
	private $settings;

	const COOKIE_NAME = 'ecm_email';

	public function __construct( EcomailApi $ecomail_api, Settings $settings ) {
		$this->ecomail_api = $ecomail_api;
		$this->settings    = $settings;
	}

	public function setup() {
		add_action( 'template_redirect', array( $this, 'maybe_save_email_cookie' ) );
		add_action( 'wp_head', array( $this, 'tracking_code' ) );
		add_action( 'admin_action_ecomail_refresh_lists', array( $this, 'refresh_lists' ) );
	}

	public function tracking_code() {
		$app_id = $this->settings->get_setting( 'app_id' );
		$enable = $this->settings->get_setting( 'enable_tracking_code' );
		if ( ! $app_id || ! $enable ) {
			return;
		}
		?>
		<!-- Ecomail starts growing -->
		<script type="text/javascript">
			;(function (p, l, o, w, i, n, g) {
				if (!p[i]) {
					p.GlobalSnowplowNamespace = p.GlobalSnowplowNamespace || [];
					p.GlobalSnowplowNamespace.push(i);
					p[i] = function () {
						(p[i].q = p[i].q || []).push(arguments)
					};
					p[i].q = p[i].q || [];
					n = l.createElement(o);
					g = l.getElementsByTagName(o)[0];
					n.async = 1;
					n.src = w;
					g.parentNode.insertBefore(n, g)
				}
			}(window, document, "script", "//d1fc8wv8zag5ca.cloudfront.net/2.4.2/sp.js", "ecotrack"));
			window.ecotrack('newTracker', 'cf', 'd2dpiwfhf3tz0r.cloudfront.net', { // Initialise a tracker
				appId: '<?php echo esc_attr( $app_id ); ?>'
			});
			window.ecotrack('setUserIdFromLocation', 'ecmid');
			<?php
			$this->manual_tracking();
			?>

			window.ecotrack('trackPageView');

		</script>
		<!-- Ecomail stops growing -->
		<?php
	}

	public function manual_tracking() {
		if ( ! $this->settings->get_setting( 'enable_manual_tracking' ) ) {
			return;
		}

		$email = $this->get_customer_email();
		if ( ! $email ) {
			return;
		}
		printf( "window.ecotrack('setUserId', '%s')", esc_attr( $email ) );
	}

	/**
	 * Get customer email
	 *
	 * @return string|null
	 */
	public function get_customer_email(): ?string {
		if ( $this->get_email_cookie() ) {
			return $this->get_email_cookie();
		}

		if ( ! empty( WC()->customer ) && ! empty( WC()->customer->get_billing_email() ) ) {
			return WC()->customer->get_billing_email();
		}
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();

			return $user->user_email;
		}

		return null;
	}


	public function get_lists() {
		return get_option( 'ecomail_lists', array() );
	}

	public function refresh_lists() {
		$this->save_lists();
		wp_safe_redirect( $this->settings->get_settings_url() );
		exit();
	}

	public function save_lists() {
		$lists = $this->ecomail_api->get_lists();
		if ( ! is_wp_error( $lists ) ) {
			update_option( 'ecomail_lists', $lists );
		}

		return $lists;
	}

	public function maybe_save_email_cookie() {
		if ( isset( $_GET['ecmid'] ) ) {
			$this->save_email_cookie( sanitize_text_field( $_GET['ecmid'] ) );
		}
	}

	public function save_email_cookie( $email ) {
		setcookie( $this::COOKIE_NAME, $email, time() + ( 86400 * 30 ), '/' ); // 86400 = 1 day
	}

	public function get_email_cookie() {
		return $_COOKIE[ $this::COOKIE_NAME ] ?? '';
	}
}
