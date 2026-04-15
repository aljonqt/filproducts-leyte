@extends('staff.layout')

@section('content')

<style>
.page-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #003366;
}

/* CARD */
.table-card {
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}

/* FILTER BAR */
.top-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
    align-items: center;
}

/* INPUTS */
input[type="text"], select {
    padding: 8px 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 13px;
}

/* BUTTONS */
.btn {
    padding: 8px 14px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
}

.btn-primary {
    background: #003366;
    color: white;
}

.btn-primary:hover {
    background: #002244;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

/* TABLE */
.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

th {
    background: #003366;
    color: white;
    text-align: left;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #f9fbfd;
}

/* BADGES */
.badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    color: white;
    display: inline-block;
}

.pending { background: orange; }
.assigned { background: purple; }
.ongoing { background: #17a2b8; }
.resolved { background: green; }
.cancelled { background: red; }

/* STATUS FORM */
.status-form {
    display: flex;
    gap: 5px;
}

.status-form select {
    padding: 5px;
}

/* CHECKBOX */
input[type="checkbox"] {
    transform: scale(1.2);
}

/* EMPTY */
.empty {
    text-align: center;
    padding: 20px;
    color: #999;
}
</style>

<div class="page-title">📞 Complaints Management</div>

<div class="table-card">

<form method="GET" action="{{ route('staff.complaints') }}">
    
    <div class="top-actions">

        <!-- SEARCH -->
        <input type="text" name="search" placeholder="🔍 Search complaints..." 
               value="{{ request('search') }}">

        <!-- AREA -->
        <select name="filter_area">
            <option value="">📍 All Areas</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}" 
                    {{ request('filter_area') == $area->id ? 'selected' : '' }}>
                    {{ $area->area_name }}
                </option>
            @endforeach
        </select>

        <!-- SORT -->
        <select name="sort">
            <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>
                📅 Newest
            </option>
            <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>
                📅 Oldest
            </option>
        </select>

        <!-- APPLY -->
        <button type="submit" class="btn btn-primary">
            Apply
        </button>

    </div>

</form>

<!-- TRANSMITTAL FORM SEPARATED (IMPORTANT UX FIX) -->
<form method="POST" action="{{ route('staff.transmittal.generate') }}">
@csrf

<div style="margin-bottom:10px;">
    <button type="submit" class="btn btn-success">
        📄 Generate Transmittal (Selected)
    </button>
</div>

<div class="table-wrapper">

<table>
<tr>
    <th><input type="checkbox" onclick="toggleAll(this)"></th>
    <th>Ticket</th>
    <th>Name</th>
    <th>Address</th>
    <th>Mobile</th>
    <th>Concern</th>
    <th>Status</th>
    <th>Action</th>
</tr>

@foreach($complaints as $c)
<tr>
    <td>
        <input type="checkbox" name="selected[]" value="{{ $c->id }}">
    </td>

    <td><strong>#{{ $c->ticket_number }}</strong></td>

    <td>{{ $c->account_name }}</td>

    <td style="font-size:12px;color:#555;">
        {{ $c->address }}
    </td>

    <td>{{ $c->mobile_number }}</td>

    <td>
        <span style="font-size:12px;">
            {{ $c->category }}
        </span>
    </td>

    <td>
        @php 
        $statusClass = match($c->status) {
            'Pending' => 'pending',
            'Assigned' => 'assigned',
            'On-going' => 'ongoing',
            'Resolved' => 'resolved',
            'Cancelled' => 'cancelled',
            default => 'pending'
        };
        @endphp

        <span class="badge {{ $statusClass }}">
            {{ $c->status }}
        </span>
    </td>

    <td>
        <form method="POST" action="{{ route('staff.complaint.status', $c->id) }}" class="status-form">
            @csrf

            <select name="status">
                <option {{ $c->status=='Pending'?'selected':'' }}>Pending</option>
                <option {{ $c->status=='Assigned'?'selected':'' }}>Assigned</option>
                <option {{ $c->status=='On-going'?'selected':'' }}>On-going</option>
                <option {{ $c->status=='Resolved'?'selected':'' }}>Resolved</option>
                <option {{ $c->status=='Cancelled'?'selected':'' }}>Cancelled</option>
            </select>

            <button class="btn btn-primary">✔</button>
        </form>
    </td>
</tr>
@endforeach

@if($complaints->isEmpty())
<tr>
    <td colspan="8" class="empty">No complaints found</td>
</tr>
@endif

</table>

</div>

</form>

</div>

<script>
function toggleAll(source) {
    document.querySelectorAll('input[name="selected[]"]').forEach(cb => {
        cb.checked = source.checked;
    });
}
</script>

@endsection