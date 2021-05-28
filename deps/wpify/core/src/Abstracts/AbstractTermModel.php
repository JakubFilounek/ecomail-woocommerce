<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use Exception;
use WP_Error;
use WP_Term;
use EcomailDeps\Wpify\Core\Interfaces\CustomFieldsFactoryInterface;
use EcomailDeps\Wpify\Core\Interfaces\FieldsStoreInterface;
use EcomailDeps\Wpify\Core\Interfaces\TermModelInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractTermModel extends \EcomailDeps\Wpify\Core\Abstracts\AbstractComponent implements \EcomailDeps\Wpify\Core\Interfaces\TermModelInterface
{
    /**
     * Disable auto init by default
     * @var bool
     */
    protected $auto_init = \false;
    private $term;
    /** @var AbstractTaxonomy */
    private $taxonomy;
    private $id;
    private $name;
    private $description;
    /**
     * @param int    $term
     * @param string $taxonomy
     * @param null   $filter
     */
    public function __construct($term, $taxonomy, $filter = null)
    {
        $this->taxonomy = $taxonomy;
        if (!empty($term) && !empty($taxonomy)) {
            $this->term = get_term($term, $taxonomy->get_name(), null, $filter);
        }
    }
    public function setup()
    {
        foreach ($this->get_taxonomy()->get_fields_autoload() as $field_id) {
            $setter = \sprintf('set_%s', \ltrim($field_id, '_'));
            if (!\method_exists($this, $setter)) {
                continue;
            }
            $this->{$setter}($this->get_field($field_id));
        }
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
     * Save custom field value
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
     * @return FieldsStoreInterface|false
     */
    private function get_fields_store()
    {
        return $this->get_taxonomy()->get_fields_store();
    }
    /**
     * Get single term
     * @return array|WP_Error|WP_Term|null
     */
    public function get_term()
    {
        return $this->term;
    }
    /**
     * Get term name
     * @return string|null
     */
    public function get_name()
    {
        if ($this->name) {
            return $this->name;
        }
        return $this->term->name ?? null;
    }
    /**
     * Get term slug
     * @return string|null
     */
    public function get_slug()
    {
        return $this->term->slug ?? null;
    }
    /**
     * Get term ID
     * @return int|null
     */
    public function get_id()
    {
        if ($this->id) {
            return $this->id;
        }
        return $this->term->term_id ?? null;
    }
    /**
     * @param mixed $name
     */
    public function set_name(string $name) : void
    {
        $this->name = $name;
    }
    /**
     * @param mixed $id
     */
    public function set_id($id) : void
    {
        $this->id = $id;
    }
    /**
     * @return array
     */
    public function get_fields_autoload() : array
    {
        return $this->fields_autoload;
    }
    /**
     * @return array
     */
    public function get_fields_autosave() : array
    {
        return $this->fields_autosave;
    }
    /**
     * @return AbstractTaxonomy
     */
    public function get_taxonomy()
    {
        return $this->taxonomy;
    }
    /**
     * @return mixed
     */
    public function get_description()
    {
        if ($this->description) {
            return $this->description;
        }
        $this->description = $this->term->description ?? null;
        return $this->description;
    }
}
