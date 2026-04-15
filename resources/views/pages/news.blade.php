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

            <article class="news-card">
                <div class="news-image-wrapper">
                    <img src="{{ asset('images/mondragon.jfif') }}" alt="Mondragon Branch" class="news-image">
                    <span class="news-date">January 2026</span>
                </div>
                <div class="news-content">
                    <p class="news-location">
                        <i class="fas fa-map-marker-alt"></i> Mondragon, Northern Samar
                    </p>
                    <h3>Fil Products Samar – Mondragon Branch is Now Open</h3>
                    <p>
                        We are bringing high-speed fiber closer to you. Visit our new location after Cebuana Lhuillier to experience local support at its best.
                    </p>
                    <div class="news-highlight">
                        Fast • Reliable • Local Support
                    </div>
                    <a href="#" class="news-cta">
                        Visit Our Office <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </article>

            <article class="news-card">
                <div class="news-image-wrapper">
                    <img src="{{ asset('images/networkexpansion.png') }}" alt="Network Expansion" class="news-image">
                    <span class="news-date">Active Expansion</span>
                </div>
                <div class="news-content">
                    <p class="news-location">
                        <i class="fas fa-broadcast-tower"></i> Infrastructure Update
                    </p>
                    <h3>Fiber Expansion Across Samar</h3>
                    <p>
                        Our engineering team is working tirelessly to lay new fiber lines across the region, targeting 100% coverage for residential communities.
                    </p>
                    <a href="#" class="news-cta">
                        View Coverage Area <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </article>

            <article class="news-card">
                <div class="news-image-wrapper">
                    <img src="{{ asset('images/customersupport.png') }}" alt="Customer Support Portal" class="news-image">
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