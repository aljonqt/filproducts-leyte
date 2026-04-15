@extends('layouts.navbar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/branch.css') }}">

<section class="branch-page">
    <div class="branch-container">
        <div class="branch-header">
            <span class="pill-badge">Our Headquarters</span>
            <h1>Visit Our Office</h1>
            <p>For applications, inquiries, payments, and walk-in technical support.</p>
        </div>

        <div class="single-branch-wrapper">
            <div class="branch-info-card">
                <div class="branch-title">
                    <div class="icon-box"><i class="fas fa-building"></i></div>
                    <h3>Butuan Main Office</h3>
                </div>

                <div class="contact-detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Address</strong>
                        <p>N&D Bldg., Alviola Village Baan KM. Butuan City</p>
                    </div>
                </div>

                <div class="contact-detail-item">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <strong>Contact Numbers</strong>
                        <p>0948-921-1463</p>
                        <p>0938-583-2337</p>
                    </div>
                </div>

                <div class="contact-detail-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Office Hours</strong>
                        <p>Monday - Saturday: 8:00 AM - 5:00 PM</p>
                        <p>Sunday: Closed</p>
                    </div>
                </div>

                <div class="branch-actions">
                    <a href="https://www.google.com/maps/place/Fil+Products+Butuan/@8.9528842,125.5887402,17z/data=!3m1!4b1!4m6!3m5!1s0x3301eb0006825e1f:0x29f4c65809ea41c0!8m2!3d8.9528842!4d125.5887402!16s%2Fg%2F11yn5b2nqj?entry=ttu&g_ep=EgoyMDI2MDQwOC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="map-btn">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>
            </div>

            <div class="branch-map-wrapper">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3941.208954288358!2d125.5887402!3d8.9528842!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3301eb0006825e1f%3A0x29f4c65809ea41c0!2sFil%20Products%20Butuan!5e0!3m2!1sen!2sph!4v1776155958906!5m2!1sen!2sph"
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer') 

@endsection