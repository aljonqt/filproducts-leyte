<!DOCTYPE html>
<html>
<head>
    <title>Fil Products Staff Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    display: flex;
    background: #f4f6f9;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 240px;
    height: 100vh;
    background: linear-gradient(180deg, #003366, #001f3f);
    color: white;
    position: fixed;
    display: flex;
    flex-direction: column;
    transition: 0.3s;
}

/* COLLAPSED */
.sidebar.collapsed {
    width: 80px;
}

/* ===== LOGO ===== */
.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    transition: 0.3s;
}

.logo-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    transition: 0.3s;
}

.logo-text {
    font-size: 15px;
    font-weight: bold;
    transition: 0.3s;
}

/* COLLAPSED LOGO */
.sidebar.collapsed .logo {
    justify-content: center;
}

.sidebar.collapsed .logo-text {
    display: none;
}

.sidebar.collapsed .logo-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: white;
    padding: 6px;
}

.toggle-btn {
    position: absolute;
    top: 20px;
    right: -18px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: none;
    cursor: pointer;

    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: white;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;

    box-shadow: 0 8px 20px rgba(0, 114, 255, 0.35);
    transition: 0.3s;
}

/* HOVER EFFECT */
.toggle-btn:hover {
    transform: scale(1.1) rotate(180deg);
    box-shadow: 0 10px 25px rgba(0, 114, 255, 0.5);
}

/* ===== MENU ===== */
.menu {
    flex: 1;
    padding-top: 10px;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    color: white;
    text-decoration: none;
    transition: 0.3s;
}

/* ICON */
.menu a .icon {
    font-size: 18px;
    min-width: 24px;
    text-align: center;
}

/* TEXT */
.menu a .text {
    transition: 0.3s;
}

/* COLLAPSED MENU */
.sidebar.collapsed .menu a {
    justify-content: center;
    padding: 14px 0;
}

.sidebar.collapsed .menu a .text {
    display: none;
}

.menu a:hover {
    background: rgba(255,255,255,0.06);
    border-radius: 12px;
    transform: translateX(4px);
}

/* MODERN ACTIVE MENU */
.menu a.active {
    background: rgba(0, 114, 255, 0.12);
    border-radius: 14px;
    margin: 6px 10px;
    position: relative;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 12px rgba(0, 114, 255, 0.15);
}

/* LEFT BLUE INDICATOR */
.menu a.active::before {
    content: "";
    position: absolute;
    left: 0;
    top: 8px;
    bottom: 8px;
    width: 4px;
    border-radius: 4px;
    background: linear-gradient(180deg, #00c6ff, #0072ff);
}

/* ACTIVE ICON + TEXT */
.menu a.active .icon i,
.menu a.active .text {
    color: #0072ff;
    font-weight: 600;
}

/* ACTIVE WHEN COLLAPSED */
.sidebar.collapsed .menu a.active {
    margin: 6px;
}

/* ===== MAIN ===== */
.main {
    margin-left: 240px;
    width: 100%;
    display: flex;
    flex-direction: column;
    transition: 0.3s;
}

.sidebar.collapsed ~ .main {
    margin-left: 80px;
}

/* ===== HEADER ===== */
.header {
    background: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.logout-btn {
    background: #ff4d4d;
    border: none;
    color: white;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
}

.logout-btn:hover {
    background: #cc0000;
}

/* ===== CONTENT ===== */
.content {
    padding: 25px;
}

/* ===== CARD ===== */
.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .sidebar {
        width: 180px;
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .main {
        margin-left: 180px;
    }

    .sidebar.collapsed ~ .main {
        margin-left: 70px;
    }
}
/* ===== MENU GROUP ===== */
.menu-group {
    display: flex;
    flex-direction: column;
}

/* TOGGLE HEADER */
.menu-toggle {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    cursor: pointer;
    position: relative;
}

/* ARROW ICON */
.menu-toggle .arrow {
    margin-left: auto;
    transition: 0.3s;
}

/* ROTATE WHEN OPEN */
.menu-group.open .arrow {
    transform: rotate(180deg);
}

/* SUBMENU */
.submenu {
    display: none;
    flex-direction: column;
    padding-left: 20px;
}

/* SHOW SUBMENU */
.menu-group.open .submenu {
    display: flex;
}

/* SUBMENU ITEMS */
.submenu a {
    padding: 10px 20px;
    font-size: 14px;
    opacity: 0.9;
}

/* INDENT STYLE */
.submenu a .icon {
    font-size: 14px;
}

/* COLLAPSED (HIDE SUBMENU TEXT) */
.sidebar.collapsed .submenu {
    display: none !important;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleSidebar()">⮜</button>

    <div class="logo">
        <img src="{{ asset('images/fil-products-logo.png') }}" class="logo-img">
        <span class="logo-text">Fil Products Samar</span>
    </div>

<div class="menu">

    <a href="{{ route('staff.home') }}" 
       class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
        <span class="icon"><i class="fas fa-home"></i></span>
        <span class="text">Home</span>
    </a>

    <a href="{{ route('staff.complaints') }}" 
       class="{{ request()->routeIs('staff.complaints') ? 'active' : '' }}">
        <span class="icon"><i class="fas fa-phone"></i></span>
        <span class="text">Complaints</span>
    </a>

<!-- APPLICATIONS MENU -->
<div class="menu-group" {{ request()->routeIs('staff.installations.*') ? 'open' : '' }}">

    <a href="javascript:void(0)" class="menu-toggle" onclick="toggleSubmenu(this)">
        <span class="icon"><i class="fas fa-layer-group"></i></span>
        <span class="text">Applications</span>
        <span class="arrow"><i class="fas fa-chevron-down"></i></span>
    </a>

    <div class="submenu">

        <a href="{{ route('staff.installations.residential') }}" 
           class="{{ request()->routeIs('staff.installations.residential') ? 'active' : '' }}">
            <span class="icon"><i class="fas fa-house"></i></span>
            <span class="text">Residential</span>
        </a>

        <a href="{{ route('staff.installations.filbiz') }}" 
           class="{{ request()->routeIs('staff.installations.filbiz') ? 'active' : '' }}">
            <span class="icon"><i class="fas fa-building"></i></span>
            <span class="text">Filbiz</span>
        </a>

    </div>

</div>
    </a>

</div>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">
    @php
    $hour = now()->setTimezone('Asia/Manila')->format('H');

    if ($hour < 12) {
        $greeting = 'Good Morning';
    } elseif ($hour < 18) {
        $greeting = 'Good Afternoon';
    } else {
        $greeting = 'Good Evening';
    }

    $user = auth()->user();
@endphp

<h3>
    {{ $greeting }}, {{ $user->firstname ?? 'Staff' }} 👋
</h3>

    <form method="POST" action="{{ route('staff.logout') }}">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>
</div>

<div class="content">
    @yield('content')
</div>

</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const btn = document.querySelector('.toggle-btn');

    sidebar.classList.toggle('collapsed');

    // arrow toggle
    if (sidebar.classList.contains('collapsed')) {
        btn.innerHTML = '⮞';
    } else {
        btn.innerHTML = '⮜';
    }
}
function toggleSubmenu(el) {
    const group = el.parentElement;
    group.classList.toggle('open');
}
</script>

</body>
</html>