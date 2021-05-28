<?php

namespace Ecomail\PostTypes;

use Ecomail\Models\WooOrderModel;
use Ecomail\Plugin;
use EcomailDeps\Wpify\Core\Abstracts\AbstractPostType;

/**
 * Class BookPostType
 *
 * @package WpifyPlugin\Cpt
 * @property Plugin $plugin
 */
class WooOrderPostType extends AbstractPostType {

	public const NAME       = 'shop_order';
	protected $register_cpt = false;

	public function post_type_args(): array {
		return array();
	}

	public function post_type_name(): string {
		return $this::NAME;
	}

	public function model(): string {
		return WooOrderModel::class;
	}
}
