@extends('layouts.app')

@section('title', app()->getLocale() == 'bn' ? 'ব্যবহারের শর্তাবলী' : 'Terms of Service')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-10">
        <div class="card p-5 rounded-4 shadow-sm">
            <h1 class="display-5 fw-bold mb-4">@lang('messages.terms_of_service')</h1>
            <p class="text-muted">Last updated: {{ date('F d, Y') }}</p>
            <hr class="my-4">
            
            <div class="terms-content">
                <h3>1. Acceptance of Terms</h3>
                <p>By accessing and using FutureBooks, you accept and agree to be bound by the terms and provision of this agreement.</p>
                
                <h3 class="mt-4">2. User Accounts</h3>
                <p>To access some features of the service, you must create an account. You are responsible for maintaining the confidentiality of your account password.</p>
                
                <h3 class="mt-4">3. Purchases and Payments</h3>
                <p>All purchases are final. We use secure payment gateways to ensure your transaction data is protected.</p>
                
                <h3 class="mt-4">4. Intellectual Property</h3>
                <p>The content, organization, graphics, design, and other matters related to FutureBooks are protected under applicable copyrights and trademarks.</p>
            </div>
        </div>
    </div>
</div>
@endsection
