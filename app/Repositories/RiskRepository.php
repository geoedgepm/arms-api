<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Enums\RiskType;
use Illuminate\Support\Facades\DB;

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
         } else if ($rmType == RiskType::INHERENT) {

         } else if ($rmType == RiskType::RESIDUAL) {

         }
      }

      return $query;
   }

   public function getRiskRepository($rmType) {
      return $rmType == RiskType::LIKE_LIHOOD ? 'likeLihoodRiskRepository' : 'impactRiskRepository';
   }

   /**
    * Get risks
    */
   function getMany($option = []) {
      $query = DB::table('rms_risk_register')
      ->select([
         'Risk_ID AS riskId',
         'FiscalYear AS fiscalYear',
         'Quarter AS quarter',
         'Department AS department',
         'Directorate AS directorate',
         'Division AS division',
         'Objectives AS objectives',
         'RiskEvent AS riskEvent',
         'InherentRiskScore AS inherentRiskScore',
         'InherentRiskScoreDescription AS inherentRiskScoreDescription',
         'ResidualRiskScore AS residualRiskScore',
         'ResidualRiskScoreDescription AS residualRiskScoreDescription'
      ]);

      $query = $this->filterOption($option, $query);

      $query->orderByDesc('EnteredDate');

      if (isset($option['offset']) && $option['offset']) $query->limit($option['offset']);
      if (!isset($option['limit'])) $option['limit'] = $this->limit;

      $query->limit($option['limit']);
      
      return $query->get();
   }

   /**
    * Get risk by id
    */
    function getRiskById($id) {
      return DB::table('rms_risk_register')
      ->select([
         'Risk_ID AS riskId',
         'FiscalYear AS fiscalYear',
         'Quarter AS quarter',
         'Department AS department',
         'Directorate AS directorate',
         'Division AS division',
         'Objectives AS objectives',
         'RiskCategoryL1 AS riskCategoryL1',
         'RiskCategoryL2 AS riskCategoryL2',
         'RiskCategoryL3 AS riskCategoryL3',
         'RiskEvent AS riskEvent',
         'InherentImpactRating AS inherentImpactRating',
         'InherentLikelihoodCategory AS inherentLikelihoodCategory',
         'InherentLikelihoodRating AS inherentLikelihoodRating',
         'InherentLikelihoodJustification AS inherentLikelihoodJustification',
         'InherentLikelihoodExisitingCtrlCat AS inherentLikelihoodExisitingCtrlCat',
         'InherentLikelihoodExisitingCtrlInfo AS inherentLikelihoodExisitingCtrlInfo',
         'InherentLikelihoodPIC AS inherentLikelihoodPIC',
         'InherentRiskScore AS inherentRiskScore',
         'InherentRiskScoreDescription AS inherentRiskScoreDescription',
         'ResidualImpactRating AS residualImpactRating',
         'ResidualLikelihoodRating AS residualLikelihoodRating',
         'ResidualRiskScore AS residualRiskScore',
         'ResidualRiskScoreDescription AS residualRiskScoreDescription'
      ])
      ->where('Risk_ID', $id)
      ->first();
   }

}
