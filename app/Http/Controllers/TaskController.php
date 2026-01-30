<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    //
    public function index() {
        $tasks = Task::latest()->get();
        return view('tasks', compact('tasks'));
    }

    public function store(Request $request) {
        Task::create([
            'title' => request('title'),
            'description' => request('description')
        ]);
        return redirect('/');
    }

    public function destroy(Task $task) {
        $task->delete();
        return redirect('/');
    }
}