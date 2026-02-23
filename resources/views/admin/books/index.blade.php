@extends('layouts.admin')

@section('page_title', 'Books Inventory')

@section('content')
<!-- Stats and Info Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="admin-card d-flex justify-content-between align-items-center py-3">
            <div>
                <h4 class="mb-0">All Books</h4>
                <small class="text-muted">Total books in your system: <span class="text-white fw-bold">{{ $totalBooks }}</span></small>
            </div>
            <a href="{{ route('admin.books.create') }}" class="btn btn-gradient">
                <i class="fas fa-plus me-2"></i>Add New Book
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="admin-card p-3">
            <form action="{{ route('admin.books.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label text-muted small">Search Books</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by title..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small">Category Filter</label>
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    @if(request()->hasAny(['search', 'category_id']))
                        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary py-2 rounded-3">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Books Table Section -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cover</th>
                    <th>Book Details</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                <tr>
                    <td>{{ ($books->currentPage() - 1) * $books->perPage() + $loop->iteration }}</td>
                    <td>
                        @if($book->cover_image == 'covers/dummy.jpg')
                            <div class="bg-secondary bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 70px;">
                                <i class="fas fa-book text-muted"></i>
                            </div>
                        @else
                            <img src="{{ Storage::url($book->cover_image) }}" class="rounded-3 shadow-sm border border-secondary border-opacity-25" style="width: 50px; height: 70px; object-fit: cover;">
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-white">{{ $book->title }}</div>
                        <small class="text-muted d-block" style="max-width:300px;">{{ Str::limit($book->description, 60) }}</small>
                    </td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3">
                            {{ $book->category->name ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td class="fw-bold">${{ number_format($book->price, 2) }}</td>
                    <td>
                        @if($book->is_active)
                            <span class="badge-soft badge-soft-success">Active</span>
                        @else
                            <span class="badge-soft badge-soft-warning">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-outline-info rounded-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
                            <p>No books found matching your criteria.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Customized Pagination Section -->
    <div class="pagination-wrapper mt-4 d-flex justify-content-center align-items-center">
        <div class="custom-pagination">
            {{ $books->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Table Styling */
    .table-custom {
        vertical-align: middle;
    }

    /* Form and Input Styling */
    .form-control, .form-select, .input-group-text {
        background-color: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        border-radius: 10px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
    }

    /* Pagination Colors Customization */
    .pagination {
        margin-bottom: 0;
        gap: 5px;
    }
    .page-link {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #94a3b8 !important;
        border-radius: 8px !important;
        padding: 8px 16px !important;
        transition: all 0.2s !important;
    }
    .page-link:hover {
        background-color: #6366f1 !important;
        color: #fff !important;
        border-color: #6366f1 !important;
    }
    .page-item.active .page-link {
        background-color: #6366f1 !important;
        border-color: #6366f1 !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.4) !important;
    }
    .page-item.disabled .page-link {
        background-color: transparent !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
        color: #475569 !important;
    }
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        border-radius: 8px !important;
    }

    /* Custom Scrollbar for Table */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.2);
        border-radius: 10px;
    }
</style>
@endsection
