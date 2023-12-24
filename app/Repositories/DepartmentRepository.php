<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class DepartmentRepository.
 */
class DepartmentRepository extends BaseRepository
{
   function getMany() {
      return DB::table('org_chart')
      ->select('Department AS department')
      ->distinct()
      ->get();
   }
}
