<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use EcomailDeps\ComposePress\Core\Exception\Plugin;
use Exception;
use ReflectionException;
use WP_Post;
use EcomailDeps\Wpify\Core\Interfaces\CustomFieldsFactoryInterface;
use EcomailDeps\Wpify\Core\Interfaces\FieldsStoreInterface;
use EcomailDeps\Wpify\Core\Interfaces\PostTypeModelInterface;
use EcomailDeps\Wpify\Core\Models\AttachmentImageModel;
use EcomailDeps\Wpify\Core\Repositories\AttachmentImageRepository;
/**
 * @package Wpify\Core
 */
abstract class AbstractPostTypeModel extends \EcomailDeps\Wpify\Core\Abstracts\AbstractComponent implements \EcomailDeps\Wpify\Core\Interfaces\PostTypeModelInterface
{
    /**
     * Disable auto init by default
     * @var bool
     */
    protected $auto_init = \false;
    /**
     * @var int
     */
    private $id;
    /** @var WP_Post */
    private $post;
    /**
     * @var AbstractPostType $post_type
     */
    private $post_type;
    private $title;
    private $content;
    private $thumbnail;
    private $status;
    /**
     * @return mixed
     */
    public function __construct($post, $post_type, $filter = null)
    {
        $this->post_type = $post_type;
        if ($post) {
            $this->post = get_post($post, null, $filter);
        }
        if ($this->post) {
            $this->id = $this->post->ID;
            $this->title = $this->post->post_title;
            $this->content = $this->post->post_content;
            $this->status = $this->post->post_status;
        }
    }
    public function setup()
    {
        foreach ($this->get_post_type()->get_fields_autoload() as $field_id) {
            $setter = \sprintf('set_%s', \ltrim($field_id, '_'));
            if (!\method_exists($this, $setter)) {
                continue;
            }
            $value = $this->get_field($field_id);
            if ($value) {
                $this->{$setter}($this->get_field($field_id));
            }
        }
    }
    public function get_post()
    {
        return $this->post;
    }
    /**
     * @param WP_Post $post
     */
    public function set_post(\WP_Post $post) : void
    {
        $this->post = $post;
    }
    /**
     * Get custom field value
     *
     * @param $field
     *
     * @return mixed
     * @throws Exception
     */
    public function get_field($field)
    {
        $factory = $this->get_fields_store();
        if (!$factory) {
            throw new \Exception(__('You need to set custom fields store to register and retrieve custom fields', 'wpify'));
        }
        return $factory->get_field($this->get_id(), $field);
    }
    /**
     * @return FieldsStoreInterface|false
     */
    private function get_fields_store()
    {
        return $this->post_type->get_fields_store();
    }
    /**
     * @return int|null
     */
    public function get_id()
    {
        return $this->post->ID ?? $this->id;
    }
    /**
     * Get custom field value
     *
     * @param $field
     * @param $value
     *
     * @return mixed
     * @throws Exception
     */
    public function save_field($field, $value)
    {
        $factory = $this->get_fields_store();
        if (!$factory) {
            throw new \Exception(__('You need to set custom fields store to register and save custom fields', 'wpify'));
        }
        return $factory->save_field($this->get_id(), $field, $value);
    }
    /**
     * Get Post type for the current model
     * @return AbstractPostType
     */
    public function get_post_type() : \EcomailDeps\Wpify\Core\Abstracts\AbstractPostType
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
     * @return mixed
     */
    public function get_title()
    {
        return $this->title;
    }
    /**
     * @param mixed $title
     */
    public function set_title($title) : void
    {
        $this->title = $title;
    }
    /**
     * @return mixed
     */
    public function get_content()
    {
        return $this->content;
    }
    /**
     * @param mixed $content
     */
    public function set_content($content) : void
    {
        $this->content = $content;
    }
    /**
     * @return null|AttachmentImageModel
     * @throws Plugin
     * @throws ReflectionException
     */
    public function get_thumbnail()
    {
        if ($this->thumbnail) {
            return $this->thumbnail;
        }
        $thumbnail_id = get_post_thumbnail_id($this->get_id());
        if (!$thumbnail_id) {
            return null;
        }
        /** @var AttachmentImageRepository $repository */
        $repository = $this->plugin->create_component(\EcomailDeps\Wpify\Core\Repositories\AttachmentImageRepository::class);
        $repository->init();
        $this->thumbnail = $repository->get(get_post_thumbnail_id($this->get_id()));
        return $this->thumbnail;
    }
    /**
     * @param int $id
     */
    public function set_id(int $id) : void
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function get_status()
    {
        return $this->status;
    }
    /**
     * @param mixed $status
     */
    public function set_status($status) : void
    {
        $this->status = $status;
    }
}
