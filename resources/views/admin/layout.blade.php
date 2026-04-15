<!DOCTYPE html>
<html>
<head>

<title>Fil Products Admin</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

/* SIDEBAR */

.sidebar{
width:240px;
height:100vh;
background:#0d2b57;
position:fixed;
color:white;
transition:0.3s;
overflow:hidden;
}

.sidebar.collapsed{
width:70px;
}

.logo-box{
text-align:center;
padding:20px 10px 10px;
}

.logo-box img{
width:60px;
margin-bottom:6px;
}

.logo-box h2{
margin:0;
font-size:16px;
}

.sidebar.collapsed .logo-box h2{
display:none;
}

.sidebar a{
display:block;
padding:14px 20px;
color:white;
text-decoration:none;
transition:0.2s;
white-space:nowrap;
}

.sidebar a i{
width:25px;
}

.sidebar.collapsed a span{
display:none;
}

.sidebar a:hover{
background:#1d4c8f;
}

.sidebar a.active{
background:#1d4c8f;
border-left:4px solid #ffcc00;
font-weight:bold;
}

/* CONTENT */

.content{
margin-left:240px;
padding:25px;
transition:0.3s;
}

.content.expanded{
margin-left:70px;
}

/* TOPBAR */

.topbar{
height:50px;
background:white;
box-shadow:0 2px 6px rgba(0,0,0,0.1);
display:flex;
align-items:center;
padding:0 20px;
margin-bottom:20px;
}

.toggle-btn{
font-size:20px;
cursor:pointer;
color:#0d2b57;
}

/* CARD */

.card{
background:white;
padding:20px;
border-radius:8px;
box-shadow:0 2px 8px rgba(0,0,0,0.08);
margin-bottom:20px;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
}

table th,table td{
padding:10px;
border-bottom:1px solid #ddd;
}

/* BUTTON */

.btn{
background:#0d2b57;
color:white;
padding:6px 12px;
border-radius:4px;
text-decoration:none;
}

.sidebar-header{
display:flex;
align-items:center;
justify-content:space-between;
padding:15px;
border-bottom:1px solid rgba(255,255,255,0.2);
}

/* TITLE */

.logo-title h2{
font-size:16px;
margin:0;
color:white;
transition:0.3s;
}

/* TOGGLE BUTTON */

.toggle-btn{
background:none;
border:none;
color:white;
font-size:18px;
cursor:pointer;
}

/* COLLAPSED SIDEBAR */

.sidebar.collapsed{
width:70px;
}

.sidebar.collapsed .logo-title h2{
display:none;
}

.sidebar.collapsed a span{
display:none;
}

/* CONTENT SHIFT */

.content{
margin-left:240px;
transition:0.3s;
}

.content.expanded{
margin-left:70px;
}
/* SUBMENU */

.submenu{
display:none;
background:#163b73;
}

.submenu a{
padding-left:45px;
font-size:14px;
}

.menu-item.active .submenu{
display:block;
}
</style>

</head>

<body>

<div class="sidebar" id="sidebar">

<div class="sidebar-header">

<div class="logo-title">
<h2>Fil Products Samar</h2>
</div>

<button class="toggle-btn" onclick="toggleMenu()">
<i class="fa fa-bars"></i>
</button>

</div>

<a href="{{ route('admin.dashboard') }}"
class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
<i class="fa fa-chart-line"></i> <span>Dashboard</span>
</a>

<a href="{{ route('admin.complaints') }}"
class="{{ request()->routeIs('admin.complaints') ? 'active' : '' }}">
<i class="fa fa-exclamation-circle"></i> <span>Complaints</span>
</a>

<div class="menu-item {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">

<a href="javascript:void(0)" onclick="toggleSubmenu(this)">
<i class="fa fa-file"></i> <span>Applications</span>
<i class="fa fa-chevron-down" style="float:right"></i>
</a>

<div class="submenu">

<a href="{{ route('admin.applications.residential') }}"
class="{{ request()->routeIs('admin.applications.residential') ? 'active' : '' }}">
<i class="fa fa-house"></i> <span>Residential</span>
</a>

<a href="{{ route('admin.applications.filbiz') }}"
class="{{ request()->routeIs('admin.applications.filbiz') ? 'active' : '' }}">
<i class="fa fa-building"></i> <span>FilBiz</span>
</a>

</div>

</div>

<a href="{{ route('admin.areas') }}"
class="{{ request()->routeIs('admin.areas') ? 'active' : '' }}">
<i class="fa fa-map-location-dot"></i> <span>Area Management</span>
</a>

</div>

<div class="content" id="content">

@yield('content')

</div>

<script>

function toggleMenu(){

let sidebar = document.getElementById("sidebar");
let content = document.getElementById("content");

sidebar.classList.toggle("collapsed");
content.classList.toggle("expanded");

}

function toggleSubmenu(element){

let parent = element.parentElement;

parent.classList.toggle("active");

}

</script>

</body>
</html>