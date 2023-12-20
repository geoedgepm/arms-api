<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Repositories\Core\FindOption;

class CategoryController extends BaseController {
    private $repository;

    public function __construct(
        CategoryRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $option = new FindOption();
        $option->relations = ['childs'];
        $option->sorts = ['order', 'ASC'];
        return $this->responses($this->repository->getListByFilter($option));
    }
}