@extends('layouts.app')

@section('title', app()->getLocale() == 'bn' ? 'গোপনীয়তা নীতি' : 'Privacy Policy')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-10">
        <div class="card p-5 rounded-4 shadow-sm">
            <h1 class="display-5 fw-bold mb-4">@lang('messages.privacy_policy')</h1>
            <p class="text-muted">Last updated: {{ date('F d, Y') }}</p>
            <hr class="my-4">
            
            <div class="privacy-content">
                <h3>1. Collection of Information</h3>
                <p>We collect information you provide directly to us, such as when you create or modify your account, purchase books, or contact customer support.</p>
                
                <h3 class="mt-4">2. Use of Information</h3>
                <p>We use the information we collect to provide, maintain, and improve our services, develop new ones, and protect FutureBooks and our users.</p>
                
                <h3 class="mt-4">3. Data Security</h3>
                <p>We take reasonable measures to help protect information about you from loss, theft, misuse and unauthorized access, disclosure, alteration and destruction.</p>
            </div>
        </div>
    </div>
</div>
@endsection
