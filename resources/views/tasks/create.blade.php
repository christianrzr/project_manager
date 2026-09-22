@extends('layouts.app')

@section('title', 'Add New Task')
@section('header_title', 'Add New Task')

@section('content')
<div class="view-shell">
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <nav class="breadcrumb-trail" style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; color: var(--text-muted);">
            <a href="{{ route('tasks.index') }}" style="color: var(--text-muted); text-decoration: none;">Tasks</a>
            <span style="color: #cbd5e1;">/</span>
            <span style="color: #1e293b; font-weight: 600;">Create Task</span>
        </nav>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">← Back to Tasks</a>
    </div>

    <div class="card" style="max-width: 760px; padding: 28px;">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="task_name">Task Title <span style="color:red;">*</span></label>
                <input type="text" name="task_name" id="task_name" class="form-control" placeholder="e.g., Complete WST21 Laboratory Exercise 4" value="{{ old('task_name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description & Notes</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Add optional details, notes, or code snippet...">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="category_name">Category</label>
                    <input type="text" name="new_category_name" id="category_name" class="form-control" list="existingCategoriesList" value="{{ old('new_category_name') }}" placeholder="Select or type a category...">
                    <datalist id="existingCategoriesList">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div class="form-group">
                    <label class="form-label" for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Priority Level</label>
                <div class="priority-radios">
                    <label class="priority-radio-label">
                        <input type="radio" name="priority" value="Low" {{ old('priority') === 'Low' ? 'checked' : '' }}>
                        <div class="priority-card p-low">
                            <i data-lucide="arrow-down" aria-hidden="true"></i>
                            <span>Low</span>
                        </div>
                    </label>
                    <label class="priority-radio-label">
                        <input type="radio" name="priority" value="Medium" {{ old('priority', 'Medium') === 'Medium' ? 'checked' : '' }}>
                        <div class="priority-card p-medium">
                            <i data-lucide="minus" aria-hidden="true"></i>
                            <span>Medium</span>
                        </div>
                    </label>
                    <label class="priority-radio-label">
                        <input type="radio" name="priority" value="High" {{ old('priority') === 'High' ? 'checked' : '' }}>
                        <div class="priority-card p-high">
                            <i data-lucide="arrow-up" aria-hidden="true"></i>
                            <span>High</span>
                        </div>
                    </label>
                    <label class="priority-radio-label">
                        <input type="radio" name="priority" value="Urgent" {{ old('priority') === 'Urgent' ? 'checked' : '' }}>
                        <div class="priority-card p-urgent">
                            <i data-lucide="alert-circle" aria-hidden="true"></i>
                            <span>Urgent</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Initial Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="Pending" {{ old('status', 'Pending') === 'Pending' ? 'selected' : '' }}>Pending (To Do)</option>
                    <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Initial Subtasks (Optional)</label>
                <div id="subtaskInputsPage">
                    <input type="text" name="subtasks[]" class="form-control" style="margin-bottom: 8px;" placeholder="Subtask 1">
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addPageSubtask()">+ Add Another Subtask</button>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary">Create Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    function addPageSubtask() {
        const container = document.getElementById('subtaskInputsPage');
        const count = container.querySelectorAll('input').length + 1;
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'subtasks[]';
        input.className = 'form-control';
        input.style.marginBottom = '8px';
        input.placeholder = 'Subtask ' + count;
        container.appendChild(input);
    }
</script>
@endsection
