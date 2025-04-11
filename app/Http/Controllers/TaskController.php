<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TaskImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth'); // Re-added auth middleware for consistency
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $tasks = Task::latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('tasks.create');
    }

    public function store(TaskRequest $request): \Illuminate\Http\RedirectResponse
    {
        Task::create($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Created successfully');
    }

    public function show(Task $task): \Illuminate\Contracts\View\View
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): \Illuminate\Contracts\View\View
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(TaskRequest $request, Task $task): \Illuminate\Http\RedirectResponse
    {
        $task->update($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Updated successfully');
    }

    public function destroy(Task $task): \Illuminate\Http\RedirectResponse
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Deleted successfully');
    }

    public function sections(): \Illuminate\Contracts\View\View
    {
        return view('tasks.sections');
    }

    public function sectionTasks($section)
    {
        $validSections = ['open', 'middle_work', 'close'];
        if (!in_array($section, $validSections)) {
            return redirect()->route('tasks.sections');
        }

        $query = Task::where('section', $section)
            ->where('user_id', Auth::id());

        if (in_array($section, ['open', 'close'])) {
            $query->where(function ($q) {
                $q->where('is_completed', false)
                    ->orWhereDoesntHave('images');
            });
        }

        $tasks = $query->paginate(10);
        return view('tasks.section_tasks', compact('tasks', 'section'));
    }

    public function updateTaskStatus(Request $request, Task $task): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeTask($task);
        $task->update([
            'is_completed' => $request->boolean('is_completed', false),
            'language' => $request->language ?? $task->language
        ]);
        return redirect()->back();
    }

    public function uploadImage(Request $request, Task $task)
    {
        $this->authorizeTask($task);
        $request->validate([
            'image' => 'required|image',
        ]);

        $now = Carbon::now();

        Task::where('id', $task->id)
            ->update([
                'completed_at' => $now
            ]);

        // Refresh task model to reflect DB update
        $task->refresh();

        if (in_array($task->section, ['open', 'close']) && $task->is_completed) {
            try {
                $path = $request->file('image')->store('task_images', 'public');

                TaskImage::create([
                    'task_id' => $task->id,
                    'image_path' => $path
                ]);

                return redirect()->back()->with('success', 'Image uploaded successfully');
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Failed to upload image')
                    ->withInput();
            }
        }

        // Add return for cases where condition isn't met
        return redirect()->back()->with('error', 'Invalid task status or section');
    }

    protected function authorizeTask(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to perform this action.');
        }
    }
}
