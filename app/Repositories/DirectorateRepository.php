<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class DirectorateRepository.
 */
class DirectorateRepository extends BaseRepository
{
   function getMany() {
      return DB::table('org_chart')
      ->select('Directorate AS directorate')
      ->distinct()
      ->get();
   }
}
