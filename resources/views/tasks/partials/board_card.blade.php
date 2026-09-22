@php
    $isOverdue = $task->isOverdue();
    $subtasksTotal = $task->subtasks->count();
    $subtasksDone = $task->subtasks->where('is_completed', true)->count();
@endphp

<article class="kanban-card">
    <div style="align-items: center; display: flex; gap: 8px; justify-content: space-between;">
        <span class="badge badge-{{ strtolower($task->priority) }}">{{ $task->priority }}</span>
        @if($task->category)<span class="kanban-category"><span style="background: {{ $task->category->color }}; border-radius: 50%; height: 7px; width: 7px;"></span>{{ $task->category->name }}</span>@endif
    </div>
    <div><a href="{{ route('tasks.show', $task) }}" class="kanban-title" style="{{ $task->status === 'Completed' ? 'text-decoration: line-through; opacity: .6;' : '' }}">{{ $task->task_name }}</a>@if($task->description)<p class="kanban-description">{{ Str::limit($task->description, 70) }}</p>@endif</div>
    @if($subtasksTotal > 0)<div class="kanban-progress" style="align-items: center; display: flex; gap: 5px;"><i data-lucide="list-checks" aria-hidden="true" style="height: 14px; width: 14px;"></i>{{ $subtasksDone }}/{{ $subtasksTotal }} subtasks</div>@endif
    <footer class="kanban-footer" style="align-items: center; border-top: 1px solid #f0e7e2; display: flex; justify-content: space-between; padding-top: 10px;">
        <span style="align-items: center; display: inline-flex; gap: 5px; {{ $isOverdue ? 'color: #b42318; font-weight: 700;' : '' }}">@if($task->due_date)<i data-lucide="calendar-days" aria-hidden="true" style="height: 14px; width: 14px;"></i>{{ $task->due_date->format('M j') }}@else No date @endif</span>
        <span style="display: flex; gap: 5px;">@if($prevStatus)<form action="{{ route('tasks.updateStatus', $task) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $prevStatus }}"><button type="submit" class="btn btn-sm btn-secondary" style="padding: 5px 7px;" title="Move back to {{ $prevStatus }}" aria-label="Move {{ $task->task_name }} back"><i data-lucide="arrow-left" aria-hidden="true"></i></button></form>@endif @if($nextStatus)<form action="{{ route('tasks.updateStatus', $task) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $nextStatus }}"><button type="submit" class="btn btn-sm btn-primary" style="padding: 5px 8px;" title="Move to {{ $nextStatus }}">Move forward<i data-lucide="arrow-right" aria-hidden="true"></i></button></form>@endif</span>
    </footer>
</article>
