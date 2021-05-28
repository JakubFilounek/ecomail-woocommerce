<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use EcomailDeps\Doctrine\Common\Collections\ArrayCollection;
use Exception;
use WP_Query;
use EcomailDeps\Wpify\Core\Exceptions\PluginException;
use EcomailDeps\Wpify\Core\Interfaces\PostTypeModelInterface;
use EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractPostTypeRepository extends \EcomailDeps\Wpify\Core\Abstracts\AbstractComponent implements \EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface
{
    /** @var AbstractPostType */
    private $post_type;
    private $query;
    public function init()
    {
        $this->post_type = $this->post_type();
        parent::init();
    }
    public abstract function post_type();
    /**
     * @param  array  $args
     *
     * @return ArrayCollection
     */
    public function all($args = array()) : \EcomailDeps\Doctrine\Common\Collections\ArrayCollection
    {
        $defaults = array('posts_per_page' => -1);
        $args = wp_parse_args($args, $defaults);
        return $this->find($args);
    }
    public function find($args = array())
    {
        $defaults = array('post_type' => $this->post_type->get_name());
        $args = wp_parse_args($args, $defaults);
        $collection = new \EcomailDeps\Doctrine\Common\Collections\ArrayCollection();
        $this->query = new \WP_Query($args);
        // The Loop
        while ($this->query->have_posts()) {
            $this->query->the_post();
            global $post;
            $collection->add($this->get($post));
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
        $model = $this->plugin->create_component($this->post_type->model, ['post' => $post, 'post_type' => $this->post_type]);
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
     * @param  AbstractPostType  $post_type
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
    public function get_paginate_links($args = array())
    {
        $pagination = $this->get_pagination();
        $default_args = array('total' => $pagination['total_pages'], 'current' => $pagination['current_page']);
        $args = wp_parse_args($args, $default_args);
        return paginate_links($args);
    }
    public function get_pagination()
    {
        return array('found_posts' => $this->get_query()->found_posts, 'current_page' => $this->get_query()->query_vars['paged'] ?: 1, 'total_pages' => $this->get_query()->max_num_pages, 'per_page' => $this->get_query()->query_vars['posts_per_page']);
    }
    /**
     * @return mixed
     */
    public function get_query()
    {
        return $this->query;
    }
    /**
     * @param  AbstractPostTypeModel  $model
     *
     * @return AbstractPostTypeModel
     * @throws Exception
     */
    public function save(\EcomailDeps\Wpify\Core\Abstracts\AbstractPostTypeModel $model) : \EcomailDeps\Wpify\Core\Abstracts\AbstractPostTypeModel
    {
        if (!$model->get_id()) {
            $args = ['post_title' => $model->get_title(), 'post_content' => $model->get_content(), 'post_status' => $model->get_status() ?: 'publish', 'post_type' => $model->get_post_type()->get_name()];
            $args = apply_filters('wpify_core_post_args', $args, $model);
            $id = wp_insert_post($args);
            if (is_wp_error($id)) {
                throw new \Exception($id->get_error_message());
            }
            if ($id && !is_wp_error($id)) {
                $model->set_id($id);
            }
        } else {
            $args = ['ID' => $model->get_id(), 'post_title' => $model->get_title(), 'post_content' => $model->get_content(), 'post_status' => $model->get_status()];
            wp_update_post($args);
        }
        foreach ($model->get_post_type()->get_fields_autosave() as $field_id) {
            $getter = \sprintf('get_%s', \ltrim($field_id, '_'));
            if (!\method_exists($model, $getter)) {
                continue;
            }
            $model->save_field($field_id, $model->{$getter}());
        }
        return $model;
    }
}
