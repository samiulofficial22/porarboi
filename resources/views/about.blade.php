@extends('layouts.app')

@section('title', app()->getLocale() == 'bn' ? 'আমাদের সম্পর্কে' : 'About Us')

@section('content')
<!-- Hero Section -->
<section class="about-hero text-center py-5 mb-5 rounded-5 shadow-lg overflow-hidden position-relative">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-1">
        <span class="badge hero-badge px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">@lang('messages.about_us')</span>
        <h1 class="display-3 fw-bold mb-3 hero-main-title">
            {{ app()->getLocale() == 'bn' ? 'জ্ঞানের নতুন দিগন্ত' : 'New Horizons of Knowledge' }}
        </h1>
        <p class="lead hero-subtitle mb-0 mx-auto" style="max-width: 700px; font-weight: 500;">
            {{ app()->getLocale() == 'bn' 
                ? 'আমরা ডিজিটাল সাহিত্যের মাধ্যমে মানুষের চিন্তাঝারাকে উন্নত করতে এবং বিশ্বজুড়ে জ্ঞানের আলো ছড়িয়ে দিতে কাজ করছি।' 
                : 'We are working to elevate human thought through digital literature and spread the light of knowledge worldwide.' 
            }}
        </p>
    </div>
</section>

<!-- Stats Section -->
<div class="row g-4 mb-5 text-center">
    @php
        $stats = [
            ['1K+', app()->getLocale() == 'bn' ? 'সেরা বই' : 'Premium Books', 'fa-book'],
            ['5K+', app()->getLocale() == 'bn' ? 'সক্রিয় পাঠক' : 'Active Readers', 'fa-users'],
            ['50+', app()->getLocale() == 'bn' ? 'ক্যাটাগরি' : 'Categories', 'fa-layer-group'],
            ['24/7', app()->getLocale() == 'bn' ? 'সাপোর্ট' : 'Support', 'fa-headset']
        ];
    @endphp
    @foreach($stats as $stat)
        <div class="col-6 col-md-3">
            <div class="stat-card p-4 rounded-4 bg-glass h-100 shadow-sm border">
                <i class="fas {{ $stat[2] }} text-primary mb-3 opacity-50"></i>
                <h2 class="display-5 fw-bold mb-0 stat-number">{{ $stat[0] }}</h2>
                <p class="text-muted fw-semibold mt-2 mb-0">{{ $stat[1] }}</p>
            </div>
        </div>
    @endforeach
</div>

<!-- Mission & Vision -->
<div class="row g-5 align-items-center mb-5">
    <div class="col-lg-6">
        <div class="pe-lg-5">
            <h2 class="fw-bold mb-4 section-title text-body">
                {{ app()->getLocale() == 'bn' ? 'আমাদের গল্প' : 'Our Story' }}
            </h2>
            <p class="text-muted mb-4 lead fs-5">
                {{ app()->getLocale() == 'bn' 
                    ? setting('site_name', 'FutureBooks').'-এর যাত্রা শুরু হয়েছিল একটি সাধারণ উদ্দেশ্য নিয়ে: বই পড়া যেন সবার জন্য সহজ এবং সাশ্রয়ী হয়। আজ আমরা হাজার হাজার পাঠকের আস্থার প্রতীক।' 
                    : 'The journey of '.setting('site_name', 'FutureBooks').' began with a simple goal: to make reading easy and affordable for everyone. Today, we are a symbol of trust for thousands of readers.' 
                }}
            </p>
            @php
                $features = [
                    app()->getLocale() == 'bn' ? 'উন্নত ইউজার ইন্টারফেস' : 'Advanced User Interface',
                    app()->getLocale() == 'bn' ? 'দ্রুত ডাউনলোড সুবিধা' : 'Fast Download System',
                    app()->getLocale() == 'bn' ? 'নিরাপদ পেমেন্ট গেটওয়ে' : 'Secure Payment Gateway'
                ];
            @endphp
            @foreach($features as $feature)
                <div class="feature-item d-flex align-items-center mb-4">
                    <div class="icon-box bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fas fa-check text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-body">{{ $feature }}</h5>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row g-4">
            <div class="col-sm-6">
                <div class="card p-4 border-0 shadow-sm rounded-4 h-100 bg-glass text-center mission-card">
                    <div class="icon-circle mb-3 mx-auto shadow-sm">
                        <i class="fas fa-eye fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold text-body">{{ app()->getLocale() == 'bn' ? 'ভিশন' : 'Vision' }}</h4>
                    <p class="text-muted mb-0">
                        {{ app()->getLocale() == 'bn' ? 'বিশ্বের প্রতিটি প্রান্তে ডিজিটাল বই পৌঁছে দেওয়া।' : 'Deliver digital books to every corner of the world.' }}
                    </p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card p-4 border-0 shadow-sm rounded-4 h-100 bg-glass text-center mission-card">
                    <div class="icon-circle mb-3 mx-auto shadow-sm">
                        <i class="fas fa-rocket fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold text-body">{{ app()->getLocale() == 'bn' ? 'মিশন' : 'Mission' }}</h4>
                    <p class="text-muted mb-0">
                        {{ app()->getLocale() == 'bn' ? 'শিক্ষাকে সবার জন্য উন্মুক্ত এবং আনন্দদায়ক করা।' : 'Making education open and enjoyable for everyone.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="py-5 text-center cta-section rounded-5 shadow-sm mb-5">
    <h3 class="fw-bold mb-4 text-body">{{ app()->getLocale() == 'bn' ? 'আমাদের সাথে যুক্ত হোন' : 'Join Our Community' }}</h3>
    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow">
        {{ app()->getLocale() == 'bn' ? 'এখনই শুরু করুন' : 'Get Started Now' }}
    </a>
</div>
@endsection

@section('styles')
<style>
    .about-hero {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.3);
        transition: all 0.3s ease;
    }

    .hero-main-title {
        color: #ffffff;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .hero-subtitle {
        color: rgba(255, 255, 255, 0.9);
    }

    .hero-badge {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Light Mode Adjustments */
    [data-bs-theme="light"] .about-hero {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    [data-bs-theme="light"] .hero-main-title {
        background: linear-gradient(to right, #4f46e5, #9333ea);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: none;
    }

    [data-bs-theme="light"] .hero-subtitle {
        color: #475569 !important;
    }

    [data-bs-theme="light"] .hero-badge {
        background: rgba(99, 102, 241, 0.1) !important;
        color: #6366f1 !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
    }

    .hero-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.1;
    }

    .bg-glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="light"] .bg-glass {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
    }

    .stat-number {
        background: linear-gradient(45deg, #6366f1, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .section-title {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--primary-color);
        border-radius: 2px;
    }

    .icon-box {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .feature-item:hover .icon-box {
        transform: scale(1.1) rotate(10deg);
        background: var(--primary-color) !important;
        color: white !important;
    }

    .icon-circle {
        width: 70px;
        height: 70px;
        background: rgba(99, 102, 241, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .mission-card:hover .icon-circle {
        transform: scale(1.1);
        background: var(--primary-color);
    }

    .mission-card:hover .icon-circle i {
        color: white !important;
    }

    .cta-section {
        background: rgba(99, 102, 241, 0.03);
        border: 1px solid rgba(99, 102, 241, 0.1);
    }

    .text-body {
        color: var(--text-color) !important;
    }
</style>
@endsection
