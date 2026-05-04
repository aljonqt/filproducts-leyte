@extends('layouts.navbar')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">

<!-- HERO -->


<section class="hero-section">
    <div class="glow-blob"></div>
    
    <div class="container hero-grid">
        
        <div class="hero-content">
            <div class="badge-wrapper">
                <span class="status-indicator animate-pulse"></span>
                <span class="hero-badge">Fiber & Cable TV now live in Leyte</span>
            </div>

            <h1 class="hero-title">
                Experience the Speed of <br>
                <span class="gradient-text">Pure Fiber Connectivity</span>
            </h1>

            <p class="hero-description">
                Unleash the full potential of your home and business with Leyte's most reliable network. High-speed internet meets premium entertainment.
            </p>

            <div class="hero-actions">
                <div class="button-group">
                    <a href="{{ route('residential.inquiry') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> 
                        <span>For Home</span>
                    </a>
                    <a href="{{ route('filbiz.inquiry') }}" class="btn btn-secondary">
                        <i class="fas fa-building"></i> 
                        <span>For Business</span>
                    </a>
                    <a href="{{ route('complaint') }}" class="btn btn-outline">
                        <i class="fas fa-headset"></i> 
                        <span>Support & Complaints</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="device-composition">
                <div class="device-wrapper modem-wrap">
                    <img src="{{ asset('public/images/modem.png') }}" alt="Fiber Router" class="floating-img">
                    <div class="glass-card card-internet">
                        <div class="icon-box"><i class="fas fa-bolt"></i></div>
                        <div>
                            <p class="card-label">Fiber Speed</p>
                        </div>
                    </div>
                </div>

                <div class="device-wrapper tv-wrap">
                    <img src="{{ asset('public/images/tv.png') }}" alt="HD Cable TV" class="floating-img delay-1">
                    <div class="glass-card card-tv">
                        <div class="icon-box"><i class="fas fa-tv"></i></div>
                        <div>
                            <p class="card-label">HD Channels</p>
                            <p class="card-value">64 Stations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


