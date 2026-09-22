@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')
@section('hide_topbar_action', 'true')

@section('styles')
<style>
    .dashboard-shell {
        max-width: 1280px;
        margin: 0 auto;
        --dashboard-space: clamp(20px, 3vw, 40px);
    }

    .dashboard-actions {
        align-items: center;
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }

    .dashboard-hero {
        background: #f3e4dd;
        border-bottom: 3px solid #c87e61;
        display: grid;
        gap: var(--dashboard-space);
        grid-template-columns: minmax(130px, 300px) minmax(0, 1fr);
        margin: clamp(64px, 8vw, 65px) 0 var(--dashboard-space);
        min-height: 232px;
        overflow: visible;
        padding: clamp(20px, 3vw, 36px);
        position: relative;
    }

    .dashboard-page-date {
        color: #786a63;
        font-size: var(--text-sm);
        font-weight: var(--weight-semibold);
        letter-spacing: .01em;
        line-height: 1.4;
    }

    .dashboard-intro {
        display: flex;
        align-items: end;
        grid-column: 1 / -1;
        justify-content: space-between;
        gap: 20px;
        padding: 0 0 0 clamp(230px, 10vw, 300px);
        position: relative;
        z-index: 1;
    }

    .dashboard-hero-art {
        align-self: end;
        bottom: -12px;
        height: 450px;
        left: -14px;
        margin: 0;
        object-fit: contain;
        object-position: center bottom;
        pointer-events: none;
        position: absolute;
        transform: none;
        width: min(300px, 25vw);
        z-index: 0;
    }

    .dashboard-brand {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 8px;
    }

    .dashboard-brand img {
        height: 27px;
        width: 27px;
        object-fit: contain;
    }

    .dashboard-date {
        color: #64748b;
        font-size: var(--text-sm);
        font-weight: var(--weight-semibold);
    }

    .dashboard-intro h2 {
        color: #442f28;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: var(--weight-bold);
        letter-spacing: -.04em;
        line-height: 1.12;
    }

    .dashboard-intro p {
        color: #526176;
        font-size: var(--text-base);
        line-height: 1.55;
        margin-top: 9px;
        max-width: 58ch;
    }

    .dashboard-summary {
        border-radius: 14px;
        display: grid;
        grid-template-columns: minmax(220px, 1.45fr) repeat(3, minmax(120px, 1fr));
        isolation: isolate;
        margin: 28px 0 46px;
        overflow: hidden;
        position: relative;
    }

    .dashboard-summary::before,
    .dashboard-summary::after {
        content: '';
        inset-block: 0;
        pointer-events: none;
        position: absolute;
        width: 28px;
        z-index: 2;
    }

    .dashboard-summary::before {
        background: linear-gradient(90deg, rgba(255, 253, 252, .9), rgba(255, 253, 252, 0));
        left: 0;
    }

    .dashboard-summary::after {
        background: linear-gradient(270deg, rgba(255, 253, 252, .9), rgba(255, 253, 252, 0));
        right: 0;
    }

    .summary-item {
        min-width: 0;
        padding: 18px 24px 19px;
        position: relative;
    }

    .summary-item+.summary-item::before {
        background: linear-gradient(to bottom, transparent, #ddcec6 20%, #ddcec6 80%, transparent);
        content: '';
        inset: 10px auto 10px 0;
        position: absolute;
        width: 1px;
    }

    .summary-item.summary-primary {
        padding-left: 18px;
    }

    .summary-item a {
        display: block;
    }

    .summary-number {
        color: #172033;
        display: block;
        font-size: 1.8rem;
        font-weight: var(--weight-bold);
        letter-spacing: -.05em;
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }

    .summary-label {
        color: #64748b;
        display: block;
        font-size: var(--text-xs);
        font-weight: var(--weight-semibold);
        letter-spacing: .06em;
        line-height: 1.35;
        margin-bottom: 5px;
        margin-top: 0;
        text-transform: uppercase;
    }

    .summary-primary .summary-number {
        font-size: 2.35rem;
    }

    .summary-context {
        color: var(--text-muted);
        display: block;
        font-size: var(--text-sm);
        line-height: 1.45;
        margin-top: 5px;
    }

    .summary-item.is-alert .summary-number,
    .summary-item.is-alert .summary-label {
        color: #ae5340;
    }

    .summary-item.is-today .summary-number,
    .summary-item.is-today .summary-label {
        color: #a75f45;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(250px, .75fr);
        gap: clamp(36px, 5vw, 68px);
        align-items: start;
    }

    .dashboard-aside {
        border-left: 1px solid #dce3ea;
        padding-left: clamp(24px, 3vw, 40px);
    }

    .dashboard-section+.dashboard-section {
        margin-top: 44px;
    }

    /* Keep secondary dashboard content out of the initial rendering path. */
    .dashboard-section:not(:first-child) {
        content-visibility: auto;
        contain-intrinsic-size: auto 420px;
    }

    .section-heading {
        align-items: baseline;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 13px;
    }

    .section-heading h3 {
        color: #172033;
        font-size: var(--text-lg);
        font-weight: var(--weight-bold);
        letter-spacing: -.018em;
        line-height: 1.25;
    }

    .section-link {
        align-items: center;
        color: #a75f45;
        display: inline-flex;
        font-size: var(--text-sm);
        font-weight: var(--weight-semibold);
        gap: 4px;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .section-link svg,
    .row-action svg {
        height: 14px;
        width: 14px;
    }

    .focus-list {
        border-top: 1px solid #b8a79d;
    }

    .focus-row,
    .task-row {
        align-items: center;
        border-bottom: 1px solid #dce3ea;
        display: flex;
        gap: 13px;
        padding: 15px 4px;
        transition: background-color .18s ease;
    }

    .focus-row {
        padding-left: 8px;
    }

    .focus-row.is-overdue {
        background: #fdf3ef;
        border-bottom-color: #e9c9bd;
    }

    .focus-row:hover,
    .task-row:hover {
        background: #f8f3f0;
    }

    .status-button {
        align-items: center;
        appearance: none;
        background: transparent;
        border: 1.5px solid #9aa8b8;
        border-radius: 50%;
        color: transparent;
        cursor: pointer;
        display: inline-flex;
        flex: 0 0 40px;
        height: 40px;
        justify-content: center;
        padding: 0;
        transition: border-color .16s, background-color .16s;
        width: 40px;
    }

    .status-button:hover,
    .status-button:focus-visible {
        border-color: #a75f45;
        outline: 3px solid rgba(200, 126, 97, .2);
        outline-offset: 2px;
    }

    .status-button.is-complete {
        background: #1e7a53;
        border-color: #1e7a53;
        color: #fff;
    }

    .status-button svg {
        height: 13px;
        stroke-width: 2.4;
        width: 13px;
    }

    .task-main {
        flex: 1;
        min-width: 0;
    }

    .task-title {
        color: #172033;
        font-size: var(--text-base);
        font-weight: var(--weight-semibold);
        letter-spacing: -.012em;
        line-height: 1.35;
    }

    .task-title.is-complete {
        color: #7b8797;
        text-decoration: line-through;
    }

    .task-meta {
        align-items: center;
        color: #64748b;
        display: flex;
        flex-wrap: wrap;
        font-size: var(--text-xs);
        gap: 7px;
        margin-top: 5px;
    }

    .task-meta .category {
        color: #445165;
        font-weight: 650;
    }

    .due-copy {
        color: #b42318;
        font-weight: 750;
    }

    .due-copy.is-today {
        color: #a75f45;
    }

    .priority {
        color: #6b5b52;
        flex: 0 0 auto;
        font-size: .7rem;
        font-weight: var(--weight-semibold);
        letter-spacing: .06em;
        padding: 0;
        text-transform: uppercase;
    }

    .priority.urgent {
        color: #b42318;
    }

    .priority.high {
        color: #9d530c;
    }

    .empty-focus {
        border-top: 1px solid #b8a79d;
        color: #526176;
        padding: 20px 4px;
    }

    .workload {
        border-radius: 12px;
        overflow: hidden;
        padding: 3px 16px;
    }

    .workload-row {
        align-items: baseline;
        border-bottom: 1px solid #dce3ea;
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        transition: color .18s ease;
    }

    .workload-row span {
        color: #526176;
        font-size: .88rem;
    }

    .workload-row strong {
        color: #172033;
        font-size: var(--text-lg);
        font-variant-numeric: tabular-nums;
    }

    .row-action {
        align-items: center;
        color: var(--text-muted);
        display: inline-flex;
        gap: 8px;
    }

    .workload-row .progress {
        color: #a75f45;
    }

    .category-list {
        border-top: 1px solid #b8a79d;
    }

    .category-row {
        align-items: center;
        border-bottom: 1px solid #dce3ea;
        display: flex;
        gap: 10px;
        justify-content: space-between;
        padding: 12px 0;
        transition: color .18s ease;
    }

    .category-name {
        color: #263247;
        font-size: .88rem;
        font-weight: 680;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .category-count {
        color: #64748b;
        font-size: .77rem;
        font-variant-numeric: tabular-nums;
    }

    .category-dot {
        background: var(--category-color);
        border-radius: 50%;
        flex: 0 0 8px;
        height: 8px;
        width: 8px;
    }

    .category-label {
        align-items: center;
        display: flex;
        gap: 8px;
        min-width: 0;
    }

    .new-task-link {
        align-items: center;
        color: #a75f45;
        display: inline-flex;
        font-size: var(--text-sm);
        font-weight: var(--weight-semibold);
        gap: 4px;
        margin-top: 15px;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .new-task-link svg {
        height: 14px;
        width: 14px;
    }

    .workload-row:hover,
    .category-row:hover {
        color: #a75f45;
    }

    .dashboard-shell a:focus-visible {
        border-radius: 3px;
        outline: 3px solid rgba(200, 126, 97, .28);
        outline-offset: 3px;
    }

    @media (max-width: 900px) {
        .dashboard-hero {
            margin-top: 72px;
            min-height: 200px;
        }

        .dashboard-intro {
            padding-left: clamp(190px, 26vw, 240px);
        }

        .dashboard-hero-art {
            height: 350px;
            width: min(240px, 28vw);
        }

        .dashboard-grid {
            gap: 34px;
            grid-template-columns: 1fr;
        }

        .dashboard-aside {
            border-left: 0;
            display: grid;
            gap: 34px;
            grid-template-columns: 1fr 1fr;
            padding-left: 0;
        }

        .dashboard-section+.dashboard-section {
            margin-top: 0;
        }
    }

    @media (max-width: 680px) {
        .dashboard-actions {
            margin-bottom: 10px;
        }

        .dashboard-hero {
            grid-template-columns: 1fr;
            margin: 12px 0 30px;
            min-height: 0;
            overflow: hidden;
            padding: 15px 22px 0;
        }

        .dashboard-intro {
            align-items: flex-start;
            flex-direction: column;
            grid-column: auto;
            padding: 28px 0 0;
        }

        .dashboard-hero-art {
            align-self: end;
            bottom: auto;
            height: 150px;
            left: auto;
            margin: 0;
            order: 2;
            position: static;
            transform: translateY(-12px);
            width: 100%;
        }

        .status-button {
            flex-basis: 40px;
            height: 40px;
            width: 40px;
        }

        .dashboard-summary {
            gap: 0;
            grid-template-columns: repeat(2, 1fr);
            margin: 22px 0 32px;
        }

        .summary-item {
            min-width: 0;
            padding: 0 16px;
        }

        .summary-item.summary-primary {
            grid-column: 1 / -1;
            padding: 16px 0;
        }

        .summary-item.summary-primary::after {
            background: linear-gradient(to right, transparent, #ddcec6 12%, #ddcec6 88%, transparent);
            bottom: 0;
            content: '';
            height: 1px;
            left: 0;
            position: absolute;
            right: 0;
        }

        .summary-item:nth-child(even) {
            padding-left: 0;
        }

        .summary-item:nth-child(even)::before {
            display: none;
        }

        .summary-item:nth-child(n+2) {
            padding-bottom: 16px;
            padding-top: 16px;
        }

        .dashboard-aside {
            grid-template-columns: 1fr;
        }

        .focus-row,
        .task-row {
            align-items: flex-start;
        }

        .priority {
            margin-top: 3px;
        }

        .dashboard-section:not(:first-child) {
            contain-intrinsic-size: auto 360px;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-shell">
    <div class="dashboard-actions"><span class="dashboard-page-date">{{ now()->format('l, F j, Y') }}</span></div>
    <div class="dashboard-hero">
        <img class="dashboard-hero-art" src="{{ asset('gif/welcome.gif') }}" alt="Welcome illustration">
        <header class="dashboard-intro">
            <div>
                <div class="dashboard-brand"></div>
                <h2>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ Str::before(Auth::user()->name, ' ') }}.</h2>
                <p>{{ $pendingTasks + $inProgressTasks }} active {{ Str::plural('task', $pendingTasks + $inProgressTasks) }} across your academic and personal workload.</p>
            </div>
        </header>
    </div>

    <nav class="dashboard-summary" aria-label="Task overview">
        <div class="summary-item summary-primary"><a href="{{ route('tasks.index') }}"><span class="summary-label">Your workload</span><span class="summary-number">{{ $totalTasks }}</span><span class="summary-context">All tasks in your planner</span></a></div>
        <div class="summary-item is-today"><a href="{{ route('tasks.index', ['timeframe' => 'today']) }}"><span class="summary-number">{{ $todayTasks->count() }}</span><span class="summary-label">Due today</span></a></div>
        <div class="summary-item is-alert"><a href="{{ route('tasks.index', ['timeframe' => 'overdue']) }}"><span class="summary-number">{{ $overdueTasks->count() }}</span><span class="summary-label">Overdue</span></a></div>
        <div class="summary-item"><a href="{{ route('tasks.index', ['status' => 'Completed']) }}"><span class="summary-number">{{ $completedTasks }}</span><span class="summary-label">Completed</span></a></div>
    </nav>

    <div class="dashboard-grid">
        <div>
            <section class="dashboard-section" aria-labelledby="focus-heading">
                <div class="section-heading">
                    <h3 id="focus-heading">Focus now</h3><a class="section-link" href="{{ route('tasks.index', ['timeframe' => 'today']) }}">See today’s tasks</a>
                </div>
                @if($overdueTasks->count() || $todayTasks->count())
                <div class="focus-list">
                    @foreach($overdueTasks->concat($todayTasks) as $task)
                    <article class="focus-row {{ $task->isOverdue() ? 'is-overdue' : '' }}">
                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="status" value="Completed"><button class="status-button" type="submit" aria-label="Mark {{ $task->task_name }} complete" title="Mark complete"></button></form>
                        <div class="task-main"><a class="task-title" href="{{ route('tasks.show', $task) }}">{{ $task->task_name }}</a>
                            <div class="task-meta">@if($task->category)<span class="category">{{ $task->category->name }}</span>@endif <span class="due-copy {{ $task->isDueToday() ? 'is-today' : '' }}">{{ $task->isOverdue() ? 'Overdue ' . $task->due_date->diffForHumans() : 'Due today' }}</span></div>
                        </div>
                        <span class="priority {{ strtolower($task->priority) }}">{{ $task->priority }}</span>
                    </article>
                    @endforeach
                </div>
                @else
                <div class="empty-focus">Nothing is due today. Use this space to make steady progress on your upcoming work.</div>
                @endif
            </section>

            <section class="dashboard-section" aria-labelledby="recent-heading">
                <div class="section-heading">
                    <h3 id="recent-heading">Recently added</h3><a class="section-link" href="{{ route('tasks.index') }}">View all tasks <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </div>
                @if($recentTasks->count())
                <div class="focus-list">
                    @foreach($recentTasks as $task)
                    <article class="task-row">
                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $task->status === 'Completed' ? 'Pending' : 'Completed' }}"><button class="status-button {{ $task->status === 'Completed' ? 'is-complete' : '' }}" type="submit" aria-label="{{ $task->status === 'Completed' ? 'Reopen' : 'Mark' }} {{ $task->task_name }} {{ $task->status === 'Completed' ? '' : 'complete' }}" title="{{ $task->status === 'Completed' ? 'Reopen task' : 'Mark complete' }}">@if($task->status === 'Completed')<i data-lucide="check" aria-hidden="true"></i>@endif</button></form>
                        <div class="task-main"><a class="task-title {{ $task->status === 'Completed' ? 'is-complete' : '' }}" href="{{ route('tasks.show', $task) }}">{{ $task->task_name }}</a>
                            <div class="task-meta">@if($task->category)<span class="category">{{ $task->category->name }}</span>@endif <span class="{{ $task->isOverdue() ? 'due-copy' : '' }}">{{ $task->due_date ? ($task->isDueToday() ? 'Due today' : 'Due ' . $task->due_date->format('M j')) : 'No deadline' }}</span>@if($task->subtasks->count())<span>{{ $task->subtask_progress }}% subtasks complete</span>@endif</div>
                        </div>
                        <span class="priority {{ strtolower($task->priority) }}">{{ $task->priority }}</span>
                    </article>
                    @endforeach
                </div>
                @else
                <div class="empty-focus">Your task list is empty. <button type="button" class="new-task-link" onclick="openCreateTaskModal()"><i data-lucide="plus" aria-hidden="true"></i>Add your first task</button>.</div>
                @endif
            </section>
        </div>

        <aside class="dashboard-aside">
            <section class="dashboard-section" aria-labelledby="workload-heading">
                <div class="section-heading">
                    <h3 id="workload-heading">Workload</h3>
                </div>
                <div class="workload"><a class="workload-row" href="{{ route('tasks.index', ['status' => 'Pending']) }}"><span>To do</span><span class="row-action"><strong>{{ $pendingTasks }}</strong><i data-lucide="chevron-right" aria-hidden="true"></i></span></a><a class="workload-row" href="{{ route('tasks.index', ['status' => 'In Progress']) }}"><span>In progress</span><span class="row-action"><strong class="progress">{{ $inProgressTasks }}</strong><i data-lucide="chevron-right" aria-hidden="true"></i></span></a><a class="workload-row" href="{{ route('tasks.index', ['timeframe' => 'upcoming']) }}"><span>Upcoming</span><span class="row-action"><strong>{{ $upcomingTasks->count() }}</strong><i data-lucide="chevron-right" aria-hidden="true"></i></span></a></div>
            </section>
            <section class="dashboard-section" aria-labelledby="categories-heading">
                <div class="section-heading">
                    <h3 id="categories-heading">Categories</h3><a class="section-link" href="{{ route('categories.index') }}">Manage <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </div>@if($categories->count())<div class="category-list">@foreach($categories as $cat)<a class="category-row" href="{{ route('tasks.index', ['category_id' => $cat->id]) }}"><span class="category-label"><span class="category-dot" style="--category-color: {{ $cat->color }}"></span><span class="category-name">{{ $cat->name }}</span></span><span class="row-action"><span class="category-count">{{ $cat->tasks_count }}</span><i data-lucide="chevron-right" aria-hidden="true"></i></span></a>@endforeach</div>@else<div class="empty-focus">No categories yet.</div>@endif
            </section>
        </aside>
    </div>
</div>
@endsection