<?php

namespace App\Http\Controllers;

use App\Repositories\DepartmentRepository;
use App\Http\Controllers\BaseController;

class DepartmentController extends BaseController {
    public function __construct(
        private DepartmentRepository $repository
    ) {}
}