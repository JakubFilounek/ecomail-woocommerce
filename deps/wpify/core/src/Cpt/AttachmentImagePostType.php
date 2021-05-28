<?php

namespace EcomailDeps\Wpify\Core\Cpt;

use EcomailDeps\Wpify\Core\Models\AttachmentImageModel;
class AttachmentImagePostType extends \EcomailDeps\Wpify\Core\Cpt\AttachmentPostType
{
    /**
     * @inheritDoc
     */
    public function model() : string
    {
        return \EcomailDeps\Wpify\Core\Models\AttachmentImageModel::class;
    }
}
