<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DirectorateRepository;
use App\Http\Controllers\BaseController;

class DirectorateController extends BaseController {
    public function __construct(
        private DirectorateRepository $repository
    ) {}
}