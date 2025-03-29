<?php

namespace App\Repositories;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TaskRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
       return $this->model->newQuery();
    }

    /**
     * @param string $term
     * @return Builder
     */
    public function search(string $term): Builder
    {
        $query = $this->model->newQuery();

        // Check if the term is a valid date
        if (strtotime($term) !== false) {
            $date = Carbon::parse($term)->format('Y-m-d');
            $query->orWhere('due_date', 'like', '%' . $date . '%');
        } else {
            $query->orWhere('title', 'like', '%' . $term . '%');
        }
        return $query;
    }
}
