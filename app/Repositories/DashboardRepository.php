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
         ['value' => 'impact', 'label' => 'Impact Risk Treatment'],
         ['value' => 'likelihood', 'label' => 'Likelihood Risk Treatment']
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
      ->select([
         'Division AS label',
         'Division AS value',
      ])
      ->distinct()
      ->get();

      $periods = [
         'Cycle 1',
         'Cycle 2'
      ];

      $categories = [
         ['value' => 'Avoid', 'label' => 'Avoid'],
         ['value' => 'Transfer', 'label' => 'Transfer'],
         ['value' => 'Mitigate/Reduce', 'label' => 'Mitigate/Reduce']
      ];

      $statuses = [
         ['value' => 'Cancelled', 'label' => 'Cancelled'],
         ['value' => 'Not Started', 'label' => 'Not Started'],
         ['value' => 'In Progress', 'label' => 'In Progress'],
         ['value' => 'Near Due Date', 'label' => 'Near Due Date'],
         ['value' => 'Overdue', 'label' => 'Overdue']
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
