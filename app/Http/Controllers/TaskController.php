<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return $user->tasks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $user = $request->user();
        $task = new Task();

        $task->content = $request->content;
        $task->user_id = $user->id;
        $task->completed = false;
        $task->date = new Carbon();

        $task->save();

        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task, Request $request)
    {
        $user = $request->user();
        return $user->tasks->find($task->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $user = $request->user();
        $userTask = $user->tasks->find($task->id);

        if ($userTask) {
            $userTask->content = $request->content;
            $userTask->completed = $request->completed;
            $userTask->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task, Request $request)
    {
        $user = $request->user();
        $userTask = $user->tasks->find($task->id);

        if ($userTask) {
            $userTask->delete();
        }
    }
}
