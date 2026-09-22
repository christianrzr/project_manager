@extends('layouts.app')

@section('title', 'Create Category')
@section('header_title', 'Create Category')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">← Back to Categories</a>
    </div>

    <div class="card" style="max-width: 600px;">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Category Name <span style="color:red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="e.g., Web Systems (WST21)" value="{{ old('name') }}" required>
            </div>

            <input type="hidden" name="icon" value="folder">
            <div class="form-group">
                <label class="form-label" for="color">Category Color</label>
                <input type="color" name="color" id="color" class="form-control" style="height: 44px; padding: 4px; cursor: pointer;" value="{{ old('color', '#c87e61') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Brief note about what tasks belong here...">{{ old('description') }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Create Category</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
