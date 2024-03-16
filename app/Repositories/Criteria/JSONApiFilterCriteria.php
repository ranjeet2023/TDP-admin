<?php

namespace App\Repositories\Criteria;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Prettus\Repository\Contracts\CriteriaInterface;
use Spatie\QueryBuilder\QueryBuilder;

class JSONApiFilterCriteria implements CriteriaInterface
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, \Prettus\Repository\Contracts\RepositoryInterface $repository)
    {
        $searchableFields = $repository->getFieldsSearchable();

        $relationshipFilters = [];
        if (request()->get('filter') !== null) {
            foreach (request()->get('filter') as $key => $filter) {
                if (Str::contains($key, '.')) {
                    $relationshipFilters[] = $key;
                }
            }
        }
        $searchableFields = array_merge($searchableFields, $relationshipFilters);

        $query = QueryBuilder::for($model)
            ->allowedFilters($searchableFields)
            ->getEloquentBuilder();

        return $query;
    }
}
