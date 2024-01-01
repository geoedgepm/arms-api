<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Repositories\DashboardRepository;
use App\Repositories\RiskRepository;
use App\Repositories\ImpactRiskRepository;
use App\Repositories\LikeLihoodRiskRepository;

class DashboardController extends BaseController {
    
    public function __construct(
        private DashboardRepository $repository,
        private RiskRepository $riskRepository,
        private ImpactRiskRepository $impactRiskRepository,
        private LikeLihoodRiskRepository $likeLihoodRiskRepository,
    ) {}

    /***
     * Get risk count for statistic
     */
    public function index(Request $request)
    {
        $option = $request->only('fiscalYear', 'quarter', 'department', 'directorate', 'division', 'rmType');
        $repository = $this->riskRepository->getRiskRepository($option['rmType']);

        $options = $this->repository->getSelectOptions();
        $riskCount = $this->{$repository}->getRiskCount();
        $riskSummaries = $this->{$repository}->getRiskSummary($option);
        $riskTreatmentByCategories = $this->{$repository}->getRiskTreatmentByCategories($option);
        $riskTreatmentDetails = $this->{$repository}->getRiskTreatmentDetails();

        return $this->responses([
            'options' => $options,
            'riskCount' => $riskCount,
            'riskSummaries' => $riskSummaries,
            'riskTreatmentByCategories' => $riskTreatmentByCategories,
            'riskTreatmentDetails' => $riskTreatmentDetails
        ]);
    }

    /**
     * Get select options data for dashboard form
     */
    public function getSelectOptions() {
        return $this->responses($this->repository->getSelectOptions());
    }

    /***
     * Get risk count for statistic
     */
    public function riskCount(Request $request)
    {
        $repository = $this->riskRepository->getRiskRepository($request->rmType);
        return $this->responses($this->{$repository}->getRiskCount());
    }

    /**
     * Get risk summary
     */
    public function getRiskSummary(Request $request) {
        $repository = $this->riskRepository->getRiskRepository($request->rmType);
        $option = $request->only('fiscalYear', 'quarter', 'department', 'directorate', 'division', 'rmType');

        return $this->responses($this->{$repository}->getRiskSummary($option));
    }

    /**
     * Get risk treatment by category
     */
    public function getRiskTreatmentByCategory(Request $request) {
        $repository = $this->riskRepository->getRiskRepository($request->rmType);
        return $this->responses($this->{$repository}->getRiskTreatmentByCategories($request->only('rmType')));
    }

    /**
     * Get risk treament details
     */
    public function getRiskTreatmentDetails(Request $request) {
        $repository = $this->riskRepository->getRiskRepository($request->rmType);
        return $this->responses($this->{$repository}->getRiskTreatmentDetails());
    }
}