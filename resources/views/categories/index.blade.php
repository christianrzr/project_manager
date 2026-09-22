@extends('layouts.app')

@section('title', 'Categories')
@section('header_title', 'Category Management')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <p style="color: #64748b; font-size: 0.95rem;">
            Group and color-code your tasks by subject, project, or domain.
        </p>
        <a href="{{ route('categories.create') }}" class="btn btn-primary"><i data-lucide="plus" aria-hidden="true"></i>Create New Category</a>
    </div>

    @if($categories->count() === 0)
        <div class="card" style="text-align: center; padding: 50px 20px;">
            <i data-lucide="tags" aria-hidden="true" style="color: var(--primary); height: 42px; margin-bottom: 10px; width: 42px;"></i>
            <h3>No categories yet</h3>
            <p style="color: #94a3b8; margin: 6px 0 16px;">Create your first category (e.g., Academics, Projects, Personal).</p>
            <a href="{{ route('categories.create') }}" class="btn btn-primary"><i data-lucide="plus" aria-hidden="true"></i>Create Category</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
            @foreach($categories as $category)
                <div class="card" style="margin-bottom: 0; border-top: 4px solid {{ $category->color }}; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="background: {{ $category->color }}; border-radius: 50%; height: 10px; width: 10px;"></span>
                                <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a;">{{ $category->name }}</h3>
                            </div>
                            <span class="badge" style="background: {{ $category->color }}20; color: {{ $category->color }}; font-weight: 700;">
                                {{ $category->tasks_count }} tasks
                            </span>
                        </div>

                        <p style="font-size: 0.88rem; color: #64748b; line-height: 1.4; margin-bottom: 16px;">
                            {{ $category->description ?: 'No description provided.' }}
                        </p>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                        <a href="{{ route('tasks.index', ['category_id' => $category->id]) }}" class="btn btn-secondary btn-sm">
                            View Tasks →
                        </a>

                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary btn-sm" title="Edit Category"><i data-lucide="pencil" aria-hidden="true"></i>Edit</a>

                            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? Tasks assigned to it will become uncategorized.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Category" aria-label="Delete {{ $category->name }}"><i data-lucide="trash-2" aria-hidden="true"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
