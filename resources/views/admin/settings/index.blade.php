@extends('layouts.admin')

@section('page_title', 'Site Settings')

@section('content')
<div class="admin-card overflow-hidden p-0 border-0 shadow-sm">
    <div class="row g-0">
        <!-- Sidebar Tabs -->
        <div class="col-md-3 border-end" style="background: rgba(0,0,0,0.02);">
            <div class="nav flex-column nav-pills p-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @php
                    $groups = [
                        'general' => ['icon' => 'fas fa-globe', 'label' => 'General'],
                        'seo' => ['icon' => 'fas fa-search', 'label' => 'SEO'],
                        'payment' => ['icon' => 'fas fa-credit-card', 'label' => 'Payment'],
                        'email' => ['icon' => 'fas fa-envelope', 'label' => 'Email'],
                        'download' => ['icon' => 'fas fa-download', 'label' => 'Downloads'],
                        'store' => ['icon' => 'fas fa-store', 'label' => 'Store'],
                        'security' => ['icon' => 'fas fa-shield-alt', 'label' => 'Security'],
                    ];
                @endphp

                @foreach($groups as $key => $group)
                    <button class="nav-link mb-2 text-start d-flex align-items-center gap-3 py-3 px-4 @if($loop->first) active @endif" 
                            id="v-pills-{{ $key }}-tab" 
                            data-bs-toggle="pill" 
                            data-bs-target="#v-pills-{{ $key }}" 
                            type="button" role="tab">
                        <i class="{{ $group['icon'] }}"></i>
                        <span>{{ $group['label'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Tab Content -->
        <div class="col-md-9 bg-card">
            <div class="tab-content" id="v-pills-tabContent">
                @foreach($groups as $key => $groupInfo)
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="v-pills-{{ $key }}" role="tabpanel">
                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="settings_group" value="{{ $key }}">
                            
                            <div class="p-5">
                                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                                    <h4 class="mb-0 fw-bold">{{ $groupInfo['label'] }} Settings</h4>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </div>

                                @php
                                    $groupSettings = $settings->get($key, collect());
                                @endphp

                                @forelse($groupSettings as $setting)
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">
                                            {{ $setting->display_name }}
                                        </label>
                                        
                                        @if($setting->type === 'text')
                                            <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control form-control-lg border-opacity-10 bg-opacity-10" placeholder="Enter {{ strtolower($setting->display_name) }}">
                                        
                                        @elseif($setting->type === 'textarea')
                                            <textarea name="{{ $setting->key }}" rows="4" class="form-control form-control-lg border-opacity-10 bg-opacity-10" placeholder="Enter {{ strtolower($setting->display_name) }}">{{ $setting->value }}</textarea>
                                        
                                        @elseif($setting->type === 'password')
                                            <input type="password" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control form-control-lg border-opacity-10 bg-opacity-10" placeholder="••••••••">
                                        
                                        @elseif($setting->type === 'boolean')
                                            <div class="form-check form-switch pt-1">
                                                <input class="form-check-input" type="checkbox" name="{{ $setting->key }}" id="switch-{{ $setting->key }}" @if($setting->value) checked @endif value="1">
                                                <label class="form-check-label ms-2 text-muted" for="switch-{{ $setting->key }}">Enable this feature</label>
                                            </div>

                                        @elseif($setting->type === 'image')
                                            @php
                                                // Determine preview background based on key
                                                $previewBg = 'bg-secondary bg-opacity-10';
                                                if ($setting->key === 'site_logo_light') $previewBg = 'bg-white';
                                                if ($setting->key === 'site_logo_dark') $previewBg = 'bg-dark';
                                            @endphp
                                            <div class="d-flex align-items-center gap-4 p-4 rounded-4 border border-opacity-10 bg-light bg-opacity-5">
                                                <div class="settings-image-preview border rounded-3 overflow-hidden d-flex align-items-center justify-content-center {{ $previewBg }}" 
                                                     style="width: 120px; height: 120px; border-color: var(--border-color) !important;">
                                                    @if($setting->value)
                                                        <img src="{{ Storage::url($setting->value) }}" id="preview-{{ $setting->key }}" class="img-fluid object-fit-contain p-2" style="max-height: 100%;">
                                                    @else
                                                        <div class="text-center opacity-25">
                                                            <i class="fas fa-image fa-2x mb-2"></i>
                                                            <div class="smaller">No Image</div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="input-group input-group-sm mb-2">
                                                        <input type="file" name="{{ $setting->key }}" class="form-control" onchange="previewImage(this, 'preview-{{ $setting->key }}')">
                                                    </div>
                                                    <small class="text-muted d-block"><i class="fas fa-info-circle me-1"></i> Recommended for {{ $setting->display_name }}: PNG with transparency.</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if($setting->instructions)
                                            <div class="form-text mt-2"><i class="fas fa-info-circle me-1 small"></i> {{ $setting->instructions }}</div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-5 opacity-50">
                                        <i class="fas fa-cog fa-3x mb-3"></i>
                                        <p>No settings available in this category.</p>
                                    </div>
                                @endforelse

                                @if($groupSettings->count() > 0)
                                    <div class="pt-4 border-top mt-5">
                                        <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg">
                                            <i class="fas fa-save me-2"></i> Save All {{ $groupInfo['label'] }} Settings
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .nav-pills .nav-link {
        color: var(--text-color);
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .nav-pills .nav-link:hover {
        background: rgba(99, 102, 241, 0.05);
        color: var(--accent-color);
    }
    .nav-pills .nav-link.active {
        background: var(--accent-color) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
    }
    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.1);
    }
</style>

<script>
    function previewImage(input, previewId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewId);
                if (img) {
                    img.src = e.target.result;
                } else {
                    // Create img if it doesn't exist (e.g. initial upload)
                    const container = input.closest('.d-flex').querySelector('.settings-image-preview');
                    container.innerHTML = `<img src="${e.target.result}" id="${previewId}" class="img-fluid object-fit-contain" style="max-height: 100%;">`;
                }
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
