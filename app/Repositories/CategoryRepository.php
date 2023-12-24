<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends BaseRepository
{
   function getMany() {
      $query = DB::table('ref_risk_categories');

      return $query->get();
   }
}
