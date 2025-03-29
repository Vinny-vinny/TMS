<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "due_date" => Carbon::parse($this->due_date)->format('Y-m-d'),
            "date_created" => Carbon::parse($this->created_at)->format('Y-m-d'),
            "is_overdue" => Carbon::parse($this->due_date)->isPast(),
        ];
    }
}
