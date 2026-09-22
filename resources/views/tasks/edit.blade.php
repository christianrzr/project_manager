@extends('layouts.app')

@section('title', 'Edit Task')
@section('header_title', 'Edit Task')

@section('content')
<div class="view-shell">
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <nav class="breadcrumb-trail" style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; color: var(--text-muted);">
            <a href="{{ route('tasks.index') }}" style="color: var(--text-muted); text-decoration: none;">Tasks</a>
            <span style="color: #cbd5e1;">/</span>
            <a href="{{ route('tasks.show', $task) }}" style="color: var(--text-muted); text-decoration: none;">{{ Str::limit($task->task_name, 30) }}</a>
            <span style="color: #cbd5e1;">/</span>
            <span style="color: #1e293b; font-weight: 600;">Edit</span>
        </nav>
        <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary btn-sm">← Back to Details</a>
    </div>

    <div class="card" style="max-width: 760px; padding: 28px;">
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="task_name">Task Title <span style="color:red;">*</span></label>
                <input type="text" name="task_name" id="task_name" class="form-control" value="{{ old('task_name', $task->task_name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description & Notes</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Add optional details, notes, or code snippet...">{{ old('description', $task->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="category_name">Category</label>
                    <input type="text" name="new_category_name" id="category_name" class="form-control" list="existingCategoriesList" value="{{ old('new_category_name', $task->category ? $task->category->name : '') }}" placeholder="Select or type a category...">
                    <datalist id="existingCategoriesList">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div class="form-group">
                    <label class="form-label" for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Priority Level</label>
                <div class="priority-radios">
                    <label class="priority-radio-label">
                        <input type="radio" name="priority" value="Low" {{ old('priority', $task->priority) === 'Low' ? 'checked' : '' }}>
                        <div class="priority-card p-low">
                            <i data-lucide="arrow-down" aria-hidden="true"></i>
                            <span>Low</span>
                        </div>
                    </label>
                    <label class="priority-radio-label">
                        <input type="radio" name="priority" value="Medium" {{ old('priority', $task->priority) === 'Medium' ? 'checked' : '' }}>
                        <div class="priority-card p-medium">
                            <i data-lucide="minus" aria-hidden="true"></i>
                            <span>Medium</span>
                        </div>
                    </label>
                    <label class="priority-radio-label">
                        <input type="radio" name="priority" value="High" {{ old('priority', $task->priority) === 'High' ? 'checked' : '' }}>
                        <div class="priority-card p-high">
                            <i data-lucide="arrow-up" aria-hidden="true"></i>
                            <span>High</span>
                        </div>
                    </label>
                    <label class="priority-radio-label">
                        <input type="radio" name="priority" value="Urgent" {{ old('priority', $task->priority) === 'Urgent' ? 'checked' : '' }}>
                        <div class="priority-card p-urgent">
                            <i data-lucide="alert-circle" aria-hidden="true"></i>
                            <span>Urgent</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Task Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="Pending" {{ old('status', $task->status) === 'Pending' ? 'selected' : '' }}>Pending (To Do)</option>
                    <option value="In Progress" {{ old('status', $task->status) === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ old('status', $task->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
