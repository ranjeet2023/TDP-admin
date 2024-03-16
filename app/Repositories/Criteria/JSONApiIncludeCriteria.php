<?php

namespace App\Repositories\Criteria;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\QueryBuilder;

class JSONApiIncludeCriteria implements CriteriaInterface
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository.
     *
     * @param $model
     * @param RepositoryInterface $repository
     *
     * @return Builder
     */
    public function apply($model, RepositoryInterface $repository): Builder
    {
        /** @var BaseRepository $baseRepository */
        $baseRepository = $repository;

        $availableRelations = $baseRepository->getAvailableRelations();
        $fields = $repository->getFieldsSearchable();

        return QueryBuilder::for($model)
            ->allowedFields($fields)
            ->allowedIncludes($availableRelations)
            ->getEloquentBuilder();
    }
}
