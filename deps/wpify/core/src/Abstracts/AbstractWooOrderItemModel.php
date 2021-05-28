<?php

namespace EcomailDeps\Wpify\Core\Abstracts;

use EcomailDeps\ComposePress\Core\Exception\Plugin;
use WC_Order_Item;
use WC_Order_Item_Fee;
use WC_Order_Item_Product;
use WC_Order_Item_Shipping;
/**
 * @package Wpify\Core
 */
abstract class AbstractWooOrderItemModel extends \EcomailDeps\Wpify\Core\Abstracts\AbstractComponent
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
    /** @var WC_Order_Item $wc_order_item WC_Order Item */
    private $wc_order_item;
    private $type;
    private $name;
    private $unit_price_tax_included;
    private $unit_price_tax_excluded;
    private $quantity;
    private $vat_rate;
    private $tax_total;
    private $tax_class;
    /**
     * @param WC_Order_Item $item
     */
    public function __construct(\WC_Order_Item $item)
    {
        $this->wc_order_item = $item;
    }
    public function init()
    {
        $this->id = $this->wc_order_item->get_id();
        $this->type = $this->wc_order_item->get_type();
        $this->name = $this->wc_order_item->get_name();
        $this->quantity = $this->wc_order_item->get_quantity();
        $this->tax_total = $this->wc_order_item->get_total_tax();
        $this->tax_class = $this->wc_order_item->get_tax_class();
        parent::init();
    }
    /**
     * @return WC_Order_Item
     */
    public function get_wc_order_item() : \WC_Order_Item
    {
        return $this->wc_order_item;
    }
    /**
     * @param WC_Order_Item $wc_order_item
     */
    public function set_wc_order_item(\WC_Order_Item $wc_order_item) : void
    {
        $this->wc_order_item = $wc_order_item;
    }
    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function get_type()
    {
        return $this->type;
    }
    /**
     * @return mixed
     */
    public function get_name()
    {
        return $this->name;
    }
    /**
     * @return mixed
     */
    public function get_unit_price_tax_included()
    {
        if ($this->unit_price_tax_included) {
            return $this->unit_price_tax_included;
        }
        $this->unit_price_tax_included = $this->get_unit_price();
        return $this->unit_price_tax_included;
    }
    public function get_unit_price($inc_tax = \true)
    {
        if (\is_callable(array($this->wc_order_item, 'get_total')) && $this->wc_order_item->get_quantity()) {
            if ($inc_tax) {
                $total = ($this->wc_order_item->get_total() + $this->wc_order_item->get_total_tax()) / $this->wc_order_item->get_quantity();
            } else {
                $total = \floatval($this->wc_order_item->get_total()) / $this->wc_order_item->get_quantity();
            }
        }
        return $total;
    }
    /**
     * @return mixed
     */
    public function get_unit_price_tax_excluded()
    {
        if ($this->unit_price_tax_excluded) {
            return $this->unit_price_tax_excluded;
        }
        $this->unit_price_tax_excluded = $this->get_unit_price(\false);
        return $this->unit_price_tax_excluded;
    }
    /**
     * @return mixed
     */
    public function get_quantity()
    {
        return $this->quantity;
    }
    /**
     * @return mixed
     */
    public function get_vat_rate()
    {
        if ($this->vat_rate) {
            return $this->vat_rate;
        }
        $tax = 0;
        if ($this->wc_order_item->get_tax_status() == 'taxable') {
            if ($this->wc_order_item->get_total_tax()) {
                $tax = \round($this->wc_order_item->get_total_tax() / ($this->wc_order_item->get_total() / 100));
            }
        }
        $this->vat_rate = $tax;
        return $this->vat_rate;
    }
    /**
     * @return mixed
     */
    public function get_tax_total()
    {
        return $this->tax_total;
    }
    /**
     * @return mixed
     */
    public function get_tax_class()
    {
        return $this->tax_class;
    }
}
