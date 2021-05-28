<?php

namespace EcomailDeps\Wpify\Core\Stores;

use EcomailDeps\Wpify\Core\Abstracts\AbstractCustomFieldsStore;
class CustomFieldsStore extends \EcomailDeps\Wpify\Core\Abstracts\AbstractCustomFieldsStore
{
    /**
     * Get a single field value
     *
     * @param                       $id
     * @param                       $field
     *
     * @return mixed
     */
    public function get_field($id, $field)
    {
        if ('cpt' === $this->get_type()) {
            return get_post_meta($id, $field, \true);
        } elseif ('taxonomy' === $this->get_type()) {
            return get_term_meta($id, $field, \true);
        } else {
            throw new \Exception('Custom fields store is not configured yet for this type');
        }
    }
    /**
     * Save custom field value
     *
     * @param                       $id
     * @param                       $field
     * @param                       $value
     *
     * @return bool|int
     */
    public function save_field($id, $field, $value)
    {
        if ('cpt' === $this->get_type()) {
            return update_post_meta($id, $field, $value);
        } elseif ('taxonomy' === $this->get_type()) {
            return update_term_meta($id, $field, $value);
        } else {
            throw new \Exception('Custom fields store is not configured yet for this type');
        }
    }
}
