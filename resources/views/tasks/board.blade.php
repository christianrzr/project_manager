@extends('layouts.app')

@section('title', 'Task Board')
@section('header_title', 'Task Board')

@section('styles')
<style>
    .board-page { max-width: 1280px; margin: 0 auto; width: 100%; }
    .board-intro { align-items: end; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; gap: 20px; margin-bottom: 26px; padding: 8px 0 23px; }
    .board-intro h2 { color: var(--text-main); font-size: clamp(1.5rem, 2vw, 1.75rem); font-weight: var(--weight-bold); letter-spacing: -.035em; line-height: 1.2; }
    .board-intro p { color: var(--text-muted); font-size: var(--text-sm); line-height: 1.5; margin-top: 6px; }
    .board-container { display: grid; grid-template-columns: repeat(3, minmax(280px, 1fr)); gap: 18px; overflow-x: auto; padding-bottom: 10px; }
    .board-column { background: #f4eeeb; border-top: 3px solid var(--column-color); min-height: 540px; padding: 15px; }
    .column-header { align-items: center; display: flex; gap: 10px; justify-content: space-between; margin-bottom: 14px; }
    .column-title { align-items: center; color: #4a3630; display: flex; font-size: var(--text-sm); font-weight: var(--weight-bold); gap: 8px; letter-spacing: -.01em; }
    .column-title svg { color: var(--column-color); height: 17px; width: 17px; }
    .column-count { background: #fff; border: 1px solid #e4d8d1; border-radius: 999px; color: #6e5a51; font-size: var(--text-xs); font-variant-numeric: tabular-nums; font-weight: var(--weight-semibold); padding: 3px 8px; }
    .kanban-card { background: #fff; border: 1px solid #eadfd9; border-radius: 10px; display: flex; flex-direction: column; gap: 12px; margin-bottom: 10px; padding: 15px; transition: border-color .18s, box-shadow .18s, transform .18s; }
    .kanban-card:hover { border-color: #d6b2a4; box-shadow: 0 7px 18px rgba(83, 52, 39, .08); transform: translateY(-1px); }
    .board-empty { border: 1px dashed #d8c8bf; color: #8c7a72; font-size: .84rem; padding: 25px 14px; text-align: center; }
    .kanban-category { align-items: center; color: #6e5a51; display: inline-flex; font-size: var(--text-xs); font-weight: var(--weight-semibold); gap: 6px; }
    .kanban-title { color: var(--text-main); display: block; font-size: var(--text-sm); font-weight: var(--weight-bold); letter-spacing: -.01em; line-height: 1.4; }
    .kanban-description { color: var(--text-muted); font-size: var(--text-xs); line-height: 1.5; margin-top: 5px; }
    .kanban-progress, .kanban-footer { color: var(--text-muted); font-size: var(--text-xs); line-height: 1.4; }
    @media (max-width: 760px) { .board-intro { align-items: flex-start; flex-direction: column; } .board-container { grid-template-columns: repeat(3, minmax(270px, 82vw)); } }
</style>
@endsection

@section('content')
<div class="board-page">
    <header class="board-intro"><div><h2>Keep work moving.</h2><p>Move tasks through each stage as your week takes shape.</p></div></header>
    <div class="board-container">
        <section class="board-column" style="--column-color: #c87e61" aria-labelledby="todo-column"><div class="column-header"><h3 class="column-title" id="todo-column"><i data-lucide="circle" aria-hidden="true"></i>To do</h3><span class="column-count">{{ $pendingTasks->count() }}</span></div>@forelse($pendingTasks as $task)@include('tasks.partials.board_card', ['task' => $task, 'nextStatus' => 'In Progress', 'prevStatus' => null])@empty<div class="board-empty">No tasks waiting here.</div>@endforelse</section>
        <section class="board-column" style="--column-color: #8c674f" aria-labelledby="progress-column"><div class="column-header"><h3 class="column-title" id="progress-column"><i data-lucide="loader-circle" aria-hidden="true"></i>In progress</h3><span class="column-count">{{ $inProgressTasks->count() }}</span></div>@forelse($inProgressTasks as $task)@include('tasks.partials.board_card', ['task' => $task, 'nextStatus' => 'Completed', 'prevStatus' => 'Pending'])@empty<div class="board-empty">Start a task when you are ready.</div>@endforelse</section>
        <section class="board-column" style="--column-color: #4f7b63" aria-labelledby="complete-column"><div class="column-header"><h3 class="column-title" id="complete-column"><i data-lucide="circle-check" aria-hidden="true"></i>Completed</h3><span class="column-count">{{ $completedTasks->count() }}</span></div>@forelse($completedTasks as $task)@include('tasks.partials.board_card', ['task' => $task, 'nextStatus' => null, 'prevStatus' => 'In Progress'])@empty<div class="board-empty">Completed work will appear here.</div>@endforelse</section>
    </div>
</div>
@endsection
