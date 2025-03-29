<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }
    public function paginate($perPage = 15, $page = null): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, ["*"], "page", $page);
    }

    public function find($id)
    {
        $model =  $this->model->find($id);
        if (!$model) {
            throw new ModelNotFoundException();
        }
        return $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }

    public function where(array $conditions)
    {
        return $this->model->where($conditions);
    }
    public function formatPagination(LengthAwarePaginator $paginator, $resourceCollection)
    {
        return [
            'current_page' => $paginator->currentPage(),
            'data' => $resourceCollection,
            'first_page_url' => $paginator->url(1),
            'from' => $paginator->firstItem(),
            'last_page' => $paginator->lastPage(),
            'last_page_url' => $paginator->url($paginator->lastPage()),
            'links' => $this->getPaginationLinks($paginator),
            'next_page_url' => $paginator->nextPageUrl(),
            'path' => $paginator->path(),
            'per_page' => $paginator->perPage(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'to' => $paginator->lastItem(),
            'total' => $paginator->total(),
        ];
    }
    public function getPaginationLinks(LengthAwarePaginator $paginator)
    {
        return $paginator->linkCollection();
    }
}
