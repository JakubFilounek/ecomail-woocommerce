<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use EcomailDeps\Wpify\Core\Interfaces\FieldsStoreInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractCustomFieldsStore extends \EcomailDeps\Wpify\Core\Abstracts\AbstractComponent implements \EcomailDeps\Wpify\Core\Interfaces\FieldsStoreInterface
{
    /** @var string $type Type of the custom fields (cpt, taxonomy, etc.) */
    private $type;
    /** @var string $entity_name Name of the ctp, taxonomy, etc. */
    private $entity_name;
    /** @var array $custom_fields Array of the custom fields */
    private $custom_fields = array();
    /**
     * CustomFieldsFactory constructor.
     *
     * @param string $type
     * @param string $entity_name
     */
    public function __construct(string $type, string $entity_name)
    {
        $this->set_type($type);
        $this->set_entity_name($entity_name);
    }
    public function get_type() : string
    {
        return $this->type;
    }
    public function set_type(string $type) : void
    {
        $this->type = $type;
    }
    public function get_entity_name() : string
    {
        return $this->entity_name;
    }
    public function set_entity_name(string $entity_name) : void
    {
        $this->entity_name = $entity_name;
    }
}
