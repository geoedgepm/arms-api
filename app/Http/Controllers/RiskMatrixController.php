<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RiskMatrixRepository;

class RiskMatrixController extends BaseController {
    
    public function __construct(
        private RiskMatrixRepository $repository
    ) {}

    public function index(Request $request)
    {
        return $this->responses($this->repository->getMany($request->only('rmType')));
    }
}