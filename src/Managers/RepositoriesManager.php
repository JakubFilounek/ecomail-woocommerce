<?php

namespace Ecomail\Managers;

use Ecomail\Plugin;
use Ecomail\Repositories\WooOrderRepository;
use EcomailDeps\Wpify\Core\Abstracts\AbstractManager;

/**
 * Class RepositoriesManager
 *
 * @package Wpify\Managers
 * @property Plugin $plugin
 */
class RepositoriesManager extends AbstractManager {
	protected $modules = array(
		WooOrderRepository::class,
	);
}
