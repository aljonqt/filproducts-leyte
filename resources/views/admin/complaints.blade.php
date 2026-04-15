@extends('admin.layout')

@section('content')

<style>
.complaint-table{
width:100%;
border-collapse:collapse;
table-layout:fixed;
}

.complaint-table th,
.complaint-table td{
padding:12px;
border-bottom:1px solid #ddd;
text-align:left;
vertical-align:middle;
}

.complaint-table th{
background:#f4f6f9;
font-weight:600;
}

.center{text-align:center;}
.remarks{word-wrap:break-word;}

.status{font-weight:600;}
.status.pending{color:red;}
.status.assigned{color:#ff9800;}
.status.progress{color:#2196f3;}
.status.resolved{color:green;}
.status.closed{color:#555;}

.status-select{
padding:8px 12px;
border-radius:8px;
border:1px solid #d1d5db;
background:#ffffff;
font-size:13px;
cursor:pointer;
min-width:130px;
}
</style>

<h2>Customer Complaints</h2>

<div class="card">

<!-- FILTER -->
<form method="GET" action="{{ route('admin.complaints') }}" style="margin-bottom:15px;">

<input type="text"
name="search"
placeholder="Search account, area, or category"
value="{{ request('search') }}"
style="padding:8px;width:220px;border:1px solid #ccc;border-radius:4px;">

<select name="sort_area" style="padding:8px;border-radius:4px;">
<option value="">Sort by Area</option>
<option value="asc" {{ request('sort_area')=='asc'?'selected':'' }}>Area A-Z</option>
<option value="desc" {{ request('sort_area')=='desc'?'selected':'' }}>Area Z-A</option>
</select>

<select name="sort_date" style="padding:8px;border-radius:4px;">
<option value="">Sort by Date</option>
<option value="desc" {{ request('sort_date')=='desc'?'selected':'' }}>Newest First</option>
<option value="asc" {{ request('sort_date')=='asc'?'selected':'' }}>Oldest First</option>
</select>

<button class="btn">Filter</button>

<a href="{{ route('admin.complaints') }}" class="btn" style="background:#777;">
Reset
</a>

</form>


<!-- TRANSMITTAL FORM -->
<form method="POST" action="{{ route('admin.transmittal.generate') }}" id="transmittalForm">
@csrf

<div style="display:flex; justify-content:flex-end; gap:10px; margin-bottom:15px;">

<select name="area" id="areaSelect" style="padding:8px;border-radius:4px;">
<option value="">Generate by Area (if no customers selected)</option>
<option value="Calbayog (Carayman) Area">Calbayog (Carayman) Area</option>
<option value="Calbayog (Central) Area">Calbayog (Central) Area</option>
<option value="Oquendo Dist Area">Oquendo Dist Area</option>
<option value="Tinambacan Dist Area">Tinambacan Dist Area</option>
<option value="Catbalogan Area">Catbalogan Area</option>
<option value="Allen Area">Allen Area</option>
<option value="Bobon Area">Bobon Area</option>
<option value="Catarman Area">Catarman Area</option>
</select>

<button type="submit"
class="btn"
style="background:#0d2b57;color:white;">
Generate Transmittal PDF
</button>

</div>


<table class="complaint-table">

<thead>
<tr>

<th class="center">
<label>
<input type="checkbox" id="selectAll"> Select All
</label>
</th>

<th>Ticket No</th>
<th>Account Name</th>
<th>Address</th>
<th>Area</th>
<th>Category</th>
<th>Remarks</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>

</tr>
</thead>

<tbody>

@foreach($complaints as $row)

<tr>

<td class="center">
<input type="checkbox" name="customers[]" value="{{ $row->id }}" class="customer-checkbox">
</td>

<td>{{ $row->ticket_number }}</td>
<td>{{ $row->account_name }}</td>
<td>{{ $row->address }}</td>
<td>{{ $row->area }}</td>
<td>{{ $row->category }}</td>
<td class="remarks">{{ $row->remarks }}</td>

<td class="center">

@if($row->status=="Pending")
<span class="status pending">Pending</span>
@elseif($row->status=="Assigned")
<span class="status assigned">Assigned</span>
@elseif($row->status=="In Progress")
<span class="status progress">In Progress</span>
@elseif($row->status=="Resolved")
<span class="status resolved">Resolved</span>
@elseif($row->status=="Closed")
<span class="status closed">Closed</span>
@endif

</td>

<td class="center">
{{ date('M d, Y', strtotime($row->created_at)) }}
</td>

<td class="center">

<select class="status-select"
data-id="{{ $row->id }}"
onchange="updateStatus(this)">

<option value="Pending" {{ $row->status=='Pending'?'selected':'' }}>Pending</option>
<option value="Assigned" {{ $row->status=='Assigned'?'selected':'' }}>Assigned</option>
<option value="In Progress" {{ $row->status=='In Progress'?'selected':'' }}>In Progress</option>
<option value="Resolved" {{ $row->status=='Resolved'?'selected':'' }}>Resolved</option>
<option value="Closed" {{ $row->status=='Closed'?'selected':'' }}>Closed</option>

</select>

</td>

</tr>

@endforeach

</tbody>

</table>

</form>

</div>


<script>

/* SELECT ALL */

document.getElementById('selectAll').onclick = function() {

let checkboxes = document.querySelectorAll('.customer-checkbox');

checkboxes.forEach(cb => cb.checked = this.checked);

}


/* REMOVE AREA IF CUSTOMER SELECTED */

document.querySelectorAll('.customer-checkbox').forEach(cb => {

cb.addEventListener('change', function(){

if(this.checked){
document.getElementById('areaSelect').value="";
}

});

});


/* STATUS UPDATE AJAX */

function updateStatus(select){

let id = select.dataset.id;
let status = select.value;

fetch("/admin/complaint/status/"+id,{
method:"POST",
headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':'{{ csrf_token() }}'
},
body:JSON.stringify({status:status})
});

}

</script>

@endsection