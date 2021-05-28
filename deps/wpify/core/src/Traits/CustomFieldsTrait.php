<?php

namespace EcomailDeps\Wpify\Core\Traits;

use EcomailDeps\Wpify\Core\Interfaces\FieldsStoreInterface;
trait CustomFieldsTrait
{
    /** @var FieldsStoreInterface $custom_fields_factory */
    private $fields_store;
    /**
     * @return FieldsStoreInterface
     */
    public function get_fields_store() : \EcomailDeps\Wpify\Core\Interfaces\FieldsStoreInterface
    {
        return $this->fields_store;
    }
    /**
     * @param FieldsStoreInterface $fields_store
     */
    public function set_fields_store(\EcomailDeps\Wpify\Core\Interfaces\FieldsStoreInterface $fields_store) : void
    {
        $this->fields_store = $fields_store;
    }
    /**
     * @param string $type
     * @param string $entity_name
     */
    protected function init_custom_fields(string $type, string $entity_name)
    {
        if ($this->fields_store()) {
            /** @var FieldsStoreInterface $factory */
            $this->fields_store = $this->plugin->create_component($this->fields_store(), ['type' => $type, 'entity_name' => $entity_name]);
            $this->fields_store->init();
        }
    }
    /**
     * Set custom fields factory needed for custom fields registration / manipulation
     * @return string|null
     */
    public function fields_store() : ?string
    {
        return null;
    }
}
