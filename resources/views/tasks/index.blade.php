@extends('layouts.app')

@section('title', 'All Tasks')
@section('header_title', 'Task Management')

@section('content')
    <!-- Search, Filter & Sort Controls Card -->
    <div class="card" style="padding: 20px;">
        <form action="{{ route('tasks.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1.2fr auto; gap: 12px; align-items: end;">
                
                {{-- 1. Keyword Search --}}
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Search tasks</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by title or description..." value="{{ request('search') }}">
                </div>

                {{-- 2. Status Filter --}}
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Status</label>
                    <select name="status" class="form-control">
                        <option value="all">All Statuses</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                {{-- 3. Priority Filter --}}
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Priority</label>
                    <select name="priority" class="form-control">
                        <option value="all">All Priorities</option>
                        <option value="Urgent" {{ request('priority') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="High" {{ request('priority') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ request('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low" {{ request('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                {{-- 4. Category Filter --}}
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. Sort Dropdown --}}
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Sort By</label>
                    <select name="sort" class="form-control">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="due_date" {{ request('sort') === 'due_date' ? 'selected' : '' }}>Due Date</option>
                        <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>Priority Level</option>
                        <option value="task_name" {{ request('sort') === 'task_name' ? 'selected' : '' }}>Task Title</option>
                    </select>
                </div>

                {{-- Submit & Reset Buttons --}}
                <div style="display: flex; gap: 6px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 16px;">Apply</button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'category_id', 'sort', 'timeframe']))
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary" style="padding: 10px 12px;" title="Reset filters">✕</a>
                    @endif
                </div>
            </div>

            {{-- Quick Filter Pills --}}
            <div style="display: flex; gap: 8px; margin-top: 14px; padding-top: 12px; border-top: 1px solid #f1f5f9; flex-wrap: wrap;">
                <span style="font-size: 0.82rem; font-weight: 600; color: #64748b; align-self: center;">Quick Filters:</span>
                <a href="{{ route('tasks.index') }}" class="badge {{ !request()->has('timeframe') && !request()->has('status') ? 'badge-medium' : 'badge-low' }}" style="padding: 6px 12px; cursor: pointer;">All Tasks</a>
                <a href="{{ route('tasks.index', ['status' => 'Pending']) }}" class="badge {{ request('status') === 'Pending' ? 'badge-medium' : 'badge-low' }}" style="padding: 6px 12px; cursor: pointer;">Pending</a>
                <a href="{{ route('tasks.index', ['status' => 'In Progress']) }}" class="badge {{ request('status') === 'In Progress' ? 'badge-medium' : 'badge-low' }}" style="padding: 6px 12px; cursor: pointer;">In Progress</a>
                <a href="{{ route('tasks.index', ['timeframe' => 'today']) }}" class="badge {{ request('timeframe') === 'today' ? 'badge-medium' : 'badge-low' }}" style="padding: 6px 12px; cursor: pointer;">Due Today</a>
                <a href="{{ route('tasks.index', ['timeframe' => 'overdue']) }}" class="badge {{ request('timeframe') === 'overdue' ? 'badge-urgent' : 'badge-low' }}" style="padding: 6px 12px; cursor: pointer;">Overdue</a>
                <a href="{{ route('tasks.index', ['timeframe' => 'upcoming']) }}" class="badge {{ request('timeframe') === 'upcoming' ? 'badge-medium' : 'badge-low' }}" style="padding: 6px 12px; cursor: pointer;">Upcoming</a>
            </div>
        </form>
    </div>

    <!-- Tasks List Table / Cards -->
    @if($tasks->count() === 0)
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <i data-lucide="search-x" aria-hidden="true" style="color: var(--primary); height: 42px; margin-bottom: 12px; width: 42px;"></i>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #334155;">No matching tasks found</h3>
            <p style="color: #94a3b8; margin: 6px 0 20px;">Try adjusting your search terms or filters, or create a new task.</p>
            <button type="button" class="btn btn-primary" onclick="openCreateTaskModal()"><i data-lucide="plus" aria-hidden="true"></i>Add New Task</button>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($tasks as $task)
                @php
                    $isOverdue = $task->isOverdue();
                    $progress = $task->subtask_progress;
                    $totalSubtasks = $task->subtasks->count();
                @endphp
                <div class="card" style="padding: 18px 22px; margin-bottom: 0; border-top: 3px solid {{ $task->status === 'Completed' ? '#10b981' : ($isOverdue ? '#ef4444' : ($task->category ? $task->category->color : '#c87e61')) }};">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                        
                        {{-- Task Left Info --}}
                        <div style="flex: 1; min-width: 280px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                                {{-- Quick Status Toggle Form --}}
                                <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" style="padding: 3px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; border: 1.5px solid #cbd5e1; cursor: pointer; background: #fff;">
                                        <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>

                                <a href="{{ route('tasks.show', $task) }}" style="font-size: 1.05rem; font-weight: 700; color: #0f172a; {{ $task->status === 'Completed' ? 'text-decoration: line-through; opacity: 0.6;' : '' }}">
                                    {{ $task->task_name }}
                                </a>

                                <span class="badge badge-{{ strtolower($task->priority) }}">{{ $task->priority }}</span>

                                @if($task->category)
                                    <span class="badge" style="background: {{ $task->category->color }}20; color: {{ $task->category->color }};">
                                        {{ $task->category->name }}
                                    </span>
                                @endif
                            </div>

                            @if($task->description)
                                <p style="font-size: 0.88rem; color: #64748b; margin-bottom: 10px; line-height: 1.4;">
                                    {{ Str::limit($task->description, 140) }}
                                </p>
                            @endif

                            {{-- Subtasks Progress Bar if subtasks exist --}}
                            @if($totalSubtasks > 0)
                                <div style="margin: 8px 0 10px; max-width: 320px;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.76rem; font-weight: 600; color: #64748b; margin-bottom: 4px;">
                                        <span>Subtasks ({{ $task->subtasks->where('is_completed', true)->count() }}/{{ $totalSubtasks }})</span>
                                        <span>{{ $progress }}%</span>
                                    </div>
                                    <div class="progress-bar-bg">
                                        <div class="progress-bar-fill" style="width: {{ $progress }}%;"></div>
                                    </div>
                                </div>
                            @endif

                            {{-- Due Date & Meta --}}
                            <div style="display: flex; align-items: center; gap: 14px; font-size: 0.8rem; color: #64748b;">
                                <span>
                                    @if($task->due_date)
                                        <i data-lucide="calendar-days" aria-hidden="true" style="height: 14px; vertical-align: -2px; width: 14px;"></i> Due: <strong style="{{ $isOverdue ? 'color: #ef4444;' : '' }}">{{ $task->due_date->format('M d, Y') }}</strong>
                                        @if($isOverdue)
                                            <span style="color: #ef4444; font-weight: 700;">(Overdue!)</span>
                                        @elseif($task->isDueToday())
                                            <span style="color: #16a34a; font-weight: 700;">(Today)</span>
                                        @endif
                                    @else
                                        <i data-lucide="calendar-off" aria-hidden="true" style="height: 14px; vertical-align: -2px; width: 14px;"></i> No deadline
                                    @endif
                                </span>
                                <span>• Added {{ $task->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Task Action Buttons --}}
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary btn-sm" title="View details & subtasks"><i data-lucide="eye" aria-hidden="true"></i>View</a>

                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary btn-sm" title="Edit task"><i data-lucide="pencil" aria-hidden="true"></i>Edit</a>

                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete task" aria-label="Delete {{ $task->task_name }}"><i data-lucide="trash-2" aria-hidden="true"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 20px;">
            {{ $tasks->links() }}
        </div>
    @endif
@endsection
