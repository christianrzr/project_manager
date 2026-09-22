<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->subtasks()->create([
            'title'        => $validated['title'],
            'is_completed' => false,
        ]);

        return redirect()->back()->with('success', 'Subtask added!');
    }

    public function toggle(Subtask $subtask)
    {
        if ($subtask->task->user_id !== Auth::id()) {
            abort(403);
        }

        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        return redirect()->back()->with('success', 'Subtask updated!');
    }

    public function destroy(Subtask $subtask)
    {
        if ($subtask->task->user_id !== Auth::id()) {
            abort(403);
        }

        $subtask->delete();

        return redirect()->back()->with('success', 'Subtask removed!');
    }
}
