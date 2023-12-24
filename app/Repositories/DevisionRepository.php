<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class DevisionRepository.
 */
class DevisionRepository extends BaseRepository
{
   function getMany() {
      return DB::table('org_chart')
      ->select('Division AS division')
      ->distinct()
      ->get();
   }
}
