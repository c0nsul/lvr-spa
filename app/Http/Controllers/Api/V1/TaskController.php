<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskStoreRequest $request
     * @return TaskResource
     */
    public function store(TaskStoreRequest $request): TaskResource
    {
        $newTask = Task::create($request->validated());
        return new TaskResource($newTask);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TasStoreRequest $request
     * @param Task $task
     * @return TaskResource
     */
    public function update(TaskUpdateRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return Response
     */
    public function destroy(Task $task): Response
    {
        $task->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
