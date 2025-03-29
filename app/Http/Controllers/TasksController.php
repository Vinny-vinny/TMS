<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TasksResource;
use App\Repositories\TaskRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Exception;

class TasksController extends BaseController
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $tasks = $this->taskRepository->query()
            ->where('user_id', Auth::id())
            ->when($request->input('search'), function ($query, $term) {
                return $this->taskRepository->search($term);
            })
            ->when($request->input('sort_by') && $request->input('sort_order'), function ($query) use ($request) {
                return $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
            })
            ->paginate($request->input('per_page', 10), ["*"], "page", $request->input('page', 1));

        $tasksCollection = TasksResource::collection($tasks);
        return response()->json($this->taskRepository->formatPagination($tasks, $tasksCollection));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $request['due_date'] = Carbon::parse($request->input('due_date'))->format('Y-m-d');
            $request['user_id'] = Auth::id();
            $task = $this->taskRepository->create($request->all());
            return $this->sendResponse($task, 'Task has been successfully added', 201);
        } catch (QueryException $e) {
            info(__METHOD__ . $e->getMessage());
            return $this->sendError('Database connection error. ', $e->getMessage(), 500);
        } catch (Exception $exception) {
            info(__METHOD__ . $exception->getMessage());
            return $this->sendError('Internal server error. ', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $task = $this->taskRepository->find($id);

            if ($task->user_id !== Auth::id()) {
                return $this->sendError('Unauthorized access.', [], 403);
            }

            return $this->sendResponse(new TasksResource($task), 'Task retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            info(__METHOD__ . $e->getMessage());
            return $this->sendError('Task not found.');
        } catch (\Exception $e) {
            info(__METHOD__ . $e->getMessage());
            return $this->sendError('Internal server error. ', 500);
        }
    }

    /**
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        try {
            $task = $this->taskRepository->find($id);

            if ($task->user_id !== Auth::id()) {
                return $this->sendError('Unauthorized access.', [], 403);
            }

            $request['due_date'] = Carbon::parse($request->input('due_date'));
            $task = $this->taskRepository->update($id, $request->all());

            return $this->sendResponse(new TasksResource($task), 'Task updated successfully.');
        } catch (ModelNotFoundException $e) {
            info(__METHOD__ . $e->getMessage());
            return $this->sendError('Task not found.');
        } catch (\Exception $e) {
            info(__METHOD__ . $e->getMessage());
            return $this->sendError('Internal server error. ', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $task = $this->taskRepository->find($id);

            if ($task->user_id !== Auth::id()) {
                return $this->sendError('Unauthorized access.', [], 403);
            }

            $this->taskRepository->delete($id);

            return $this->sendResponse([], 'Task deleted successfully.');
        } catch (ModelNotFoundException $e) {
            info(__METHOD__ . $e->getMessage());
            return $this->sendError('Task not found.');
        } catch (\Exception $e) {
            info(__METHOD__ . $e->getMessage());
            return $this->sendError('Internal server error. ', 500);
        }
    }
}
