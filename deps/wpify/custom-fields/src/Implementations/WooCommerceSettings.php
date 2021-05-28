<?php

namespace EcomailDeps\WpifyCustomFields\Implementations;

use WC_Admin_Settings;
use EcomailDeps\WpifyCustomFields\WpifyCustomFields;
/**
 * Class WooCommerceSettings
 * @package WpifyCustomFields\Implementations
 */
final class WooCommerceSettings extends \EcomailDeps\WpifyCustomFields\Implementations\AbstractImplementation
{
    /** @var array */
    private $tab;
    /** @var array */
    private $section;
    /** @var array */
    private $items;
    /** @var string */
    private $class;
    /** @var bool */
    private $is_new_tab = \false;
    /**
     * WooCommerceSettings constructor.
     *
     * @param array $args
     * @param WpifyCustomFields $wcf
     */
    public function __construct(array $args, \EcomailDeps\WpifyCustomFields\WpifyCustomFields $wcf)
    {
        parent::__construct($args, $wcf);
        $args = wp_parse_args($args, array('tab' => array('id' => '', 'label' => null), 'section' => array('id' => '', 'label' => null), 'class' => null, 'items' => array()));
        $this->tab = $args['tab'];
        $this->section = $args['section'];
        $this->class = $args['class'];
        $this->items = $args['items'];
        add_filter('woocommerce_settings_tabs_array', array($this, 'woocommerce_settings_tabs_array'), 30);
        add_filter('woocommerce_get_sections_' . $this->tab['id'], array($this, 'woocommerce_get_sections'));
        add_action('woocommerce_settings_' . $this->tab['id'], array($this, 'render'), 11);
        add_action('woocommerce_settings_save_' . $this->tab['id'], array($this, 'save'));
        /* The WCF does redirect after save, so we need
         * to handle showing the success message by ourselves */
        $tab = empty($_REQUEST['tab']) ? '' : sanitize_title($_REQUEST['tab']);
        $section = empty($_REQUEST['section']) ? '' : sanitize_title($_REQUEST['section']);
        if ($tab === $this->tab['id'] && $section === $this->section['id'] && !empty($_REQUEST['settings-updated']) && $_REQUEST['settings-updated'] === '1') {
            \WC_Admin_Settings::add_message(__('Your settings have been saved.', 'woocommerce'));
        }
    }
    /**
     * @return void
     */
    public function set_wcf_shown()
    {
        $current_screen = get_current_screen();
        $tab = empty($_REQUEST['tab']) ? '' : sanitize_title($_REQUEST['tab']);
        $section = empty($_REQUEST['section']) ? '' : sanitize_title($_REQUEST['section']);
        $this->wcf_shown = $current_screen->base === 'woocommerce_page_wc-settings' && $tab === $this->tab['id'] && $section === $this->section['id'];
    }
    /**
     * @param $tabs
     *
     * @return mixed
     */
    public function woocommerce_settings_tabs_array($tabs)
    {
        if (empty($tabs[$this->tab['id']])) {
            $tabs[$this->tab['id']] = $this->tab['label'];
            $this->is_new_tab = \true;
        }
        return $tabs;
    }
    /**
     * @param $sections
     *
     * @return mixed
     */
    public function woocommerce_get_sections($sections)
    {
        if (!empty($this->section)) {
            $sections[$this->section['id']] = $this->section['label'];
        }
        if (isset($sections[''])) {
            $empty_section = $sections[''];
            $reordered_sections = array('' => $empty_section);
            unset($sections['']);
            foreach ($sections as $id => $label) {
                $reordered_sections[$id] = $label;
            }
            $sections = $reordered_sections;
        }
        return $sections;
    }
    /**
     * @return void
     */
    public function render()
    {
        global $current_section;
        if ($this->is_new_tab) {
            $sections = $this->get_sections();
            if (!empty($sections) || \count($sections) > 1) {
                $array_keys = \array_keys($sections);
                ?>
				<ul class="subsubsub">
					<?php 
                foreach ($sections as $id => $label) {
                    ?>
						<li>
							<a href="<?php 
                    echo admin_url('admin.php?page=wc-settings&tab=' . $this->tab['id'] . '&section=' . sanitize_title($id));
                    ?>"
							   class="<?php 
                    echo $current_section == $id ? 'current' : '';
                    ?>">
								<?php 
                    echo $label;
                    ?>
							</a>
							<?php 
                    echo \end($array_keys) == $id ? '' : '|';
                    ?>
						</li>
					<?php 
                }
                ?>
				</ul>
				<br class="clear"/>
				<?php 
            }
        }
        if ($current_section !== $this->section['id']) {
            return;
        }
        $this->render_fields('woocommerce_settings', 'div', array('class' => $this->class));
    }
    /**
     * @return mixed|void
     */
    public function get_sections()
    {
        return apply_filters('woocommerce_get_sections_' . $this->tab['id'], array());
    }
    /**
     * @return array
     */
    public function get_data()
    {
        return array('object_type' => 'woocommerce_settings', 'tab' => $this->tab, 'section' => $this->section, 'items' => $this->get_items());
    }
    public function get_items()
    {
        $items = apply_filters('wcf_woocommerce_settings_items', $this->items, array('tab' => $this->tab, 'section' => $this->section));
        return $this->prepare_items($items);
    }
    /**
     * @param string $name
     * @param string $default
     *
     * @return mixed
     */
    public function get_field($name, $default = '')
    {
        return \WC_Admin_Settings::get_option($name, $default);
    }
    /**
     * @return void
     */
    public function save()
    {
        $tab = empty($_REQUEST['tab']) ? '' : sanitize_title($_REQUEST['tab']);
        $section = empty($_REQUEST['section']) ? '' : sanitize_title($_REQUEST['section']);
        if ($tab === $this->tab['id'] && $section === $this->section['id']) {
            foreach ($this->get_items() as $item) {
                if (!empty($item['id'])) {
                    $sanitizer = $this->sanitizer->get_sanitizer($item);
                    $value = $sanitizer(wp_unslash($_POST[$item['id']]));
                    $this->set_field($item['id'], $value);
                }
            }
            wp_redirect(add_query_arg(array('page' => 'wc-settings', 'tab' => $this->tab['id'], 'section' => $this->section['id'], 'settings-updated' => \true), admin_url('admin.php')));
            exit;
        }
    }
    /**
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function set_field($name, $value)
    {
        return update_option($name, $value);
    }
}
