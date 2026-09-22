@extends('layouts.app')

@section('title', $task->task_name)
@section('header_title', 'Task Details')

@section('styles')
<style>
    .task-detail-shell {
        max-width: 1280px;
        margin: 0 auto;
        width: 100%;
    }

    .detail-nav-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .breadcrumb-trail {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.88rem;
        color: var(--text-muted);
    }

    .breadcrumb-trail a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.15s;
    }

    .breadcrumb-trail a:hover {
        color: var(--primary);
    }

    .breadcrumb-separator {
        color: #cbd5e1;
    }

    .detail-action-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .task-detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.85fr) minmax(280px, 0.85fr);
        gap: 24px;
        align-items: start;
    }

    /* Main Workspace Card */
    .task-hero-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 26px 28px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 22px;
    }

    .task-hero-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .task-check-circle {
        appearance: none;
        background: transparent;
        border: 2px solid #94a3b8;
        border-radius: 50%;
        color: transparent;
        cursor: pointer;
        display: inline-flex;
        flex: 0 0 28px;
        height: 28px;
        width: 28px;
        align-items: center;
        justify-content: center;
        margin-top: 3px;
        transition: all 0.18s ease;
    }

    .task-check-circle:hover {
        border-color: var(--primary);
        background: rgba(200, 126, 97, 0.1);
    }

    .task-check-circle.is-done {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
    }

    .task-hero-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
        letter-spacing: -0.02em;
    }

    .task-hero-title.is-done {
        text-decoration: line-through;
        color: #94a3b8;
    }

    .task-hero-meta-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    /* Note & Description Section */
    .task-notes-box {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }

    .notes-box-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 10px;
    }

    .notes-box-content {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 16px;
        font-size: 0.88rem;
        color: #334155;
        line-height: 1.55;
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* Subtasks Checklist Section */
    .subtasks-section {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        box-shadow: var(--shadow-sm);
    }

    .subtasks-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .subtasks-header h3 {
        font-size: 1.05rem;
        font-weight: 750;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .subtask-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 14px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-md);
        margin-bottom: 8px;
        transition: all 0.15s ease;
    }

    .subtask-item-row:hover {
        border-color: #cbd5e1;
        background: #fafafa;
    }

    .subtask-title-text {
        font-size: 0.9rem;
        font-weight: 500;
        color: #1e293b;
        transition: color 0.15s;
    }

    .subtask-title-text.is-done {
        text-decoration: line-through;
        color: #94a3b8;
    }

    .add-subtask-form {
        display: flex;
        gap: 8px;
        margin-top: 14px;
    }

    /* Sidebar Info Cards */
    .sidebar-info-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 20px 24px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 18px;
    }

    .info-card-heading {
        font-size: 0.95rem;
        font-weight: 750;
        color: #1e293b;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-detail-item {
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
    }

    .info-detail-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-detail-label {
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-weight: 700;
        color: #94a3b8;
    }

    .info-detail-value {
        font-weight: 600;
        color: #334155;
    }

    /* Status Segmented Buttons */
    .status-segmented-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: var(--radius-md);
        margin-bottom: 16px;
    }

    .status-segment-btn {
        padding: 6px 4px;
        border: none;
        background: transparent;
        border-radius: var(--radius-sm);
        font-size: 0.76rem;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        text-align: center;
        transition: all 0.15s ease;
    }

    .status-segment-btn.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 860px) {
        .task-detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="task-detail-shell">
    
    <!-- Top Breadcrumbs & Actions -->
    <div class="detail-nav-bar">
        <nav class="breadcrumb-trail" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}"><i data-lucide="layout-dashboard" style="width: 14px; height: 14px; vertical-align: -2px;"></i> Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('tasks.index') }}">Tasks</a>
            <span class="breadcrumb-separator">/</span>
            <span style="color: #1e293b; font-weight: 600;">{{ Str::limit($task->task_name, 35) }}</span>
        </nav>

        <div class="detail-action-group">
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary btn-sm" title="Edit Task">
                <i data-lucide="pencil" aria-hidden="true"></i> Edit
            </a>

            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?');" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" title="Delete Task" aria-label="Delete {{ $task->task_name }}">
                    <i data-lucide="trash-2" aria-hidden="true"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Main 2-Column Detail Grid -->
    <div class="task-detail-grid">
        
        <!-- Left Main Column: Task Details & Subtasks -->
        <div>
            <!-- Hero Card -->
            <div class="task-hero-card">
                <div class="task-hero-header">
                    {{-- Toggle Completed Checkbox --}}
                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $task->status === 'Completed' ? 'Pending' : 'Completed' }}">
                        <button type="submit" class="task-check-circle {{ $task->status === 'Completed' ? 'is-done' : '' }}" title="{{ $task->status === 'Completed' ? 'Reopen task' : 'Mark as complete' }}" aria-label="Toggle Complete">
                            @if($task->status === 'Completed')
                                <i data-lucide="check" style="width: 16px; height: 16px; stroke-width: 2.8;"></i>
                            @endif
                        </button>
                    </form>

                    <div style="flex: 1;">
                        <h1 class="task-hero-title {{ $task->status === 'Completed' ? 'is-done' : '' }}">
                            {{ $task->task_name }}
                        </h1>

                        <div class="task-hero-meta-row">
                            <span class="badge badge-{{ strtolower($task->priority) }}">
                                {{ $task->priority }} Priority
                            </span>

                            <span class="badge badge-{{ strtolower(str_replace(' ', '-', $task->status)) }}">
                                {{ $task->status }}
                            </span>

                            @if($task->category)
                                <span class="badge" style="background: {{ $task->category->color }}20; color: {{ $task->category->color }}; font-weight: 700;">
                                    {{ $task->category->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description & Notes Codeblock -->
                <div class="task-notes-box">
                    <div class="notes-box-label">
                        <i data-lucide="file-text" style="width: 14px; height: 14px;"></i>
                        Description & Notes
                    </div>
                    <div class="notes-box-content">{{ $task->description ?: 'No description provided.' }}</div>
                </div>
            </div>

            <!-- Subtasks Checklist Section -->
            <div class="subtasks-section">
                <div class="subtasks-header">
                    <h3>
                        <i data-lucide="list-checks" style="color: var(--primary); width: 18px; height: 18px;"></i>
                        Subtasks Checklist
                    </h3>
                    <span style="font-size: 0.82rem; font-weight: 750; color: #64748b;">
                        {{ $task->subtasks->where('is_completed', true)->count() }} of {{ $task->subtasks->count() }} completed ({{ $task->subtask_progress }}%)
                    </span>
                </div>

                <div class="progress-bar-bg" style="margin-bottom: 16px;">
                    <div class="progress-bar-fill" style="width: {{ $task->subtask_progress }}%;"></div>
                </div>

                <!-- Subtask Items List -->
                <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px;">
                    @forelse($task->subtasks as $subtask)
                        <div class="subtask-item-row">
                            <form action="{{ route('subtasks.toggle', $subtask) }}" method="POST" style="display: flex; align-items: center; gap: 10px; flex: 1; margin: 0;">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" {{ $subtask->is_completed ? 'checked' : '' }} style="width: 16px; height: 16px; cursor: pointer; accent-color: #10b981;">
                                <span class="subtask-title-text {{ $subtask->is_completed ? 'is-done' : '' }}">
                                    {{ $subtask->title }}
                                </span>
                            </form>

                            <form action="{{ route('subtasks.destroy', $subtask) }}" method="POST" onsubmit="return confirm('Remove subtask?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" style="border: none; padding: 4px; color: #94a3b8;" title="Delete subtask" aria-label="Delete {{ $subtask->title }}">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p style="color: #94a3b8; font-size: 0.86rem; font-style: italic; padding: 8px 0;">No subtasks added yet. Break this task into smaller steps below.</p>
                    @endforelse
                </div>

                <!-- Add Subtask Input Form -->
                <form action="{{ route('subtasks.store', $task) }}" method="POST" class="add-subtask-form">
                    @csrf
                    <input type="text" name="title" class="form-control" placeholder="Add a subtask step and press enter..." required style="padding: 8px 12px; font-size: 0.88rem;">
                    <button type="submit" class="btn btn-primary btn-sm" style="white-space: nowrap; padding: 8px 14px;">
                        <i data-lucide="plus" aria-hidden="true"></i> Add Step
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Status Switcher & Task Metadata -->
        <div>
            <!-- Quick Status Switcher Card -->
            <div class="sidebar-info-card">
                <div class="info-card-heading">
                    <i data-lucide="sliders-horizontal" style="width: 15px; height: 15px;"></i>
                    Task State
                </div>

                <div class="status-segmented-group">
                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" style="margin: 0;">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Pending">
                        <button type="submit" class="status-segment-btn {{ $task->status === 'Pending' ? 'active' : '' }}" style="width: 100%;">To Do</button>
                    </form>
                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" style="margin: 0;">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="In Progress">
                        <button type="submit" class="status-segment-btn {{ $task->status === 'In Progress' ? 'active' : '' }}" style="width: 100%;">In Progress</button>
                    </form>
                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" style="margin: 0;">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Completed">
                        <button type="submit" class="status-segment-btn {{ $task->status === 'Completed' ? 'active' : '' }}" style="width: 100%;">Done</button>
                    </form>
                </div>
            </div>

            <!-- Task Metadata Card -->
            <div class="sidebar-info-card">
                <div class="info-card-heading">
                    <i data-lucide="info" style="width: 15px; height: 15px;"></i>
                    Properties & Timing
                </div>

                <div class="info-detail-item">
                    <span class="info-detail-label">Due Date</span>
                    <span class="info-detail-value">
                        @if($task->due_date)
                            <span style="{{ $task->isOverdue() ? 'color: #ef4444;' : '' }}">
                                <i data-lucide="calendar-days" style="width: 14px; height: 14px; vertical-align: -2px;"></i>
                                {{ $task->due_date->format('M d, Y') }}
                                @if($task->isOverdue())
                                    <strong style="color: #ef4444;">(Overdue)</strong>
                                @elseif($task->isDueToday())
                                    <strong style="color: #16a34a;">(Due Today)</strong>
                                @endif
                            </span>
                        @else
                            <span style="color: #94a3b8;">No deadline assigned</span>
                        @endif
                    </span>
                </div>

                <div class="info-detail-item">
                    <span class="info-detail-label">Category</span>
                    <span class="info-detail-value">
                        @if($task->category)
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $task->category->color }};"></span>
                                {{ $task->category->name }}
                            </span>
                        @else
                            <span style="color: #94a3b8;">Uncategorized</span>
                        @endif
                    </span>
                </div>

                <div class="info-detail-item">
                    <span class="info-detail-label">Date Created</span>
                    <span class="info-detail-value">{{ $task->created_at->format('M d, Y • h:i A') }}</span>
                </div>

                @if($task->completed_at)
                    <div class="info-detail-item">
                        <span class="info-detail-label">Completed On</span>
                        <span class="info-detail-value" style="color: #059669; font-weight: 700;">
                            {{ $task->completed_at->format('M d, Y • h:i A') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
