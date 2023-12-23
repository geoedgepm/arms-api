<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class RiskRepository.
 */
class RiskRepository extends BaseRepository
{
   /**
    * Filter option base on provided condition
    */
   private function filterOption($option, $query) {
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

      return $query;
   }

   /**
    * Get risk count for dashboard statistic
    */
   function getRiskCount($option = []) {
      $query = DB::table('rms_risk_add_treatment AS RAT')
      ->join('rms_risk_register AS RR', 'RAT.Risk_ID', '=', 'RR.Risk_ID')
      ->select([
         DB::raw('COUNT(RAT.Risk_ID) AS count'),
         'ImpactStatus AS status'
      ]);

      $query = $this->filterOption($option, $query);

      //TODO: apply access condition record base user role/position

      return $query->where('ImpactAddCtrlCategory', '<>', 'Accept')
      ->where('RiskStatus', 'Active')
      ->groupBy('ImpactStatus')
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
      ->select('RR.Risk_ID', 'RiskEvent')
      ->selectRaw('COUNT(DISTINCT IRAT.RAT_ID) AS ImpRiskTreatment')
      ->selectRaw('COUNT(DISTINCT LRAT.RAT_ID) AS LikRiskTreatment')
      ->leftJoin(DB::raw("(SELECT RAT_ID, Risk_ID, ImpactAddCtrlCategory FROM rms_risk_add_treatment WHERE ImpactAddCtrlCategory <> 'Accept'".$impactRiskStatusFilter.$impCategoryFilter.") AS IRAT"), function ($join) {
         $join->on('RR.Risk_ID', '=', 'IRAT.Risk_ID');
      })
      ->leftJoin(DB::raw("(SELECT RAT_ID, Risk_ID, LikelihoodAddCtrlCategory FROM rms_add_risk_treatment_likelihood WHERE LikelihoodAddCtrlCategory <> 'Accept'".$likehoodRiskStatus.$likehoodCategoryFilter.") AS LRAT"), function ($join) {
         $join->on('RR.Risk_ID', '=', 'LRAT.Risk_ID');
      })
      ->where('RR.Risk_ID', '<>', '')
      ->where('RR.RiskStatus', '=', 'Active')
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
    * Get risk treatment summary by cateories for dounut chart
    */
   function getRiskTreatmentByCategories($option = []) {
      $query = DB::table('rms_risk_add_treatment AS RAT')
      ->join('rms_risk_register AS RR', 'RAT.Risk_ID', '=', 'RR.Risk_ID')
      ->select([
         DB::raw('COUNT(RAT.Risk_ID) AS count'),
         'ImpactAddCtrlCategory AS category'
      ]);

      $query = $this->filterOption($option, $query);

      //TODO: apply access condition record base user role/position

      return $query->where('ImpactAddCtrlCategory', '<>', 'Accept')
      ->where('RiskStatus', 'Active')
      ->groupBy('ImpactAddCtrlCategory')
      ->get();
   }

   function getRiskTreatmentDetails() {

   }
}
