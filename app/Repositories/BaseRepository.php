<?php

namespace App\Repositories;

use App\Repositories\Criteria\JSONApiAllowedFieldsCriteria;
use App\Repositories\Criteria\JSONApiFilterCriteria;
use App\Repositories\Criteria\JSONApiIncludeCriteria;
use App\Repositories\Criteria\JSONApiSortingCriteria;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;
use Ramsey\Uuid\Uuid;

abstract class BaseRepository extends PrettusBaseRepository
{
    /**
     * Get Searchable Fields
     *
     * @return array
     */
    public function getAvailableRelations()
    {
        return [];
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        parent::boot();
        $this->pushCriteria(app(JSONApiSortingCriteria::class));
        $this->pushCriteria(app(JSONApiFilterCriteria::class));
        $this->pushCriteria(app(JSONApiAllowedFieldsCriteria::class));
        $this->pushCriteria(app(JSONApiIncludeCriteria::class));

        Paginator::currentPageResolver(function() {
            return request()->input('page.number', 1);
        });
    }

    public function create(array $attributes)
    {
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return parent::update($attributes, $id);
    }

    public function fetch($request)
    {
        if (!empty($request->get('limit'))) {
            $this->take($request->get('limit'));
        }

        if (!empty($request->get('skip'))) {
            $this->skip($request->get('skip'));
        }

        return $this->get();
    }
}
