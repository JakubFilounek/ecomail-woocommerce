<?php

namespace EcomailDeps\Wpify\Core\Models;

use EcomailDeps\Wpify\Core\Abstracts\AbstractPostTypeModel;
/**
 * @package Wpify\Core
 */
class AttachmentModel extends \EcomailDeps\Wpify\Core\Abstracts\AbstractPostTypeModel
{
    public function get_type()
    {
    }
    public function get_url()
    {
        return wp_get_attachment_url($this->get_id());
    }
}
