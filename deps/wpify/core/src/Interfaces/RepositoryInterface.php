<?php

namespace EcomailDeps\Wpify\Core\Interfaces;

use EcomailDeps\Doctrine\Common\Collections\ArrayCollection;
/**
 * @package Wpify\Core
 */
interface RepositoryInterface
{
    public function all() : \EcomailDeps\Doctrine\Common\Collections\ArrayCollection;
    public function get($id);
}
