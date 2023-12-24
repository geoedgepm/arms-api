<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Enums\RiskStatus;
use App\Enums\RiskType;
use App\Repositories\RiskRepository;

/**
 * Class RiskMatrixRepository.
 */
class RiskMatrixRepository extends RiskRepository
{
   /**
    * Get inherent risk matrix
    */
   private function getInhrentMatrix($option) {
      $query = DB::table('rms_risk_register');

      $query->select([
         'InherentImpactRating AS impactRating',
         'InherentLikelihoodRating AS likelihoodRating',
         DB::raw('COUNT(DISTINCT Risk_ID) AS riskcount')
      ]);

      $query->where('InherentRiskScore', '<>', 0)
      ->where('RiskStatus', RiskStatus::ACTIVE);

      $query = $this->filterOption($option, $query);

      $query->groupBy('InherentImpactRating', 'InherentLikelihoodRating');

      return $query->get();
   }

   /**
    * Get residual risk matrix
    */
    private function getResidualMatrix($option) {
      $query = DB::table('rms_risk_register');

      $query->select([
         'ResidualImpactRating AS impactRating',
         'ResidualLikelihoodRating AS likelihoodRating',
         DB::raw('COUNT(DISTINCT Risk_ID) AS riskcount')
      ]);

      $query->where('ResidualRiskScore', '<>', 0)
      ->where('RiskStatus', RiskStatus::ACTIVE);

      $query = $this->filterOption($option, $query);

      $query->groupBy('ResidualImpactRating', 'ResidualLikelihoodRating');

      return $query->get();
   }

   /**
    * Get risk matrix
    */
   function getMany($option = []) {
      $results = [];

      if ($option['rmType'] == RiskType::INHERENT) {
         $results = $this->getInhrentMatrix($option);
      } else if ($option['rmType'] == RiskType::RESIDUAL) {
         $results = $this->getResidualMatrix($option);
      }

      return $results;
   }
}
