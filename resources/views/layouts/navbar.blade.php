@php
    $inquiryRoutes = ['residential.inquiry', 'residential.upgrade', 'filbiz.inquiry', 'filbiz.upgrade'];
    $isInquiry = request()->routeIs($inquiryRoutes);
    $isLeyte = request()->routeIs('leyte.*');

    /* FACEBOOK LINKS */
    $fbSamar = "https://m.me/109284174663318";
    $fbLeyte = "https://m.me/1045229935570610";
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fil Products Eastern Visayas</title>
    <link rel="icon" type="image/png" href="{{ asset('public/images/fil-products-logo.png') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-left">
            <a href="{{ route('home') }}" class="logo-wrapper">
                <img src="{{ asset('public/images/fil-products-logo.png') }}" alt="Fil Products Logo" class="logo">
                <div class="brand-info">
                    <span class="brand-label">Fil Products</span>
                    <span class="brand-sub">Butuan</span>
                </div>
            </a>
        </div>

        <div class="nav-right" id="navMenu">
            <a href="{{ route('home') }}" class="nav-btn {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i> <span>Home</span>
            </a>

            <a href="{{ route('news') }}" class="nav-btn {{ request()->routeIs('news') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> <span>News</span>
            </a>

            <div class="dropdown" id="inquiryDropdown">
                <div class="nav-btn dropdown-toggle {{ $isInquiry ? 'active' : '' }}" onclick="toggleDropdown(event,'inquiryDropdown')">
                    <i class="fas fa-paper-plane"></i> <span>Apply Now</span>
                    <i class="fas fa-angle-down arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('residential.inquiry') }}">Residential Application</a>
                    <a href="{{ route('residential.upgrade') }}">Residential Upgrade</a>
                    <div class="submenu-divider"></div>
                    <a href="{{ route('filbiz.inquiry') }}">Filbiz Application</a>
                    <a href="{{ route('filbiz.upgrade') }}">Filbiz Upgrade</a>
                </div>
            </div>

            <a href="{{ route('complaint') }}" class="nav-btn {{ request()->routeIs('complaint') ? 'active' : '' }}">
                <i class="fas fa-headset"></i> <span>Support</span>
            </a>

            <a href="{{ route('branch') }}" class="nav-btn {{ request()->routeIs('branch') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt"></i> <span>Branches</span>
            </a>

            <a href="{{ route('about') }}" class="nav-btn {{ request()->routeIs('about') ? 'active' : '' }}">
                <i class="fas fa-info-circle"></i> <span>About</span>
            </a>
        </div>

        <div class="menu-toggle" onclick="toggleMenu(this)">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<div class="chat-wrapper">
    <div id="chat-toggle">
        <i class="fas fa-message"></i>
        <span class="notification-ping"></span>
    </div>

    <div id="chat-panel">
        <div class="chat-header">
            <div class="header-user">
                <img src="{{ asset('images/fil-products-logo.png') }}" alt="Logo">
                <div>
                    <strong>Fil Support</strong>
                    <div class="status-wrap"><span class="dot"></span> <small>Online Now</small></div>
                </div>
            </div>
            <button class="chat-close" onclick="toggleChat()">✕</button>
        </div>

        <div class="chat-body">
            <p class="greetings" style="color: black;">👋 Welcome! How can we help you today?</p>
            
            <button class="chat-option messenger" onclick="openModal('chatModal')">
                <i class="fab fa-facebook-messenger"></i> Chat on Messenger
            </button>

            <button class="chat-option call" onclick="openModal('callModal')">
                <i class="fas fa-phone-alt"></i> Call Customer Support
            </button>

            <button class="chat-option apply" onclick="openModal('applyModal')">
                <i class="fas fa-file-signature"></i> Online Application
            </button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modalOverlay">
    <div class="modal" id="callModal">
        <div class="modal-header">
            <h3>Select Network</h3>
            <button onclick="closeModal('callModal')">✕</button>
        </div>
        <div class="modal-body">
            <a href="tel:+639173205871" class="modal-item globe">
                <span class="icon"><i class="fas fa-mobile-alt"></i></span>
                <div><strong>Globe</strong><span>0917-320-5871</span></div>
            </a>
            <a href="tel:+639383205871" class="modal-item smart">
                <span class="icon"><i class="fas fa-mobile-alt"></i></span>
                <div><strong>Smart</strong><span>0938-320-5871</span></div>
            </a>
        </div>
    </div>

    <div class="modal" id="applyModal">
        <div class="modal-header">
            <h3>Start Application</h3>
            <button onclick="closeModal('applyModal')">✕</button>
        </div>
        <div class="modal-body">
            <a href="{{ route('residential.inquiry') }}" class="modal-item residential">
                <span class="icon"><i class="fas fa-home"></i></span>
                <div><strong>Home Fiber</strong><span>Residential Plans</span></div>
            </a>
            <a href="{{ route('filbiz.inquiry') }}" class="modal-item business">
                <span class="icon"><i class="fas fa-building"></i></span>
                <div><strong>FilBiz</strong><span>Enterprise Solutions</span></div>
            </a>
        </div>
    </div>

    <div class="modal" id="chatModal">
        <div class="modal-header">
            <h3>Choose Branch</h3>
            <button onclick="closeModal('chatModal')">✕</button>
        </div>
        <div class="modal-body">
            <a href="{{ $fbSamar }}" target="_blank" class="modal-item samar">
                <span class="icon"><i class="fab fa-facebook-f"></i></span>
                <div><strong>Fil Products Samar</strong><span>Messenger</span></div>
            </a>
            <a href="{{ $fbLeyte }}" target="_blank" class="modal-item leyte">
                <span class="icon"><i class="fab fa-facebook-f"></i></span>
                <div><strong>Fil Products Leyte</strong><span>Messenger</span></div>
            </a>
        </div>
    </div>
</div>

<script>
    // NAVIGATION LOGIC
    function toggleDropdown(event, id) {
        event.stopPropagation();
        const el = document.getElementById(id);
        const isOpen = el.classList.contains('open');
        
        document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('open'));
        if (!isOpen) el.classList.add('open');
    }

    function toggleMenu(el) {
        document.getElementById("navMenu").classList.toggle("open");
        el.classList.toggle("active");
    }

    // CHAT LOGIC
    function toggleChat() {
        const panel = document.getElementById("chat-panel");
        panel.classList.toggle("active");
    }
    document.getElementById("chat-toggle").onclick = toggleChat;

    // MODAL LOGIC
    function openModal(id) {
        document.getElementById('modalOverlay').classList.add('active');
        document.querySelectorAll('.modal').forEach(m => m.classList.remove('active'));
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById('modalOverlay').classList.remove('active');
        document.getElementById(id).classList.remove('active');
    }

    window.onclick = function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            document.getElementById('modalOverlay').classList.remove('active');
        }
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('open'));
        }
    };
</script>

</body>
</html>