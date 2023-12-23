<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DashboardRepository;
use App\Repositories\RiskRepository;
use App\Http\Controllers\BaseController;

class DashboardController extends BaseController {
    private $repository;
    private $riskRepository;

    public function __construct(
        DashboardRepository $repository,
        RiskRepository $riskRepository,
    ) {
        $this->repository = $repository;
        $this->riskRepository = $riskRepository;
    }

    /**
     * Get select options data for dashboard form
     */
    public function getSelectOptions() {
        return $this->responses($this->repository->getSelectOptions());
    }

    /***
     * Get risk count
     */
    public function riskCount(Request $request)
    {
        return $this->responses($this->riskRepository->getRiskCount());
    }

    /**
     * Get risk summary
     */
    public function getRiskSummary() {
        return $this->responses($this->riskRepository->getRiskSummary());
    }

    /**
     * Get risk treatment by category
     */
    public function getRiskTreatmentByCategory() {
        return $this->responses($this->riskRepository->getRiskTreatmentByCategories());
    }

    /**
     * Get risk treament details
     */
    public function getRiskTreatmentDetails() {
        return $this->responses($this->riskRepository->getRiskTreatmentDetails());
    }
}