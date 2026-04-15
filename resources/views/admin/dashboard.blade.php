@extends('admin.layout')

@section('content')

<h2>Admin Dashboard</h2>

<div class="card">

<div style="display:flex;gap:40px;flex-wrap:wrap;">

<!-- COMPLAINT STATUS -->

<div style="width:350px;">
<h3>Complaint Status</h3>
<canvas id="complaintChart"></canvas>
</div>


<!-- INSTALL PER DAY -->

<div style="width:500px;">
<h3>Installation Requests per Day</h3>
<canvas id="installChart"></canvas>
</div>

</div>


<div style="display:flex;gap:40px;flex-wrap:wrap;margin-top:40px;">

<!-- COMPLAINTS BY AREA -->

<div style="width:500px;">
<h3>Complaints by Area</h3>
<canvas id="areaChart"></canvas>
</div>


<!-- MONTHLY INSTALL -->

<div style="width:500px;">
<h3>Monthly Install Growth</h3>
<canvas id="monthlyInstallChart"></canvas>
</div>


<!-- TOP PROBLEM CATEGORY -->

<div style="width:350px;">
<h3>Top Problem Categories</h3>
<canvas id="categoryChart"></canvas>
</div>

</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/* =============================
LOAD DATA FROM LARAVEL
============================= */

var dashboardData = {!! json_encode([
    'pending' => $pending ?? 0,
    'assigned' => $assigned ?? 0,
    'progress' => $progress ?? 0,
    'resolved' => $resolved ?? 0,
    'closed' => $closed ?? 0,

    'installDates' => $installDates,
    'installCounts' => $installCounts,

    'areaLabels' => $areaLabels,
    'areaCounts' => $areaCounts,

    'installMonths' => $installMonths,
    'installTotals' => $installTotals,

    'categoryLabels' => $categoryLabels,
    'categoryCounts' => $categoryCounts
]) !!};


/* =====================
COMPLAINT STATUS PIE
===================== */

new Chart(document.getElementById('complaintChart'), {

type:'pie',

data:{
labels:[
'Pending',
'Assigned',
'In Progress',
'Resolved',
'Closed'
],

datasets:[{
data:[
dashboardData.pending,
dashboardData.assigned,
dashboardData.progress,
dashboardData.resolved,
dashboardData.closed
],

backgroundColor:[
'#e74c3c',
'#f39c12',
'#3498db',
'#2ecc71',
'#7f8c8d'
]
}]
}

});


/* =====================
INSTALL PER DAY
===================== */

new Chart(document.getElementById('installChart'), {

type:'line',

data:{
labels: dashboardData.installDates,

datasets:[{
label:'Install Applications',
data: dashboardData.installCounts,
borderColor:'#0d2b57',
backgroundColor:'rgba(13,43,87,0.1)',
fill:true,
tension:0.3
}]
},

options:{
scales:{
y:{
beginAtZero:true
}
}
}

});


/* =====================
COMPLAINTS BY AREA
===================== */

new Chart(document.getElementById('areaChart'), {

type:'bar',

data:{
labels: dashboardData.areaLabels,

datasets:[{
label:'Complaints',
data: dashboardData.areaCounts,
backgroundColor:'#e74c3c'
}]
},

options:{
scales:{
y:{
beginAtZero:true
}
}
}

});


/* =====================
MONTHLY INSTALL GROWTH
===================== */

new Chart(document.getElementById('monthlyInstallChart'), {

type:'line',

data:{
labels: dashboardData.installMonths,

datasets:[{
label:'Installations',
data: dashboardData.installTotals,
borderColor:'#2ecc71',
backgroundColor:'rgba(46,204,113,0.1)',
fill:true,
tension:0.3
}]
}

});


/* =====================
TOP PROBLEM CATEGORY
===================== */

new Chart(document.getElementById('categoryChart'), {

type:'pie',

data:{
labels: dashboardData.categoryLabels,

datasets:[{
data: dashboardData.categoryCounts,

backgroundColor:[
'#e74c3c',
'#f39c12',
'#3498db',
'#2ecc71',
'#9b59b6'
]
}]
}

});

</script>

@endsection