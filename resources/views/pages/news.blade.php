@extends('layouts.navbar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/news.css') }}">

<section class="news-page">
    <div class="news-container">

        <div class="news-header">
            <span class="news-badge">Announcements</span>
            <h1>Latest News & Updates</h1>
            <p>Stay connected with the evolving fiber landscape of Fil Products Eastern Visayas.</p>
        </div>

        <div class="news-list">
            <article class="news-card promo-card">
                <div class="news-body">
                    <span class="news-tag promo">Special Promo</span>
                    <h3>Free Cable TV Promo</h3>
                    <p>Elevate your home entertainment. All residential internet plans now include <strong>FREE cable TV access</strong> at no extra cost.</p>
                    <a href="#plans" class="news-link">View Plans <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>

            <article class="news-card">
                <div class="news-image-wrapper">
                    <img src="{{ asset('public/images/customersupport.png') }}" alt="Customer Support Portal" class="news-image">
                    <span class="news-date">New Platform</span>
                </div>
                <div class="news-content">
                    <p class="news-location">
                        <i class="fas fa-laptop-code"></i> Digital Services
                    </p>
                    <h3>New Customer Service Portal</h3>
                    <p>
                        Manage your account, request technical support, or upgrade your speed instantly through our revamped digital service portal.
                    </p>
                    <a href="#" class="news-cta">
                        Explore Portal <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </article>

        </div>
    </div>
</section>

@include('layouts.footer') 

@endsection