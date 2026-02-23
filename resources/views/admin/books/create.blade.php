@extends('layouts.admin')

@section('page_title', 'Add New Book')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="admin-card">
            <h4 class="mb-4">Book Information (Hybrid System)</h4>
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @if(session('error'))
                    <div class="alert alert-danger bg-opacity-10 border-danger text-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-4 mb-4">
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

                    <div class="col-md-4 mb-4">
                        <label class="form-label text-muted">Book Title</label>
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" placeholder="Enter book title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label text-muted">Product Type</label>
                        <select name="product_type" id="product_type" class="form-select form-control-lg @error('product_type') is-invalid @enderror" required>
                            <option value="digital" {{ old('product_type') == 'digital' ? 'selected' : '' }}>Digital (PDF Only)</option>
                            <option value="physical" {{ old('product_type') == 'physical' ? 'selected' : '' }}>Physical (Hardcopy Only)</option>
                            <option value="both" {{ old('product_type') == 'both' ? 'selected' : '' }}>Both (Digital & Physical)</option>
                        </select>
                        @error('product_type')
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

                <div class="row g-4 mb-5 border rounded-4 p-4" style="background-color: var(--nav-link-hover); border-color: var(--border-color) !important;">
                    <div class="col-12">
                        <h6 class="text-primary fw-bold mb-0">Pricing & Inventory</h6>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Base Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" value="{{ old('price') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">PDF Price (Optional)</label>
                        <input type="number" step="0.01" name="format_price_pdf" class="form-control" placeholder="0.00" value="{{ old('format_price_pdf') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Hardcopy Price (Optional)</label>
                        <input type="number" step="0.01" name="format_price_hardcopy" class="form-control" placeholder="0.00" value="{{ old('format_price_hardcopy') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" placeholder="0" value="{{ old('stock_quantity') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">SKU</label>
                        <input type="text" name="sku" class="form-control" placeholder="SKU-001" value="{{ old('sku') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Weight (e.g. 500g)</label>
                        <input type="text" name="weight" class="form-control" placeholder="500g" value="{{ old('weight') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Shipping Charge</label>
                        <input type="number" step="0.01" name="shipping_charge" class="form-control" placeholder="0.00" value="{{ old('shipping_charge', 0) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*" required>
                        <small class="text-muted">Recommended: 350x500px</small>
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4" id="pdf_file_section">
                        <label class="form-label text-muted">PDF File</label>
                        <input type="file" name="pdf_file" class="form-control @error('pdf_file') is-invalid @enderror" accept=".pdf">
                        <small class="text-muted">Required for Digital/Both. Maximum 20MB</small>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productType = document.getElementById('product_type');
    const pdfSection = document.getElementById('pdf_file_section');

    function togglePdfSection() {
        if (productType.value === 'physical') {
            pdfSection.style.display = 'none';
        } else {
            pdfSection.style.display = 'block';
        }
    }

    productType.addEventListener('change', togglePdfSection);
    togglePdfSection(); // Initial state
});
</script>
@endsection

@section('styles')
<style>
    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.5;
    }
</style>
@endsection
