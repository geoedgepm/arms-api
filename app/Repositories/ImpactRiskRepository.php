<?php

namespace App\Repositories;

use App\Repositories\RiskRepository;
use Illuminate\Support\Facades\DB;
use App\Enums\RiskStatus;

/**
 * Class ImpactRiskRepository.
 */
class ImpactRiskRepository extends RiskRepository
{
   
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

   /**
    * Get risk count for dashboard statistic
    */
   function getRiskCount($option = []) {
      $query = DB::table("rms_risk_add_treatment AS RAT")
      ->join('rms_risk_register AS RR', 'RAT.Risk_ID', '=', 'RR.Risk_ID')
      ->select([
         DB::raw('COUNT(RAT.Risk_ID) AS count'),
         'ImpactStatus AS status'
      ])
      ->groupBy('ImpactStatus');

      $query = $this->filterOption($option, $query);

      //TODO: apply access condition record base user role/position

      return $query->where('RiskStatus', RiskStatus::ACTIVE)
      ->get();
   }

   /**
    * Get risk treatment summary by cateories for dounut chart
    */
    function getRiskTreatmentByCategories($option = []) {
      $query = DB::table('rms_risk_add_treatment AS RAT')
      ->join('rms_risk_register AS RR', 'RAT.Risk_ID', '=', 'RR.Risk_ID')
      ->select([
         DB::raw('COUNT(RAT.Risk_ID) AS count'),
         'ImpactAddCtrlCategory AS category'
      ])
      ->groupBy('ImpactAddCtrlCategory');

      $query = $this->filterOption($option, $query);

      //TODO: apply access condition record base user role/position

      return $query->where('RiskStatus', RiskStatus::ACTIVE)
      ->get();
   }

   /**
    * Get risk summary for dashboard
    */
   function getRiskSummary($option = []) {
      // Impact filter option
      $impactRiskStatusFilter = '';
      $impCategoryFilter = '';

      // Likehood filter option
      $likehoodRiskStatus = '';
      $likehoodCategoryFilter = '';

      if ( isset($option['status']) && $option['status'] ) {
         $status = $option['status'];
         $impactRiskStatusFilter = " AND ImpactStatus = '".$status."'";
         $likehoodRiskStatus = " AND LikelihoodStatus = '".$status."'";
      }

      if ( isset($option['category']) && $option['category'] ) {
         $category = $option['category'];
         $impCategoryFilter = " AND ImpactAddCtrlCategory = '".$category."'";
         $likehoodCategoryFilter = " AND LikelihoodAddCtrlCategory = '".$category."'";
      }

      $results = DB::table('rms_risk_register AS RR')
      ->select('RR.Risk_ID AS riskId', 'RiskEvent AS riskEvent')
      ->selectRaw('COUNT(DISTINCT IRAT.RAT_ID) AS impRiskTreatment')
      ->selectRaw('COUNT(DISTINCT LRAT.RAT_ID) AS likRiskTreatment')
      ->leftJoin(DB::raw("(SELECT RAT_ID, Risk_ID, ImpactAddCtrlCategory FROM rms_risk_add_treatment WHERE ImpactAddCtrlCategory <> 'Accept'".$impactRiskStatusFilter.$impCategoryFilter.") AS IRAT"), function ($join) {
         $join->on('RR.Risk_ID', '=', 'IRAT.Risk_ID');
      })
      ->leftJoin(DB::raw("(SELECT RAT_ID, Risk_ID, LikelihoodAddCtrlCategory FROM rms_add_risk_treatment_likelihood WHERE LikelihoodAddCtrlCategory <> 'Accept'".$likehoodRiskStatus.$likehoodCategoryFilter.") AS LRAT"), function ($join) {
         $join->on('RR.Risk_ID', '=', 'LRAT.Risk_ID');
      })
      ->where('RR.Risk_ID', '<>', '')
      ->where('RR.RiskStatus', '=', RiskStatus::ACTIVE)
      ->where(function ($query) use ($option) {
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
            $query->where('Directorate', $option['directorate']);
         }

         if ( isset($option['division']) && $option['division'] ) {
            $query->where('Division', $option['division']);
         }

         //TODO: apply access condition record base user role/position
      })
      ->groupBy('RR.Risk_ID', 'RiskEvent')
      ->havingRaw('COUNT(DISTINCT IRAT.RAT_ID) <> 0 OR COUNT(DISTINCT LRAT.RAT_ID) <> 0')
      ->get();


      //TODO: apply access condition record base user role/position

      return $results;
   }

   /**
    * Get risk treatment details for dashboard
    */
   function getRiskTreatmentDetails($option = []) {
      $query = DB::table('rms_risk_add_treatment as RAT')
      ->select(
         'RAT_ID AS ratId',
         'RAT.Risk_ID AS riskId',
         'ImpactAddCtrlCategory AS category',
         'ImpactAddCtrlDescription AS description',
         'ImpactPIC AS impactPIC',
         DB::raw("DATE_FORMAT(ImpactDueDate, '%d/%m/%Y') AS dueDate"),
         DB::raw("FORMAT(ImpactCost, 2) AS cost"),
         'ImpactStatus AS status',
         'ImpactRemarks AS remarks'
      )
      ->join('rms_risk_register as RR', 'RAT.Risk_ID', '=', 'RR.Risk_ID')
      ->where('ImpactAddCtrlCategory', '<>', 'Accept')
      ->where('RiskStatus', '=', RiskStatus::ACTIVE)
      ->where(function($query) use($option) {
         $this->filterOption($option, $query);
      });

      if ( isset($option['offset']) && $option['offset'] ) {
         $query->offset(($option['offset']));
      }

      if ( isset($option['limit']) && $option['limit'] ) {
         $query->offset(($option['limit']));
      }
      
      return $query->get();
   }
}
