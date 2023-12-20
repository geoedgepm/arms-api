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
    * get summary risk count by status
    */
   function getRiskCount() {
      $query = DB::table('rms_risk_add_treatment AS RAT')
      ->join('rms_risk_register AS RR', 'RAT.Risk_ID', '=', 'RR.Risk_ID')
      ->select([
         DB::raw('COUNT(RAT.Risk_ID) AS count'),
         'ImpactStatus AS status'
      ]);

      return $query->where('ImpactAddCtrlCategory', '<>', 'Accept')
      ->where('RiskStatus', 'Active')
      ->groupBy('ImpactStatus')
      ->get();
   }


   function getRiskSummary() {
      
   }

   function getRiskTreatmentByCategories() {

   }

   function getRiskTreatmentDetails() {

   }
}
