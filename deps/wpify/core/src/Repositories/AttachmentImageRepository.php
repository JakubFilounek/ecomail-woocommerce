<?php

namespace EcomailDeps\Wpify\Core\Repositories;

use EcomailDeps\Wpify\Core\Cpt\AttachmentImagePostType;
class AttachmentImageRepository extends \EcomailDeps\Wpify\Core\Repositories\AttachmentRepository
{
    public function post_type()
    {
        $post_type = $this->plugin->create_component(\EcomailDeps\Wpify\Core\Cpt\AttachmentImagePostType::class);
        $post_type->init();
        return $post_type;
    }
}
