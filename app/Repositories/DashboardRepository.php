<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardRepository.
 */
class DashboardRepository extends BaseRepository
{
   /**
    * get select options for form filter
    */
   function getSelectOptions() {
      $riskTreatmentFors = [
         'impact' => 'Impact Risk Treatment',
         'likelihood' => 'Likelihood Risk Treatment'
      ];

      $departments = DB::table('org_chart')
      ->select('Department AS department')
      ->distinct()
      ->get();

      $directorates = DB::table('org_chart')
      ->select('Directorate AS directorate')
      ->distinct()
      ->get();

      $divisions = DB::table('org_chart')
      ->select('Division AS division')
      ->distinct()
      ->get();

      $periods = [
         'Cycle 1',
         'Cycle 2'
      ];

      $categories = [
         'Avoid',
         'Mitigate/Reduce',
         'Transfer'
      ];

      $statuses = [
         'Cancelled',
         'Not Started',
         'In Progress',
         'Near Due Date',
         'Overdue'
      ];

      return [
         'riskTreatmentFors' => $riskTreatmentFors,
         'departments' => $departments,
         'directorates' => $directorates,
         'categories' => $categories,
         'statuses' => $statuses,
         'divisions' => $divisions,
         'periods' => $periods
      ];
   }
}
