<?php

namespace EcomailDeps\Wpify\Core\Interfaces;

/**
 * @package Wpify\Core
 */
interface FieldsStoreInterface
{
    /**
     * Get custom field value
     *
     * @param $id
     * @param $field
     *
     * @return mixed
     */
    public function get_field($id, $field);
    /**
     * Save custom field value
     *
     * @param $id
     * @param $field
     * @param $value
     *
     * @return mixed
     */
    public function save_field($id, $field, $value);
}
