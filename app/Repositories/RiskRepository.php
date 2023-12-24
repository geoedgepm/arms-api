<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Enums\RiskType;

/**
 * Class RiskRepository.
 */
class RiskRepository extends BaseRepository
{
   /**
    * Filter option base on provided condition
    */
   protected function filterOption($option, $query) {
      if ( isset($option['fiscalYear']) && $option['fiscalYear'] ) {
         $query->where('FiscalYear', $option['fiscalYear']);
      }

      if ( isset($option['quarter']) && $option['quarter'] ) {
         $query->where('Quarter', $option['quarter']);
      }

      if ( isset($option['department']) && $option['department'] ) {
         $query->where('Department', $option['department']);
      }

      if ( isset($option['directorate']) && $option['directorate'] ) {
         $query->where('Department', $option['department']);
      }

      if ( isset($option['division']) && $option['division'] ) {
         $query->where('Division', $option['division']);
      }

      if ( isset($option['category']) && $option['category'] ) {
         $query->where('ImpactAddCtrlCategory', $option['category']);
      }

      if ( isset($option['status']) && $option['status'] ) {
         $query->where('ImpactStatus', $option['status']);
      }

      if ( isset($option['riskId']) && $option['riskId'] ) {
         $query->where('RAT.Risk_ID', $option['riskId']);
      }

      if ( isset($option['rmType']) && $option['rmType'] ) {
         $rmType = $option['rmType'];
         if ($rmType == RiskType::IMPACT) {
            $query->where('ImpactAddCtrlCategory', '<>', "Accept");
         } else if ($rmType == RiskType::LIKE_LIHOOD) {
            $query->where('LikelihoodAddCtrlCategory', '<>', "Accept");
         }
      }

      return $query;
   }

   public function getRiskRepository($rmType) {
      return $rmType == RiskType::LIKE_LIHOOD ? 'likeLihoodRiskRepository' : 'impactRiskRepository';
   }

}
