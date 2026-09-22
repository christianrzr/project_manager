@extends('layouts.app')

@section('title', 'Add New Task')
@section('header_title', 'Add New Task')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">← Back to Tasks</a>
    </div>

    <div class="card" style="max-width: 700px;">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="task_name">Task Title <span style="color:red;">*</span></label>
                <input type="text" name="task_name" id="task_name" class="form-control" placeholder="e.g., Complete WST21 Laboratory Exercise 4" value="{{ old('task_name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Add detailed notes or requirements...">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">No Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="priority">Priority</label>
                    <select name="priority" id="priority" class="form-control">
                        <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', 'Medium') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="status">Initial Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="Pending" {{ old('status', 'Pending') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Subtasks (Optional)</label>
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
