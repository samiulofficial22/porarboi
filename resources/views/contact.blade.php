@extends('layouts.app')

@section('title', app()->getLocale() == 'bn' ? 'যোগাযোগ' : 'Contact Us')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-6">
        <div class="card p-5 rounded-4 shadow-lg">
            <h1 class="display-5 fw-bold mb-4 text-center">@lang('messages.contact_us')</h1>
            <p class="text-center text-muted mb-5">
                {{ app()->getLocale() == 'bn' ? 'আপনার কোনো প্রশ্ন বা পরামর্শ থাকলে আমাদের সাথে যোগাযোগ করুন।' : 'If you have any questions or suggestions, please contact us.' }}
            </p>
            
            <form>
                <div class="mb-3">
                    <label class="form-label">{{ app()->getLocale() == 'bn' ? 'আপনার নাম' : 'Your Name' }}</label>
                    <input type="text" class="form-control rounded-pill px-4 py-2" placeholder="{{ app()->getLocale() == 'bn' ? 'নাম লিখুন' : 'Enter your name' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ app()->getLocale() == 'bn' ? 'ইমেইল এড্রেস' : 'Email Address' }}</label>
                    <input type="email" class="form-control rounded-pill px-4 py-2" placeholder="{{ app()->getLocale() == 'bn' ? 'ইমেইল লিখুন' : 'Enter your email' }}">
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ app()->getLocale() == 'bn' ? 'বার্তা' : 'Message' }}</label>
                    <textarea class="form-control rounded-4 px-4 py-3" rows="4" placeholder="{{ app()->getLocale() == 'bn' ? 'আপনার বার্তা লিখুন' : 'Write your message' }}"></textarea>
                </div>
                <button type="button" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow">
                    {{ app()->getLocale() == 'bn' ? 'বার্তা পাঠান' : 'Send Message' }}
                </button>
            </form>
            
            <div class="mt-5 text-center">
                <p class="mb-1"><i class="fas fa-envelope me-2 text-primary"></i> support@futurebooks.com</p>
                <p><i class="fas fa-phone me-2 text-primary"></i> +880 123 456 789</p>
            </div>
        </div>
    </div>
</div>
@endsection
