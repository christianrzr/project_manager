<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subtask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Dashboard View with comprehensive metrics and activity.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->with(['category', 'subtasks'])->orderBy('due_date', 'asc')->get();

        $totalTasks     = $tasks->count();
        $pendingTasks   = $tasks->where('status', 'Pending')->count();
        $inProgressTasks = $tasks->where('status', 'In Progress')->count();
        $completedTasks = $tasks->where('status', 'Completed')->count();

        $overdueTasks = $tasks->filter(function ($t) {
            return $t->status !== 'Completed' && $t->due_date && $t->due_date->lt(Carbon::today());
        });

        $todayTasks = $tasks->filter(function ($t) {
            return $t->status !== 'Completed' && $t->due_date && $t->due_date->isToday();
        });

        $upcomingTasks = $tasks->filter(function ($t) {
            return $t->status !== 'Completed' && $t->due_date && $t->due_date->gt(Carbon::today());
        });

        // Category breakdown
        $categories = $user->categories()->withCount('tasks')->get();

        // Recent tasks
        $recentTasks = $user->tasks()->with(['category', 'subtasks'])->latest()->take(5)->get();

        return view('tasks.dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'overdueTasks',
            'todayTasks',
            'upcomingTasks',
            'categories',
            'recentTasks'
        ));
    }

    /**
     * List View of tasks with Search, Multi-Filter, and Sorting.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->tasks()->with(['category', 'subtasks']);

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('task_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter by Category
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Timeframe (overdue, today, upcoming)
        if ($request->filled('timeframe')) {
            if ($request->timeframe === 'overdue') {
                $query->where('status', '!=', 'Completed')
                      ->whereNotNull('due_date')
                      ->where('due_date', '<', Carbon::today());
            } elseif ($request->timeframe === 'today') {
                $query->where('status', '!=', 'Completed')
                      ->whereDate('due_date', Carbon::today());
            } elseif ($request->timeframe === 'upcoming') {
                $query->where('status', '!=', 'Completed')
                      ->whereDate('due_date', '>', Carbon::today());
            }
        }

        // Sorting
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = ['task_name', 'due_date', 'priority', 'status', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            if ($sort === 'priority') {
                // Priority ordering: Urgent -> High -> Medium -> Low
                $rawOrder = "CASE priority WHEN 'Urgent' THEN 1 WHEN 'High' THEN 2 WHEN 'Medium' THEN 3 WHEN 'Low' THEN 4 ELSE 5 END";
                $query->orderByRaw($direction === 'asc' ? $rawOrder : "{$rawOrder} DESC");
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $tasks = $query->paginate(15)->withQueryString();
        $categories = $user->categories()->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    /**
     * Kanban Board View (Pending, In Progress, Completed).
     */
    public function board()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->with(['category', 'subtasks'])->orderBy('due_date', 'asc')->get();

        $pendingTasks    = $tasks->where('status', 'Pending');
        $inProgressTasks = $tasks->where('status', 'In Progress');
        $completedTasks  = $tasks->where('status', 'Completed');
        $categories      = $user->categories()->orderBy('name')->get();

        return view('tasks.board', compact('pendingTasks', 'inProgressTasks', 'completedTasks', 'categories'));
    }

    /**
     * Calendar View displaying task deadlines.
     */
    public function calendar(Request $request)
    {
        $user = Auth::user();

        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $currentDate = Carbon::createFromDate($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

        $tasks = $user->tasks()
            ->with('category')
            ->whereNotNull('due_date')
            ->whereYear('due_date', $year)
            ->whereMonth('due_date', $month)
            ->get();

        // Group tasks by day of month
        $tasksByDay = $tasks->groupBy(function ($task) {
            return $task->due_date->day;
        });

        $categories = $user->categories()->orderBy('name')->get();

        return view('tasks.calendar', compact('currentDate', 'prevMonth', 'nextMonth', 'tasksByDay', 'categories'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->orderBy('name')->get();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name'         => 'required|string|max:255',
            'description'       => 'nullable|string',
            'category_id'       => 'nullable',
            'new_category_name' => 'nullable|string|max:100',
            'priority'          => 'required|in:Low,Medium,High,Urgent',
            'status'            => 'required|in:Pending,In Progress,Completed',
            'due_date'          => 'nullable|date',
            'subtasks'          => 'nullable|array',
            'subtasks.*'        => 'nullable|string|max:255',
        ]);

        $categoryId = $validated['category_id'] ?? null;
        if (!empty($validated['new_category_name'])) {
            $catName = trim($validated['new_category_name']);
            $category = Auth::user()->categories()->firstOrCreate(
                ['name' => $catName],
                ['color' => '#c87e61', 'icon' => '📁']
            );
            $categoryId = $category->id;
        } elseif (!empty($categoryId) && !is_numeric($categoryId)) {
            $category = Auth::user()->categories()->firstOrCreate(
                ['name' => trim($categoryId)],
                ['color' => '#c87e61', 'icon' => '📁']
            );
            $categoryId = $category->id;
        }

        $task = Auth::user()->tasks()->create([
            'task_name'    => $validated['task_name'],
            'description'  => $validated['description'] ?? null,
            'category_id'  => $categoryId,
            'priority'     => $validated['priority'],
            'status'       => $validated['status'],
            'due_date'     => $validated['due_date'] ?? null,
            'completed_at' => $validated['status'] === 'Completed' ? now() : null,
        ]);

        // Add subtasks if provided
        if (!empty($validated['subtasks'])) {
            foreach ($validated['subtasks'] as $subtaskTitle) {
                if (!empty(trim($subtaskTitle))) {
                    $task->subtasks()->create(['title' => trim($subtaskTitle)]);
                }
            }
        }

        return redirect()->back()->with('success', 'Task "' . $task->task_name . '" created successfully!');
    }

    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->load(['category', 'subtasks']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Auth::user()->categories()->orderBy('name')->get();
        $task->load('subtasks');

        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'task_name'         => 'required|string|max:255',
            'description'       => 'nullable|string',
            'category_id'       => 'nullable',
            'new_category_name' => 'nullable|string|max:100',
            'priority'          => 'required|in:Low,Medium,High,Urgent',
            'status'            => 'required|in:Pending,In Progress,Completed',
            'due_date'          => 'nullable|date',
        ]);

        $categoryId = $validated['category_id'] ?? $task->category_id;
        if (!empty($validated['new_category_name'])) {
            $catName = trim($validated['new_category_name']);
            $category = Auth::user()->categories()->firstOrCreate(
                ['name' => $catName],
                ['color' => '#c87e61', 'icon' => '📁']
            );
            $categoryId = $category->id;
        } elseif (isset($validated['new_category_name']) && trim($validated['new_category_name']) === '') {
            $categoryId = null;
        }

        $validated['category_id'] = $categoryId;
        unset($validated['new_category_name']);

        if ($validated['status'] === 'Completed' && $task->status !== 'Completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'Completed') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $title = $task->task_name;
        $task->delete();

        return redirect()->back()->with('success', 'Task "' . $title . '" deleted successfully!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update([
            'status'       => $validated['status'],
            'completed_at' => $validated['status'] === 'Completed' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Task status updated to ' . $validated['status'] . '!');
    }
}
