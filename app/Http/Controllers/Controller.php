<?php

namespace App\Http\Controllers;

use App\Models\SideBarMenu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $data = [];
    protected $userId;

    public function __construct()
    {
        $this->data['title']  = env('APP_NAME');
    }



    protected function YajraFilterValue(
        $filterValue,
        $query,
        $columnFilter,
        $join = false,
        $table = null,
        $columnRelation = null,
        $tableJoin = null
    ) {
        if ($join)
            $query->join($tableJoin, "$table.$columnRelation", '=', "$tableJoin.id");

        $filterValue = json_decode($filterValue);
        if (!empty($filterValue)) {
            $query->whereIn($columnFilter, $filterValue);
        }
    }

    /**
     * YajraColumnSearch
     *
     * @param  mixed $query
     * @param  array $columnSearch
     * @param  string $searchValue
     * @return void
     */
    protected function YajraColumnSearch($query, $columnSearch, $searchValue)
    {
        $query->where(function ($query) use ($columnSearch, $searchValue) {
            $i = 0;
            foreach ($columnSearch as $item) {
                if ($i == 0)
                    $query->where($item, 'like', "%{$searchValue}%");
                else
                    $query->orWhere($item, 'like', "%{$searchValue}%");
                $i++;
            }
        });
    }

    /**
     * YajraColumnSearch
     *
     * @param  mixed $query
     * @param  array $columnSearch
     * @param  string $searchValue
     * @return void
     */
    protected function YajraColumnFilter($query, $columnSearch, $searchValue)
    {
        $query->where(function ($query) use ($columnSearch, $searchValue) {
            foreach ($columnSearch as $item) {
                $query->where($item, "{$searchValue}");
            }
        });
    }

    /**
     * filterDateRange
     *
     * @param  mixed $query
     * @param  string $columnFilter
     * @param  object $request
     * @return void
     */
    protected function filterDateRange($query, $columnFilter, $request)
    {
        if ($request->startDate && $request->endDate) {
            $query->where($columnFilter, '>=', "$request->startDate 00:00:00");
            $query->where($columnFilter, '<=', "$request->endDate 23:59:59");
        }
    }


    protected function renderTo($file_name)
    {
        return view($file_name, $this->data);
    }

    /**
     * responseJSONFailedPermission
     *
     * @param  string $permission_name
     * @return void
     */
    protected function responseJSONFailedPermission($permission_name)
    {
        return response()->json([
            'status'    => "Failed",
            'message'   => "You do not have permission to access $permission_name"
        ], 403);
    }


    protected function isAllSetAndReturn($request)
    {
        foreach ($request as $key => $value) {
            if (trim($value) === "" || $value === null)
                unset($request[$key]);
        }
        return $request;
    }

    protected function generateWhere($request_body, $string_compare = "=")
    {
        $request = $this->isAllSetAndReturn($request_body);

        $array_where = [];
        foreach ($request as $key => $value) {
            array_push($array_where, [$key, $string_compare, $value]);
        }
        return $array_where;
    }
}
