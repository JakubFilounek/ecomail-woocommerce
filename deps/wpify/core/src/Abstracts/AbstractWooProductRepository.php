<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use EcomailDeps\Doctrine\Common\Collections\ArrayCollection;
use EcomailDeps\Wpify\Core\Exceptions\PluginException;
use EcomailDeps\Wpify\Core\Interfaces\PostTypeModelInterface;
use EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractWooProductRepository extends \EcomailDeps\Wpify\Core\Abstracts\AbstractPostTypeRepository implements \EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface
{
    /**
     * @param array $args
     *
     * @return ArrayCollection
     */
    public function all($args = array()) : \EcomailDeps\Doctrine\Common\Collections\ArrayCollection
    {
        $defaults = array('limit' => -1);
        $args = wp_parse_args($args, $defaults);
        return $this->find($args);
    }
    public function find($args = array())
    {
        $collection = new \EcomailDeps\Doctrine\Common\Collections\ArrayCollection();
        // The Loop
        foreach (wc_get_products($args) as $order) {
            $collection->add($this->get($order));
        }
        wp_reset_postdata();
        return $collection;
    }
    /**
     * @param $post
     *
     * @return AbstractPostTypeModel
     * @throws PluginException
     */
    public function get($post) : ?\EcomailDeps\Wpify\Core\Interfaces\PostTypeModelInterface
    {
        $model = $this->plugin->create_component($this->get_post_type()->model, array('product' => $post, 'post_type' => $this->get_post_type()));
        $model->init();
        return $model;
    }
}
