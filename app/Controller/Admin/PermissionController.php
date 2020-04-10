<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;

class PermissionController extends BaseController
{
    public function list()
    {
        return $this->response->raw('Hello Hyperf!');
    }
}
