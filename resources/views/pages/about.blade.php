@extends('layouts.navbar')

@section('content')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/about.css') }}">


<section class="about-page">

<div class="about-container">

<div class="about-header">

<h1>About Fil Products</h1>

<p>Delivering reliable internet and cable television services across Leyte .</p>

</div>


<div class="about-content">

<div class="about-text">

<h2>FIL PRODUCTS LEYTE.</h2>

<p>
Fil Products Leyte, is a trusted provider of
high-speed internet and cable television services in Leyte . We are committed
to delivering reliable connectivity, modern technology, and exceptional
customer service to homes, businesses, and communities.
</p>

<p>
Our goal is to ensure that every subscriber enjoys fast, stable, and
affordable digital services that empower communication, entertainment,
education, and business growth.
</p>

</div>

</div>


<!-- MISSION VISION -->

<div class="mission-vision">

<div class="mv-card">

<i class="fas fa-bullseye"></i>

<h3>Our Mission</h3>

<p>
To provide reliable and affordable internet and cable television services
while continuously improving technology, infrastructure, and customer
experience.
</p>

</div>


<div class="mv-card">

<i class="fas fa-eye"></i>

<h3>Our Vision</h3>

<p>
To become the leading connectivity provider in Leyte  by delivering
innovative digital services and empowering communities through technology.
</p>

</div>

</div>


<!-- WHY CHOOSE -->

<div class="why">

<h2>Why Choose Fil Products?</h2>

<div class="why-grid">

<div class="why-card">
<i class="fas fa-wifi"></i>
<h3>Fast Internet</h3>
<p>Reliable fiber internet for homes and businesses.</p>
</div>

<div class="why-card">
<i class="fas fa-tv"></i>
<h3>Free Cable TV</h3>
<p>Enjoy premium channels bundled with your internet.</p>
</div>

<div class="why-card">
<i class="fas fa-headset"></i>
<h3>Local Support</h3>
<p>Dedicated local support team ready to assist you.</p>
</div>

<div class="why-card">
<i class="fas fa-network-wired"></i>
<h3>Modern Network</h3>
<p>Advanced fiber infrastructure for stable connectivity.</p>
</div>

</div>

</div>

</div>

</section>


@include('layouts.footer') 

@endsection