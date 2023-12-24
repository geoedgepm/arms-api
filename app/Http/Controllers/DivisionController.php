<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DevisionRepository;
use App\Http\Controllers\BaseController;

class DivisionController extends BaseController {
    public function __construct(
        private DevisionRepository $repository
    ) {}
}