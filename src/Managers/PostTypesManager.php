<?php

namespace Ecomail\Managers;

use Ecomail\Plugin;
use Ecomail\PostTypes\BookPostType;
use Ecomail\PostTypes\WooOrderPostType;
use EcomailDeps\Wpify\Core\Abstracts\AbstractManager;

/**
 * Class CptManager
 *
 * @package Wpify\Managers
 * @property Plugin $plugin
 */
class PostTypesManager extends AbstractManager {
	protected $modules = array(
		WooOrderPostType::class,
	);
}
