@extends('layouts.app')

@section('title', 'Edit Category')
@section('header_title', 'Edit Category')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">← Back to Categories</a>
    </div>

    <div class="card" style="max-width: 600px;">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Category Name <span style="color:red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            </div>

            <input type="hidden" name="icon" value="folder">
            <div class="form-group">
                <label class="form-label" for="color">Category Color</label>
                <input type="color" name="color" id="color" class="form-control" style="height: 44px; padding: 4px; cursor: pointer;" value="{{ old('color', $category->color) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
