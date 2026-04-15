@extends('admin.layout')

@section('content')

<h2>Area Management</h2>

<div class="card">

<form method="POST" action="{{ route('admin.area.save') }}">
@csrf

<input type="text" name="area_name" placeholder="Area Name" required>
<input type="text" name="team_leader" placeholder="Team Leader" required>

<button class="btn">Add Area</button>

</form>

<br>

<table class="complaint-table">

<tr>
<th>Area</th>
<th>Team Leader</th>
<th>Action</th>
</tr>

@foreach($areas as $area)

<tr>

<td>{{ $area->area_name }}</td>

<td>

<form method="POST" action="{{ route('admin.area.update',$area->id) }}">
@csrf

<input type="text" name="team_leader" value="{{ $area->team_leader }}">

<button class="btn">Update</button>

</form>

</td>

<td>

<a href="{{ route('admin.area.delete',$area->id) }}" class="btn" style="background:red;color:white;">
Delete
</a>

</td>

</tr>

@endforeach

</table>

</div>

@endsection