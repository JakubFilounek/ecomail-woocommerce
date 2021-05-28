<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use EcomailDeps\Doctrine\Common\Collections\ArrayCollection;
use Exception;
use WP_Error;
use EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractTaxonomyRepository extends \EcomailDeps\Wpify\Core\Abstracts\AbstractComponent implements \EcomailDeps\Wpify\Core\Interfaces\RepositoryInterface
{
    /** @var AbstractTaxonomy */
    private $taxonomy;
    public function init()
    {
        $this->taxonomy = $this->taxonomy();
        parent::init();
    }
    public abstract function taxonomy();
    /**
     * @return AbstractTaxonomy
     */
    public function get_taxonomy()
    {
        return $this->taxonomy;
    }
    /**
     * @param AbstractTaxonomy $taxonomy
     */
    public function set_taxonomy(\EcomailDeps\Wpify\Core\Abstracts\AbstractTaxonomy $taxonomy) : void
    {
        $this->taxonomy = $taxonomy;
    }
    /**
     * @return string
     */
    public function get_model()
    {
        return $this->taxonomy->get_model();
    }
    /**
     * @return ArrayCollection&AbstractTermModel[]
     */
    public function all() : \EcomailDeps\Doctrine\Common\Collections\ArrayCollection
    {
        $args = array('hide_empty' => \false);
        return $this->find($args);
    }
    /**
     * Find terms
     *
     * @param array $args
     *
     * @return ArrayCollection
     */
    public function find($args = array())
    {
        $defaults = array('taxonomy' => $this->taxonomy->get_name());
        $args = wp_parse_args($args, $defaults);
        $collection = new \EcomailDeps\Doctrine\Common\Collections\ArrayCollection();
        $terms = get_terms($args);
        foreach ($terms as $term) {
            $collection->add($this->get($term));
        }
        return $collection;
    }
    public function get($term) : \EcomailDeps\Wpify\Core\Abstracts\AbstractTermModel
    {
        $model = $this->plugin->create_component($this->taxonomy->model, ['term' => $term, 'taxonomy' => $this->taxonomy]);
        $model->init();
        return $model;
    }
    /**
     * @param AbstractTermModel $term
     *
     * @return WP_Error|AbstractTermModel
     * @throws Exception
     */
    public function save(\EcomailDeps\Wpify\Core\Abstracts\AbstractTermModel $term)
    {
        // Create a new term
        if (!$term->get_id()) {
            $item = wp_insert_term($term->get_name(), $this->get_taxonomy()->get_name(), array('slug' => $term->get_slug(), 'description' => $term->get_description()));
            if (is_wp_error($item) && $item->get_error_code() !== 'term_exists') {
                return $item;
            } elseif (is_wp_error($item) && $item->get_error_code() === 'term_exists') {
                $term_id = $item->get_error_data('term_exists');
            } else {
                $term_id = $item['term_id'];
            }
            $term->set_id($term_id);
        }
        foreach ($term->get_taxonomy()->get_fields_autosave() as $field_id) {
            $getter = \sprintf('get_%s', \ltrim($field_id, '_'));
            if (!\method_exists($term, $getter)) {
                continue;
            }
            $term->save_field($field_id, $term->{$getter}());
        }
        return $term;
    }
}
