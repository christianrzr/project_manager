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
        grid-template-columns: minmax(190px, 300px) minmax(0, 1fr);
        margin: clamp(64px, 8vw, 100px) 0 var(--dashboard-space);
        min-height: 232px;
        overflow: visible;
        padding: clamp(20px, 3vw, 36px);
        position: relative;
    }

    .dashboard-page-date {
        color: #786a63;
        font-size: .84rem;
        font-weight: 700;
        letter-spacing: .02em;
    }

    .dashboard-intro {
        display: flex;
        align-items: end;
        grid-column: 1 / -1;
        justify-content: space-between;
        gap: 20px;
        padding: 0 0 0 clamp(230px, 25vw, 300px);
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
        font-size: .86rem;
        font-weight: 650;
    }

    .dashboard-intro h2 {
        color: #442f28;
        font-size: clamp(1.9rem, 3.4vw, 2.75rem);
        letter-spacing: -.05em;
        line-height: 1.06;
    }

    .dashboard-intro p {
        color: #526176;
        font-size: .98rem;
        margin-top: 9px;
        max-width: 58ch;
    }

    .dashboard-summary {
        display: flex;
        align-items: stretch;
        margin: 25px 0 38px;
    }

    .summary-item {
        min-width: 142px;
        padding: 0 26px;
        border-left: 1px solid #dce3ea;
    }

    .summary-item:first-child {
        border-left: 0;
        padding-left: 0;
    }

    .summary-item a {
        display: block;
    }

    .summary-number {
        color: #172033;
        display: block;
        font-size: 2rem;
        font-weight: 780;
        letter-spacing: -.05em;
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }

    .summary-label {
        color: #64748b;
        display: block;
        font-size: .74rem;
        font-weight: 750;
        letter-spacing: .07em;
        margin-top: 8px;
        text-transform: uppercase;
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
        grid-template-columns: minmax(0, 1.8fr) minmax(260px, .8fr);
        gap: clamp(32px, 5vw, 56px);
        align-items: start;
    }

    .dashboard-section+.dashboard-section {
        margin-top: 44px;
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
        font-size: 1.1rem;
        letter-spacing: -.02em;
    }

    .section-link {
        color: #a75f45;
        font-size: .84rem;
        font-weight: 700;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .focus-list {
        border-top: 2px solid #172033;
    }

    .focus-row,
    .task-row {
        align-items: center;
        border-bottom: 1px solid #dce3ea;
        display: flex;
        gap: 15px;
        padding: 16px 4px;
    }

    .focus-row {
        padding-left: 12px;
    }

    .focus-row.is-overdue {
        background: #fff6f4;
        border-bottom-color: #f0c9c2;
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
        font-size: .96rem;
        font-weight: 720;
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
        font-size: .78rem;
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
        border-left: 3px solid #b6c0cc;
        color: #526176;
        flex: 0 0 auto;
        font-size: .72rem;
        font-weight: 750;
        letter-spacing: .04em;
        padding-left: 8px;
        text-transform: uppercase;
    }

    .priority.urgent {
        border-color: #d92d20;
        color: #b42318;
    }

    .priority.high {
        border-color: #e58b2b;
        color: #9d530c;
    }

    .empty-focus {
        border-top: 2px solid #172033;
        color: #526176;
        padding: 20px 4px;
    }

    .workload {
        border-top: 2px solid #172033;
    }

    .workload-row {
        align-items: baseline;
        border-bottom: 1px solid #dce3ea;
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
    }

    .workload-row span {
        color: #526176;
        font-size: .88rem;
    }

    .workload-row strong {
        color: #172033;
        font-size: 1.1rem;
        font-variant-numeric: tabular-nums;
    }

    .workload-row .progress {
        color: #a75f45;
    }

    .category-list {
        border-top: 2px solid #172033;
    }

    .category-row {
        align-items: center;
        border-bottom: 1px solid #dce3ea;
        display: flex;
        gap: 10px;
        justify-content: space-between;
        padding: 13px 0;
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
        font-size: .86rem;
        font-weight: 750;
        margin-top: 15px;
        text-decoration: underline;
        text-underline-offset: 3px;
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
            display: grid;
            gap: 34px;
            grid-template-columns: 1fr 1fr;
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
            display: grid;
            gap: 18px 0;
            grid-template-columns: repeat(2, 1fr);
            margin: 22px 0 32px;
        }

        .summary-item {
            min-width: 0;
            padding: 0 16px;
        }

        .summary-item:nth-child(odd) {
            border-left: 0;
            padding-left: 0;
        }

        .summary-item:nth-child(n+3) {
            border-top: 1px solid #dce3ea;
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
    }
</style>
@endsection

@section('content')
<div class="dashboard-shell">
    <div class="dashboard-actions"><span class="dashboard-page-date">{{ now()->format('l, F j') }}</span><button type="button" class="btn btn-primary" onclick="openCreateTaskModal()"><i data-lucide="plus" aria-hidden="true"></i>Add task</button></div>
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
        <div class="summary-item"><a href="{{ route('tasks.index') }}"><span class="summary-number">{{ $totalTasks }}</span><span class="summary-label">All tasks</span></a></div>
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
                    <h3 id="recent-heading">Recently added</h3><a class="section-link" href="{{ route('tasks.index') }}">View all tasks</a>
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
                <div class="empty-focus">Your task list is empty. <button type="button" class="new-task-link" onclick="openCreateTaskModal()">Add your first task</button>.</div>
                @endif
            </section>
        </div>

        <aside class="dashboard-aside">
            <section class="dashboard-section" aria-labelledby="workload-heading">
                <div class="section-heading">
                    <h3 id="workload-heading">Workload</h3>
                </div>
                <div class="workload"><a class="workload-row" href="{{ route('tasks.index', ['status' => 'Pending']) }}"><span>To do</span><strong>{{ $pendingTasks }}</strong></a><a class="workload-row" href="{{ route('tasks.index', ['status' => 'In Progress']) }}"><span>In progress</span><strong class="progress">{{ $inProgressTasks }}</strong></a><a class="workload-row" href="{{ route('tasks.index', ['timeframe' => 'upcoming']) }}"><span>Upcoming</span><strong>{{ $upcomingTasks->count() }}</strong></a></div>
            </section>
            <section class="dashboard-section" aria-labelledby="categories-heading">
                <div class="section-heading">
                    <h3 id="categories-heading">Categories</h3><a class="section-link" href="{{ route('categories.index') }}">Manage</a>
                </div>@if($categories->count())<div class="category-list">@foreach($categories as $cat)<a class="category-row" href="{{ route('tasks.index', ['category_id' => $cat->id]) }}"><span class="category-label"><span class="category-dot" style="--category-color: {{ $cat->color }}"></span><span class="category-name">{{ $cat->name }}</span></span><span class="category-count">{{ $cat->tasks_count }}</span></a>@endforeach</div>@else<div class="empty-focus">No categories yet.</div>@endif
            </section>
        </aside>
    </div>
</div>
@endsection
