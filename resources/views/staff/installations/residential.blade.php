@extends('staff.layout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/staffinstallation.css') }}">

<div class="page-title">🏠 Residential Installations</div>

<div class="table-card">

<!-- 🔍 FILTER FORM -->
<form method="GET" action="{{ route('staff.installations.residential') }}">

<div class="top-actions">

    <!-- SEARCH -->
    <input type="text" name="search" placeholder="🔍 Search name, address..." 
           value="{{ request('search') }}">

    <!-- SORT -->
    <select name="sort">
    <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>
        📅 Newest
    </option>
    <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>
        📅 Oldest
    </option>
    <option value="status" {{ request('sort')=='status'?'selected':'' }}>
        📊 Status
    </option>
</select>

    <!-- APPLY -->
    <button type="submit" class="btn btn-primary">
        Apply
    </button>

</div>

</form>

<div class="table-wrapper">

<table>
<tr>
    <th>Name</th>
    <th>Address</th>
    <th>Mobile</th>
    <th>Status</th>
    <th>PDF</th>
    <th>Action</th>
</tr>

@foreach($residential as $r)
<tr>

    <td>
        <strong>{{ $r->firstname }} {{ $r->lastname }}</strong>
    </td>

    <td style="font-size:12px;color:#555;">
        {{ $r->street }} {{ $r->brgy }}, {{ $r->city }}
    </td>

    <td>{{ $r->mobile_number }}</td>

    <td>
        @php 
        $statusClass = match($r->status) {
            'Pending' => 'pending',
            'Processing' => 'processing',
            'Assigned to Technician' => 'assigned',
            'Full NAP' => 'fullnap',
            'Installed' => 'installed',
            'Cancelled' => 'cancelled',
            default => 'pending'
        };
        @endphp

        <span class="badge {{ $statusClass }}">
            {{ $r->status ?? 'Pending' }}
        </span>
    </td>

    <!-- PDF -->
    <td>
        @if($r->file)
            <a href="{{ asset('storage/'.$r->file) }}" 
               class="btn-download" target="_blank" download>
                ⬇ Download
            </a>
        @else
            <span style="color:#999;">No File</span>
        @endif
    </td>

    <!-- ACTION -->
    <td>
        <form class="status-form" method="POST" 
            action="{{ route('staff.installation.status', ['type'=>'residential','id'=>$r->id]) }}">
            @csrf

            <select name="status">
                <option {{ $r->status=='Pending'?'selected':'' }}>Pending</option>
                <option {{ $r->status=='Processing'?'selected':'' }}>Processing</option>
                <option {{ $r->status=='Assigned to Technician'?'selected':'' }}>Assigned to Technician</option>
                <option {{ $r->status=='Full NAP'?'selected':'' }}>Full NAP</option>
                <option {{ $r->status=='Installed'?'selected':'' }}>Installed</option>
                <option {{ $r->status=='Cancelled'?'selected':'' }}>Cancelled</option>
            </select>

            <button class="btn btn-primary">✔</button>
        </form>
    </td>

</tr>
@endforeach

@if($residential->isEmpty())
<tr>
    <td colspan="6" style="text-align:center;">No residential installations</td>
</tr>
@endif

</table>

</div>

</div>

@endsection