<section class="services">
    <div class="services-container">
        <div class="section-header">
            <span class="pill-badge">Our Advantages</span>
            <h2>Why Choose Fil Products?</h2>
            <p class="section-sub">
                Reliable Internet and Cable TV built for homes and businesses in Leyte.
            </p>
        </div>

        <div class="service-grid">
            <div class="service-card">
                <div class="card-glow"></div>
                <div class="icon-wrapper">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="card-content">
                    <h3>High Speed Fiber</h3>
                    <p>Experience ultra-fast, symmetrical fiber internet built for 8K streaming and lag-free gaming.</p>
                </div>
            </div>

            <div class="service-card">
                <div class="card-glow"></div>
                <div class="icon-wrapper">
                    <i class="fas fa-tv"></i>
                </div>
                <div class="card-content">
                    <h3>Free Cable TV</h3>
                    <p>Get the best of both worlds. Premium HD channels included in your fiber plan at no extra cost.</p>
                </div>
            </div>

            <div class="service-card">
                <div class="card-glow"></div>
                <div class="icon-wrapper">
                    <i class="fas fa-building"></i>
                </div>
                <div class="card-content">
                    <h3>Enterprise Grade</h3>
                    <p>Custom-tailored dedicated bandwidth and static IP solutions for Leyte's growing businesses.</p>
                </div>
            </div>

            <div class="service-card">
                <div class="card-glow"></div>
                <div class="icon-wrapper">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="card-content">
                    <h3>24/7 Local Care</h3>
                    <p>No more robot voices. Speak directly to our Leyte-based team whenever you need help.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="packages-section">
    <div class="container">
        <div class="section-header">
            <span class="pill-badge">Internet Plans</span>
            <h2>Choose Your Speed</h2>
            <p class="section-sub">Affordable high-speed internet packages for every household and business.</p>
        </div>

        <div class="category-header">
            <i class="fas fa-home"></i> <h3>Residential Plans</h3>
        </div>

        <div class="pricing-grid">
            <div class="price-card">
                <div class="plan-type">Starter</div>
                <div class="speed-value">50<span>Mbps</span></div>
                <div class="price-value">₱799</div>
                <ul class="features">
                    <li><i class="fas fa-check"></i> Unlimited Internet</li>
                    <li><i class="fas fa-check"></i> Free Cable TV</li>
                </ul>
                <a href="{{ route('residential.inquiry') }}" class="btn-plan">Apply Now</a>
            </div>

            <div class="price-card popular">
                <div class="popular-badge">Most Popular</div>
                <div class="plan-type">Standard</div>
                <div class="speed-value">80<span>Mbps</span></div>
                <div class="price-value">₱899</div>
                <ul class="features">
                    <li><i class="fas fa-check"></i> Unlimited Internet</li>
                    <li><i class="fas fa-check"></i> Free Cable TV</li>
                </ul>
                <a href="{{ route('residential.inquiry') }}" class="btn-plan primary">Apply Now</a>
            </div>

            <div class="price-card">
                <div class="plan-type">Premium</div>
                <div class="speed-value">100<span>Mbps</span></div>
                <div class="price-value">₱999</div>
                <ul class="features">
                    <li><i class="fas fa-check"></i> Unlimited Internet</li>
                    <li><i class="fas fa-check"></i> Free Cable TV</li>
                </ul>
                <a href="{{ route('residential.inquiry') }}" class="btn-plan">Apply Now</a>
            </div>
        </div>

        <div class="pricing-grid">
            <div class="price-card">
                <div class="plan-type">Plus</div>
                <div class="speed-value">150<span>Mbps</span></div>
                <div class="price-value">₱1,199</div>
                <ul class="features">
                    <li><i class="fas fa-check"></i> Unlimited Internet</li>
                    <li><i class="fas fa-check"></i> Free Cable TV</li>
                </ul>
                <a href="{{ route('residential.inquiry') }}" class="btn-plan">Apply Now</a>
            </div>

            <div class="price-card">
                <div class="plan-type">Pro</div>
                <div class="speed-value">250<span>Mbps</span></div>
                <div class="price-value">₱1,299</div>
                <ul class="features">
                    <li><i class="fas fa-check"></i> Unlimited Internet</li>
                    <li><i class="fas fa-check"></i> Free Cable TV</li>
                </ul>
                <a href="{{ route('residential.inquiry') }}" class="btn-plan">Apply Now</a>
            </div>

            <div class="price-card">
                <div class="plan-type">Ultra</div>
                <div class="speed-value">300<span>Mbps</span></div>
                <div class="price-value">₱1,499</div>
                <ul class="features">
                    <li><i class="fas fa-check"></i> Unlimited Internet</li>
                    <li><i class="fas fa-check"></i> Free Cable TV</li>
                </ul>
                <a href="{{ route('residential.inquiry') }}" class="btn-plan">Apply Now</a>
            </div>
        </div>

        <div class="category-header biz">
            <i class="fas fa-building"></i> <h3>Business Plans</h3>
        </div>

        <div class="pricing-grid">
            <div class="price-card biz-card">
                <div class="plan-type">Business Starter</div>
                <div class="speed-value">100<span>Mbps</span></div>
                <div class="price-value">₱1,299</div>
                <ul class="features">
                    <li><i class="fas fa-briefcase"></i> Stable Connectivity</li>
                    <li><i class="fas fa-check"></i> Business Ready</li>
                    <li><i class="fas fa-headset"></i> Priority Support</li>
                </ul>
                <a href="{{ route('filbiz.inquiry') }}" class="btn-plan">Apply Now</a>
            </div>

            <div class="price-card biz-card">
                <div class="plan-type">Business Pro</div>
                <div class="speed-value">200<span>Mbps</span></div>
                <div class="price-value">₱1,599</div>
                <ul class="features">
                    <li><i class="fas fa-briefcase"></i> Stable Connectivity</li>
                    <li><i class="fas fa-check"></i> Business Ready</li>
                    <li><i class="fas fa-headset"></i> Priority Support</li>
                </ul>
                <a href="{{ route('filbiz.inquiry') }}" class="btn-plan">Apply Now</a>
            </div>

            <div class="price-card biz-card featured-biz">
                <div class="plan-type">Business Premium</div>
                <div class="speed-value">300<span>Mbps</span></div>
                <div class="price-value">₱1,899</div>
                <ul class="features">
                    <li><i class="fas fa-briefcase"></i> Stable Connectivity</li>
                    <li><i class="fas fa-check"></i> Business Ready</li>
                    <li><i class="fas fa-headset"></i> Priority Support</li>
                </ul>
                <a href="{{ route('filbiz.inquiry') }}" class="btn-plan primary">Apply Now</a>
            </div>

            <div class="price-card biz-card">
                <div class="plan-type">Enterprise</div>
                <div class="speed-value">500<span>Mbps</span></div>
                <div class="price-value">₱2,000</div>
                <ul class="features">
                    <li><i class="fas fa-briefcase"></i> Stable Connectivity</li>
                    <li><i class="fas fa-check"></i> Business Ready</li>
                    <li><i class="fas fa-headset"></i> Priority Support</li>
                </ul>
                <a href="{{ route('filbiz.inquiry') }}" class="btn-plan">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<section class="news-section">
    <div class="container">
        <div class="section-header">
            <span class="pill-badge">Stay Connected</span>
            <h2>Latest Updates & Announcements</h2>
        </div>

        <div class="news-grid">
            <article class="news-card promo-card">
                <div class="news-body">
                    <span class="news-tag promo">Special Promo</span>
                    <h3>Free Cable TV Promo</h3>
                    <p>Elevate your home entertainment. All residential internet plans now include <strong>FREE cable TV access</strong> at no extra cost.</p>
                    <a href="#plans" class="news-link">View Plans <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>

            <article class="news-card">
                <div class="image-container">
                    <img src="{{ asset('public/images/customersupport.png') }}" alt="Customer Portal" class="news-image">
                </div>
                <div class="news-body">
                    <span class="news-tag tech">Digital Service</span>
                    <h3>Online Customer Portal</h3>
                    <p>Subscribers can now submit inquiries, upgrade requests, and complaints through our new online customer portal for faster response times.</p>
                    <a href="{{ route('complaint') }}" class="news-link">Go to Portal <i class="fas fa-external-link-alt"></i></a>
                </div>
            </article>
        </div>
    </div>
