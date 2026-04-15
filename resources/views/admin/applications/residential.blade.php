@extends('admin.layout')

@section('content')

<h2>Residential Applications</h2>

<div class="card">

<div style="overflow-x:auto;">

<table style="width:100%; min-width:900px;">

<tr>
<th>ID</th>
<th>Name</th>
<th>Plan</th>
<th>Barangay</th>
<th>City</th>
<th>Date</th>
<th>PDF</th>
</tr>

@foreach($applications as $row)

<tr>

<td>{{ $row->id }}</td>
<td>{{ $row->firstname }} {{ $row->lastname }}</td>
<td>{{ $row->monthly_subscription }}</td>
<td>{{ $row->brgy }}</td>
<td>{{ $row->city }}</td>
<td>{{ $row->created_at }}</td>

<td>

@if($row->file)

<a href="{{ asset('storage/'.$row->file) }}"
target="_blank"
class="btn btn-primary" style="margin-right: 10px;">
View
</a>

<a href="{{ route('download.residential',$row->id) }}"
class="btn btn-success">
Download
</a>

@else

No File

@endif

</td>

</tr>

@endforeach

</table>

</div>

</div>

@endsection