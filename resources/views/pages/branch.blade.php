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
                    <h3>Leyte Main Office</h3>
                </div>

                <div class="contact-detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Address</strong>
                        <p>City Center Park Real St., Brgy Aslum, Tacloban City, Leyte</p>
                    </div>
                </div>

                <div class="contact-detail-item">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <strong>Contact Numbers</strong>
                        <p>0995-415-1821</p>
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
                    <a href="https://www.google.com/maps/place/Fil+Products+Leyte/@11.2246524,125.0000159,17z/data=!3m1!4b1!4m6!3m5!1s0x33087722a4ee2067:0x8312bbc3e15c611e!8m2!3d11.2246471!4d125.0025908!16s%2Fg%2F1hc11y2h7?hl=en&entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>
            </div>

            <div class="branch-map-wrapper">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3913.499121326314!2d125.00259080000001!3d11.224647099999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33087722a4ee2067%3A0x8312bbc3e15c611e!2sFil%20Products%20Leyte!5e0!3m2!1sen!2sph!4v1777857718932!5m2!1sen!2sph"
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