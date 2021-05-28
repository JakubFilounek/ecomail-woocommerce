<?php

namespace Ecomail;

use EcomailDeps\Wpify\Core\Abstracts\AbstractComponent;

/**
 * Class Admin
 *
 * @package WpifyWoo
 * @property Plugin $plugin
 */
class Admin extends AbstractComponent {
	public function setup() {
		add_filter( 'plugin_action_links_ecomail/ecomail.php', array( $this, 'add_action_links' ) );
	}

	public function add_action_links( $links ) {
		$after = array(
			'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=ecomail' ), __( 'Settings', 'ecomail' ) ),
		);
		return array_merge( $links, $after );
	}
}
