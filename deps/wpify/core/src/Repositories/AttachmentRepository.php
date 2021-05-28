<?php

namespace EcomailDeps\Wpify\Core\Repositories;

use EcomailDeps\Wpify\Core\Abstracts\AbstractPostTypeRepository;
use EcomailDeps\Wpify\Core\Cpt\AttachmentPostType;
class AttachmentRepository extends \EcomailDeps\Wpify\Core\Abstracts\AbstractPostTypeRepository
{
    public function post_type()
    {
        $post_type = $this->plugin->create_component(\EcomailDeps\Wpify\Core\Cpt\AttachmentPostType::class);
        $post_type->init();
        return $post_type;
    }
}
