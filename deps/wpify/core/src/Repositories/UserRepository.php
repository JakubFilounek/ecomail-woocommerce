<?php

namespace EcomailDeps\Wpify\Core\Repositories;

use EcomailDeps\Doctrine\Common\Collections\ArrayCollection;
use EcomailDeps\Wpify\Core\Abstracts\AbstractComponent;
use EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface;
use EcomailDeps\Wpify\Core\Models\UserModel;
class UserRepository extends \EcomailDeps\Wpify\Core\Abstracts\AbstractComponent implements \EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface
{
    public function all() : \EcomailDeps\Doctrine\Common\Collections\ArrayCollection
    {
        $collection = new \EcomailDeps\Doctrine\Common\Collections\ArrayCollection();
        $users = get_users();
        foreach ($users as $user) {
            $collection->add($this->get($user));
        }
        return $collection;
    }
    public function get($user) : \EcomailDeps\Wpify\Core\Models\UserModel
    {
        $model = $this->plugin->create_component(\EcomailDeps\Wpify\Core\Models\UserModel::class, ['user' => $user]);
        $model->init();
        return $model;
    }
    public function get_current_user()
    {
        $user = wp_get_current_user();
        return $this->get($user);
    }
}
