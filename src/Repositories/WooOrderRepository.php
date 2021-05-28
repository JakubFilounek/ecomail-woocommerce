<?php

namespace Ecomail\Repositories;

use Ecomail\Plugin;
use Ecomail\PostTypes\WooOrderPostType;
use EcomailDeps\Wpify\Core\Abstracts\AbstractWooOrderRepository;

/**
 * @property Plugin $plugin
 */
class WooOrderRepository extends AbstractWooOrderRepository {

	public function post_type(): WooOrderPostType {
		return $this->plugin->get_post_type( WooOrderPostType::class );
	}
}
