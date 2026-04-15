@extends('admin.layout')

@section('content')

<h2>FilBiz Applications</h2>

<div class="card">

<div style="overflow-x:auto;">

<table style="width:100%; min-width:1000px;">

<tr>
<th>ID</th>
<th>Company</th>
<th>Mobile</th>
<th>Email</th>
<th>Office Address</th>
<th>Plan</th>
<th>PDF</th>
</tr>

@foreach($applications as $row)

<tr>

<td>{{ $row->id }}</td>
<td>{{ $row->company_name }}</td>
<td>{{ $row->mobile_number }}</td>
<td>{{ $row->email }}</td>
<td>{{ $row->office_address }}</td>
<td>{{ $row->monthly_subscription }}</td>

<td>

@if($row->file)

<a href="{{ asset('storage/'.$row->file) }}" target="_blank" class="btn btn-success" style = "margin-right: 10px;">
View
</a>

<a href="{{ route('download.filbiz',$row->id) }}" class="btn btn-success">
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