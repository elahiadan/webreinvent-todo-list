<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('welcome', compact('tasks'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            Task::TITLE => 'required|string|max:255|unique:tasks,title',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'something went wrong',
                'errors' => $validator->errors()
            ]);
        }

        $task = Task::create([
            Task::TITLE => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Task Added successfully.',
        ]);
    }

    public function update(Request $request)
    {

        $task = Task::where('id', $request->id)->first();
        $is_completed = $task->is_completed == 0 ? 1 : 0;
        $task->{Task::IS_COMPLETED} = $is_completed;
        $task->save();


        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Task updated successfully.',
        ]);
    }

    public function destroy(Request $request)
    {
        Task::where('id', $request->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }
}
