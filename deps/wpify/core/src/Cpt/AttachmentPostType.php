<?php

namespace EcomailDeps\Wpify\Core\Cpt;

use EcomailDeps\Wpify\Core\Abstracts\AbstractPostType;
use EcomailDeps\Wpify\Core\Models\AttachmentModel;
class AttachmentPostType extends \EcomailDeps\Wpify\Core\Abstracts\AbstractPostType
{
    protected $register_cpt = \false;
    public function post_type_args() : array
    {
        return array();
    }
    /**
     * @inheritDoc
     */
    public function post_type_name() : string
    {
        return 'attachment';
    }
    /**
     * @inheritDoc
     */
    public function model() : string
    {
        return \EcomailDeps\Wpify\Core\Models\AttachmentModel::class;
    }
}
