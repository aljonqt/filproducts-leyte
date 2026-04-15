@extends('staff.layout')

@section('content')


<link rel="stylesheet" href="{{ asset('css/staffinstallation.css') }}">

<div class="page-title">🏢 Business (Filbiz) Installations</div>

<div class="table-card">

<!-- 🔍 FILTER FORM -->
<form method="GET" action="{{ route('staff.installations.filbiz') }}">

<div class="top-actions">

    <!-- SEARCH -->
    <input type="text" name="search" placeholder="🔍 Search company, address..." 
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
            📊 Status Priority
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
    <th>Company</th>
    <th>Address</th>
    <th>Mobile</th>
    <th>Status</th>
    <th>PDF</th>
    <th>Action</th>
</tr>

@foreach($filbiz as $f)
<tr>

    <td>
        <strong>{{ $f->company_name }}</strong>
    </td>

    <td style="font-size:12px;color:#555;">
        {{ $f->office_address }}
    </td>

    <td>{{ $f->mobile_number }}</td>

    <td>
        @php 
        $statusClass = match($f->status) {
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
            {{ $f->status ?? 'Pending' }}
        </span>
    </td>

    <!-- PDF -->
    <td>
        @if($f->file)
            <a href="{{ asset('storage/'.$f->file) }}" 
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
            action="{{ route('staff.installation.status', ['type'=>'filbiz','id'=>$f->id]) }}">
            @csrf

            <select name="status">
                <option {{ $f->status=='Pending'?'selected':'' }}>Pending</option>
                <option {{ $f->status=='Processing'?'selected':'' }}>Processing</option>
                <option {{ $f->status=='Assigned to Technician'?'selected':'' }}>Assigned to Technician</option>
                <option {{ $f->status=='Full NAP'?'selected':'' }}>Full NAP</option>
                <option {{ $f->status=='Installed'?'selected':'' }}>Installed</option>
                <option {{ $f->status=='Cancelled'?'selected':'' }}>Cancelled</option>
            </select>

            <button class="btn btn-primary">✔</button>
        </form>
    </td>

</tr>
@endforeach

@if($filbiz->isEmpty())
<tr>
    <td colspan="6" style="text-align:center;">No business installations</td>
</tr>
@endif

</table>

</div>

</div>

@endsection