</section>



<script>
const slides = document.querySelectorAll(".slide");
const nextBtn = document.querySelector(".next");
const prevBtn = document.querySelector(".prev");

let index = 0;

function updateSlider() {
  slides.forEach(slide => {
    slide.classList.remove("active", "prev", "next");
  });

  const prevIndex = (index - 1 + slides.length) % slides.length;
  const nextIndex = (index + 1) % slides.length;

  slides[index].classList.add("active");
  slides[prevIndex].classList.add("prev");
  slides[nextIndex].classList.add("next");
}

/* BUTTONS */
nextBtn.addEventListener("click", () => {
  index = (index + 1) % slides.length;
  updateSlider();
});

prevBtn.addEventListener("click", () => {
  index = (index - 1 + slides.length) % slides.length;
  updateSlider();
});

/* AUTO SLIDE */
let autoSlide = setInterval(() => {
  index = (index + 1) % slides.length;
  updateSlider();
}, 4000);

/* PAUSE ON HOVER (Desktop UX) */
document.querySelector(".slider").addEventListener("mouseenter", () => {
  clearInterval(autoSlide);
});

document.querySelector(".slider").addEventListener("mouseleave", () => {
  autoSlide = setInterval(() => {
    index = (index + 1) % slides.length;
    updateSlider();
  }, 4000);
});

/* INIT */
updateSlider();
</script>

@include('layouts.footer') 

@endsection