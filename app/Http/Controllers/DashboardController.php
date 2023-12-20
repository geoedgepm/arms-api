<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DashboardRepository;
use App\Http\Controllers\BaseController;

class DashboardController extends BaseController {
    private $repository;

    public function __construct(
        DashboardRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getSelectOptions() {
        return $this->responses($this->repository->getSelectOptions());
    }

    public function riskCount(Request $request)
    {
        return $this->responses($this->repository->getRiskCount());
    }

    public function getRiskSummary() {

    }

    public function getRiskTreatmentByCategory() {

    }

    public function getRiskTreatmentDetails() {

    }
}