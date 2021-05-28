<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use EcomailDeps\Doctrine\Common\Collections\ArrayCollection;
use EcomailDeps\Wpify\Core\Exceptions\PluginException;
use EcomailDeps\Wpify\Core\Interfaces\PostTypeModelInterface;
use EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractWooOrderRepository extends \EcomailDeps\Wpify\Core\Abstracts\AbstractComponent implements \EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface
{
    /** @var AbstractPostType */
    private $post_type;
    public function init()
    {
        $this->post_type = $this->post_type();
        parent::init();
    }
    public abstract function post_type();
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
        foreach (wc_get_orders($args) as $order) {
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
        $model = $this->plugin->create_component($this->post_type->model, array('order' => $post, 'post_type' => $this->post_type));
        $model->init();
        return $model;
    }
    /**
     * @return AbstractPostType
     */
    public function get_post_type()
    {
        return $this->post_type;
    }
    /**
     * @param AbstractPostType $post_type
     */
    public function set_post_type(\EcomailDeps\Wpify\Core\Abstracts\AbstractPostType $post_type) : void
    {
        $this->post_type = $post_type;
    }
    /**
     * @return string
     */
    public function get_model()
    {
        return $this->post_type->get_model();
    }
}
