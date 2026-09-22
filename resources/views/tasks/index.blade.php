@extends('layouts.app')

@section('title', 'All Tasks')
@section('header_title', 'Tasks')

@section('styles')
<style>
    .tasks-header-bar {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
    }

    .filter-search-row {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 240px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 9px 14px 9px 36px;
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: var(--text-sm);
        background: #fafafa;
        color: var(--text-main);
        transition: all 0.2s;
    }

    .search-box input:focus {
        background: #ffffff;
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(200, 126, 97, 0.15);
    }

    .search-icon {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        width: 16px;
        height: 16px;
    }

    .filter-select {
        padding: 9px 12px;
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: var(--text-sm);
        font-weight: var(--weight-semibold);
        color: #475569;
        background: #ffffff;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .filter-select:focus {
        border-color: var(--primary);
        outline: none;
    }

    .quick-filter-pills {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .pill-link {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: var(--text-xs);
        font-weight: var(--weight-semibold);
        color: #64748b;
        background: #f1f5f9;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
    }

    .pill-link:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .pill-link.active {
        background: var(--primary);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(200, 126, 97, 0.3);
    }

    .pill-link.active-urgent {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    /* Compact Task Card */
    .task-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 14px 18px;
        margin-bottom: 10px;
        transition: all 0.18s ease;
        position: relative;
    }

    .task-card:hover {
        border-color: #cbd5e1;
        box-shadow: var(--shadow-sm);
        transform: translateY(-1px);
    }

    .task-card.is-urgent {
        border-color: #fca5a5;
        background: #fffafa;
    }

    .task-card.is-completed {
        opacity: 0.75;
    }

    .task-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .task-card-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    .task-title-link {
        font-size: var(--text-base);
        font-weight: var(--weight-bold);
        letter-spacing: -0.012em;
        line-height: 1.35;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .task-title-link.is-done {
        text-decoration: line-through;
        color: #94a3b8;
    }

    .task-description {
        color: var(--text-muted);
        font-size: var(--text-sm);
        margin: 8px 0;
        line-height: 1.55;
        max-width: 72ch;
    }

    .task-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: var(--text-xs);
        line-height: 1.4;
        color: #64748b;
        margin-top: 6px;
    }
</style>
@endsection

@section('content')
<div class="view-shell">
    <!-- Streamlined Filter & Search Controls -->
    <div class="tasks-header-bar">
        <form action="{{ route('tasks.index') }}" method="GET" id="taskFiltersForm">
            <div class="filter-search-row">
                {{-- Search Input --}}
                <div class="search-box">
                    <i data-lucide="search" class="search-icon" aria-hidden="true"></i>
                    <input type="text" name="search" placeholder="Search tasks by title or description..." value="{{ request('search') }}" onchange="document.getElementById('taskFiltersForm').submit()">
                </div>

                {{-- Status Filter --}}
                <select name="status" class="filter-select" onchange="document.getElementById('taskFiltersForm').submit()">
                    <option value="all">All Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>To Do (Pending)</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>

                {{-- Priority Filter --}}
                <select name="priority" class="filter-select" onchange="document.getElementById('taskFiltersForm').submit()">
                    <option value="all">All Priorities</option>
                    <option value="Urgent" {{ request('priority') === 'Urgent' ? 'selected' : '' }}>🔥 Urgent</option>
                    <option value="High" {{ request('priority') === 'High' ? 'selected' : '' }}>▲ High</option>
                    <option value="Medium" {{ request('priority') === 'Medium' ? 'selected' : '' }}>■ Medium</option>
                    <option value="Low" {{ request('priority') === 'Low' ? 'selected' : '' }}>▼ Low</option>
                </select>

                {{-- Category Filter --}}
                <select name="category_id" class="filter-select" onchange="document.getElementById('taskFiltersForm').submit()">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Sort Filter --}}
                <select name="sort" class="filter-select" onchange="document.getElementById('taskFiltersForm').submit()">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Newest Created</option>
                    <option value="due_date" {{ request('sort') === 'due_date' ? 'selected' : '' }}>Due Date</option>
                    <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>Priority Level</option>
                    <option value="task_name" {{ request('sort') === 'task_name' ? 'selected' : '' }}>Title (A-Z)</option>
                </select>

                @if(request()->hasAny(['search', 'status', 'priority', 'category_id', 'sort', 'timeframe']))
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm" title="Reset all filters" style="padding: 8px 12px;">Reset ✕</a>
                @endif
            </div>

            {{-- Quick Filter Pills --}}
            <div class="quick-filter-pills">
                <a href="{{ route('tasks.index') }}" class="pill-link {{ !request()->has('timeframe') && !request()->has('status') && !request()->has('priority') ? 'active' : '' }}">All ({{ $tasks->total() }})</a>
                <a href="{{ route('tasks.index', ['status' => 'Pending']) }}" class="pill-link {{ request('status') === 'Pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ route('tasks.index', ['status' => 'In Progress']) }}" class="pill-link {{ request('status') === 'In Progress' ? 'active' : '' }}">In Progress</a>
                <a href="{{ route('tasks.index', ['timeframe' => 'today']) }}" class="pill-link {{ request('timeframe') === 'today' ? 'active' : '' }}">Due Today</a>
                <a href="{{ route('tasks.index', ['priority' => 'Urgent']) }}" class="pill-link {{ request('priority') === 'Urgent' ? 'active-urgent' : '' }}">🔥 Urgent</a>
                <a href="{{ route('tasks.index', ['timeframe' => 'overdue']) }}" class="pill-link {{ request('timeframe') === 'overdue' ? 'active-urgent' : '' }}">Overdue</a>
                <a href="{{ route('tasks.index', ['status' => 'Completed']) }}" class="pill-link {{ request('status') === 'Completed' ? 'active' : '' }}">Completed</a>
            </div>
        </form>
    </div>

    <!-- Compact Tasks List -->
    @if($tasks->count() === 0)
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <i data-lucide="inbox" aria-hidden="true" style="color: var(--primary); height: 42px; width: 42px; margin-bottom: 12px;"></i>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: #334155;">No tasks found</h3>
            <p style="color: #94a3b8; margin: 6px 0 20px; font-size: 0.9rem;">No tasks match your current filter criteria.</p>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">Clear Filters</a>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @foreach($tasks as $task)
                @php
                    $isOverdue = $task->isOverdue();
                    $progress = $task->subtask_progress;
                    $totalSubtasks = $task->subtasks->count();
                    $isUrgent = $task->priority === 'Urgent' && $task->status !== 'Completed';
                @endphp
                <div class="task-card {{ $isUrgent ? 'is-urgent' : '' }} {{ $task->status === 'Completed' ? 'is-completed' : '' }}">
                    
                    {{-- Header Row --}}
                    <div class="task-card-header">
                        <div class="task-card-title-group">
                            {{-- Quick Status Toggle Form --}}
                            <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="padding: 3px 6px; border-radius: 6px; font-size: 0.74rem; font-weight: 700; border: 1.5px solid #cbd5e1; cursor: pointer; background: #fff;">
                                    <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>

                            <a href="{{ route('tasks.show', $task) }}" class="task-title-link {{ $task->status === 'Completed' ? 'is-done' : '' }}" title="{{ $task->task_name }}">
                                {{ $task->task_name }}
                            </a>

                            <span class="badge badge-{{ strtolower($task->priority) }}">{{ $task->priority }}</span>

                            @if($task->category)
                                <span class="badge" style="background: {{ $task->category->color }}20; color: {{ $task->category->color }}; font-size: 0.72rem;">
                                    {{ $task->category->name }}
                                </span>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <a href="{{ route('tasks.show', $task) }}" class="btn-icon" title="View details"><i data-lucide="eye" style="width: 14px; height: 14px;"></i></a>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn-icon" title="Edit task"><i data-lucide="pencil" style="width: 14px; height: 14px;"></i></a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" title="Delete task" style="color: #ef4444;"><i data-lucide="trash-2" style="width: 14px; height: 14px;"></i></button>
                            </form>
                        </div>
                    </div>

                    {{-- Description Codeblock --}}
                    @if($task->description)
                        <p class="task-description">{{ Str::limit($task->description, 180) }}</p>
                    @endif

                    {{-- Footer Row with Subtasks & Due Date --}}
                    <div class="task-card-footer">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            @if($task->due_date)
                                <span>
                                    <i data-lucide="calendar-days" aria-hidden="true" style="width: 13px; height: 13px; vertical-align: -2px;"></i>
                                    Due: <strong style="{{ $isOverdue ? 'color: #ef4444;' : '' }}">{{ $task->due_date->format('M d, Y') }}</strong>
                                    @if($isOverdue)
                                        <span style="color: #ef4444; font-weight: 800;">(Overdue)</span>
                                    @elseif($task->isDueToday())
                                        <span style="color: #16a34a; font-weight: 800;">(Today)</span>
                                    @endif
                                </span>
                            @else
                                <span><i data-lucide="calendar-off" aria-hidden="true" style="width: 13px; height: 13px; vertical-align: -2px;"></i> No deadline</span>
                            @endif

                            @if($totalSubtasks > 0)
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <i data-lucide="list-checks" style="width: 13px; height: 13px;"></i>
                                    {{ $task->subtasks->where('is_completed', true)->count() }}/{{ $totalSubtasks }} subtasks ({{ $progress }}%)
                                </span>
                            @endif
                        </div>

                        <span>Added {{ $task->created_at->diffForHumans() }}</span>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 18px;">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
@endsection
