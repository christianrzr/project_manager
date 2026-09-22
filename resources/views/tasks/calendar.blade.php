@extends('layouts.app')

@section('title', 'Calendar')
@section('header_title', 'Task Calendar')

@section('styles')
<style>
    .calendar-container {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .calendar-header {
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border-color);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .day-header {
        padding: 12px;
        text-align: center;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
    }

    .calendar-cell {
        min-height: 110px;
        padding: 8px;
        border-right: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        background: #ffffff;
        display: flex;
        flex-direction: column;
        gap: 4px;
        transition: background 0.15s;
    }

    .calendar-cell:hover {
        background: #f8fafc;
    }

    .calendar-cell.other-month {
        background: #fafafa;
        opacity: 0.45;
    }

    .calendar-cell.is-today {
        background: #eff6ff;
    }

    .calendar-cell.has-urgent {
        background: #fff5f5 !important;
        border-color: #fca5a5 !important;
    }

    .calendar-cell.has-urgent .cell-date {
        color: #b91c1c;
    }

    .cell-date {
        font-size: 0.85rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
    }

    .cell-date.today-number {
        color: var(--primary);
    }

    .calendar-task-item {
        padding: 4px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        transition: transform 0.15s;
    }

    .calendar-task-item:hover {
        transform: scale(1.02);
    }
</style>
@endsection

@section('content')
<div class="view-shell">
    <div class="calendar-container">
        <!-- Calendar Controls -->
        <div class="calendar-header">
            <div style="display: flex; align-items: center; gap: 14px;">
                <h2 style="font-size: 1.3rem; font-weight: 800; color: #0f172a;">
                    {{ $currentDate->format('F Y') }}
                </h2>
                <a href="{{ route('calendar', ['month' => now()->month, 'year' => now()->year]) }}" class="btn btn-secondary btn-sm">
                    Today
                </a>
            </div>

            <div style="display: flex; gap: 8px;">
                <a href="{{ route('calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="btn btn-secondary btn-sm">
                    ← {{ $prevMonth->format('M') }}
                </a>
                <a href="{{ route('calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="btn btn-secondary btn-sm">
                    {{ $nextMonth->format('M') }} →
                </a>
            </div>
        </div>

        <!-- Day of Week Headers -->
        <div class="calendar-grid">
            <div class="day-header">Sun</div>
            <div class="day-header">Mon</div>
            <div class="day-header">Tue</div>
            <div class="day-header">Wed</div>
            <div class="day-header">Thu</div>
            <div class="day-header">Fri</div>
            <div class="day-header">Sat</div>
        </div>

        <!-- Days Grid -->
        @php
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth   = $currentDate->copy()->endOfMonth();
            $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
            $daysInMonth  = $currentDate->daysInMonth;
        @endphp

        <div class="calendar-grid">
            {{-- Empty cells before first day of month --}}
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div class="calendar-cell other-month"></div>
            @endfor

            {{-- Month days --}}
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $thisDate = $currentDate->copy()->day($day);
                    $isToday = $thisDate->isToday();
                    $dayTasks = $tasksByDay->get($day, collect());
                    $hasUrgent = $dayTasks->contains(function ($t) {
                        return $t->priority === 'Urgent' && $t->status !== 'Completed';
                    });
                @endphp
                <div class="calendar-cell {{ $isToday ? 'is-today' : '' }} {{ $hasUrgent ? 'has-urgent' : '' }}">
                    <div class="cell-date {{ $isToday ? 'today-number' : '' }}">
                        <span style="display: flex; align-items: center; gap: 4px;">
                            {{ $day }}
                            @if($hasUrgent)
                                <span style="font-size: 0.65rem; background: #ef4444; color: #fff; padding: 1px 5px; border-radius: 4px; font-weight: 800;">URGENT</span>
                            @endif
                        </span>
                        @if($isToday)
                            <span style="font-size: 0.7rem; background: #3b82f6; color: #fff; padding: 1px 5px; border-radius: 4px;">Today</span>
                        @endif
                    </div>

                    @foreach($dayTasks as $task)
                        @php
                            $isTaskUrgent = $task->priority === 'Urgent' && $task->status !== 'Completed';
                            $bg = $task->status === 'Completed' ? '#d1fae5' : ($isTaskUrgent ? '#fee2e2' : '#e0e7ff');
                            $color = $task->status === 'Completed' ? '#065f46' : ($isTaskUrgent ? '#991b1b' : '#3730a3');
                        @endphp
                        <a href="{{ route('tasks.show', $task) }}" class="calendar-task-item" style="background: {{ $bg }}; color: {{ $color }}; {{ $isTaskUrgent ? 'border: 1px solid #f87171; font-weight: 700;' : '' }}" title="{{ $task->task_name }}">
                            {{ $task->status === 'Completed' ? '✓' : ($isTaskUrgent ? '⚠' : '•') }} {{ $task->task_name }}
                        </a>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>
</div>
@endsection
