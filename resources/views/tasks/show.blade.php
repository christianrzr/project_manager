@extends('layouts.app')

@section('title', $task->task_name)
@section('header_title', 'Task Details')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">← Back to Tasks</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        
        <!-- Left Column: Task Overview & Subtasks -->
        <div>
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <span class="badge badge-{{ strtolower($task->priority) }}">{{ $task->priority }} Priority</span>
                            <span class="badge badge-{{ strtolower(str_replace(' ', '-', $task->status)) }}">{{ $task->status }}</span>
                            @if($task->category)
                                <span class="badge" style="background: {{ $task->category->color }}20; color: {{ $task->category->color }};">
                                    {{ $task->category->name }}
                                </span>
                            @endif
                        </div>
                        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.3;">
                            {{ $task->task_name }}
                        </h1>
                    </div>
                </div>

                <!-- Description -->
                <div style="margin: 20px 0;">
                    <label class="form-label" style="font-size: 0.8rem; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.5px;">Description</label>
                    <div style="padding: 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.95rem; line-height: 1.6; white-space: pre-wrap; color: #334155;">{{ $task->description ?: 'No additional description provided.' }}</div>
                </div>

                <!-- Subtasks Checklist -->
                <div style="margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                        <h3 style="font-size: 1.1rem; font-weight: 700;">
                            Subtasks checklist ({{ $task->subtasks->where('is_completed', true)->count() }}/{{ $task->subtasks->count() }})
                        </h3>
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">
                            {{ $task->subtask_progress }}% Completed
                        </span>
                    </div>

                    <div class="progress-bar-bg" style="margin-bottom: 20px;">
                        <div class="progress-bar-fill" style="width: {{ $task->subtask_progress }}%;"></div>
                    </div>

                    <!-- Subtask List -->
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        @forelse($task->subtasks as $subtask)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-radius: 8px; background: #ffffff; border: 1px solid #e2e8f0;">
                                <form action="{{ route('subtasks.toggle', $subtask) }}" method="POST" style="display: flex; align-items: center; gap: 10px; flex: 1;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox" onchange="this.form.submit()" {{ $subtask->is_completed ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                                    <span style="{{ $subtask->is_completed ? 'text-decoration: line-through; color: #94a3b8;' : 'color: #1e293b;' }}; font-size: 0.92rem; font-weight: 500;">
                                        {{ $subtask->title }}
                                    </span>
                                </form>

                                <form action="{{ route('subtasks.destroy', $subtask) }}" method="POST" onsubmit="return confirm('Remove this subtask?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" style="border: none; color: #94a3b8;" title="Delete subtask" aria-label="Delete {{ $subtask->title }}"><i data-lucide="trash-2" aria-hidden="true"></i></button>
                                </form>
                            </div>
                        @empty
                            <p style="color: #94a3b8; font-size: 0.88rem; font-style: italic;">No subtasks yet. Add smaller steps below to track progress!</p>
                        @endforelse
                    </div>

                    <!-- Add Subtask Form -->
                    <form action="{{ route('subtasks.store', $task) }}" method="POST" style="display: flex; gap: 8px;">
                        @csrf
                        <input type="text" name="title" class="form-control" placeholder="Add a subtask step..." required>
                        <button type="submit" class="btn btn-primary" style="white-space: nowrap;"><i data-lucide="plus" aria-hidden="true"></i>Add Subtask</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Meta & Actions -->
        <div>
            <div class="card">
                <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 16px;">Task Info</h3>

                <div style="display: flex; flex-direction: column; gap: 14px; font-size: 0.88rem;">
                    <div>
                        <span style="color: #64748b; display: block; font-size: 0.78rem; text-transform: uppercase;">Due Date</span>
                        @if($task->due_date)
                            <strong style="{{ $task->isOverdue() ? 'color: #ef4444;' : '' }}">
                                <i data-lucide="calendar-days" aria-hidden="true" style="height: 15px; vertical-align: -3px; width: 15px;"></i> {{ $task->due_date->format('F j, Y') }}
                                @if($task->isOverdue())
                                    <span style="color: #ef4444;">(Overdue)</span>
                                @endif
                            </strong>
                        @else
                            <span style="color: #94a3b8;">No deadline set</span>
                        @endif
                    </div>

                    <div>
                        <span style="color: #64748b; display: block; font-size: 0.78rem; text-transform: uppercase;">Category</span>
                        @if($task->category)
                            <span>{{ $task->category->name }}</span>
                        @else
                            <span style="color: #94a3b8;">Uncategorized</span>
                        @endif
                    </div>

                    <div>
                        <span style="color: #64748b; display: block; font-size: 0.78rem; text-transform: uppercase;">Date Created</span>
                        <span>{{ $task->created_at->format('M d, Y h:i A') }}</span>
                    </div>

                    @if($task->completed_at)
                        <div>
                            <span style="color: #64748b; display: block; font-size: 0.78rem; text-transform: uppercase;">Completed At</span>
                            <span style="color: #059669; font-weight: 600;">{{ $task->completed_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 8px;">
                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @if($task->status !== 'Completed')
                            <input type="hidden" name="status" value="Completed">
                            <button type="submit" class="btn btn-success" style="width: 100%;"><i data-lucide="check" aria-hidden="true"></i>Mark as Completed</button>
                        @else
                            <input type="hidden" name="status" value="Pending">
                            <button type="submit" class="btn btn-warning" style="width: 100%;"><i data-lucide="rotate-ccw" aria-hidden="true"></i>Reopen Task</button>
                        @endif
                    </form>

                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary" style="width: 100%;"><i data-lucide="pencil" aria-hidden="true"></i>Edit Task</a>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: 100%;"><i data-lucide="trash-2" aria-hidden="true"></i>Delete Task</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
