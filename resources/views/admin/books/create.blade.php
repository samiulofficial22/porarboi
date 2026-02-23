@extends('layouts.admin')

@section('page_title', 'Add New Book')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="admin-card">
            <h4 class="mb-4">Book Information</h4>
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @if(session('error'))
                    <div class="alert alert-danger bg-opacity-10 border-danger text-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted">Category</label>
                        <select name="category_id" class="form-select form-control-lg @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted">Book Title</label>
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" placeholder="Enter book title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">Description</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Describe the book..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label text-muted">Price ($)</label>
                        <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="0.00" value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label text-muted">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*" required>
                        <small class="text-muted">Recommended: 350x500px</small>
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label text-muted">PDF File</label>
                        <input type="file" name="pdf_file" class="form-control @error('pdf_file') is-invalid @enderror" accept=".pdf" required>
                        <small class="text-muted">Maximum 20MB</small>
                        @error('pdf_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SEO Settings Section -->
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-4 text-primary"><i class="fas fa-search me-2"></i>SEO & Optimization</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted">URL Slug (Optional)</label>
                            <input type="text" name="slug" class="form-control" placeholder="e.g. the-way-of-kings" value="{{ old('slug') }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted">SEO Title</label>
                            <input type="text" name="seo_title" class="form-control" placeholder="Browser tab title" value="{{ old('seo_title') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">SEO Description</label>
                        <textarea name="seo_description" rows="2" class="form-control" placeholder="Meta description for search engines">{{ old('seo_description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted">SEO Keywords</label>
                            <input type="text" name="seo_keywords" class="form-control" placeholder="fantasy, epic, sanderson" value="{{ old('seo_keywords') }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted">OG Image (Social Sharing)</label>
                            <input type="file" name="og_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn btn-gradient px-5 py-2">
                        <i class="fas fa-save me-2"></i>Upload Book
                    </button>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary px-4 py-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-control, .form-select {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 12px;
    }
    .form-control:focus, .form-select:focus {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
    }
    option {
        background-color: #111827;
        color: white;
    }
</style>
@endsection
