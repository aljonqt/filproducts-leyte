@extends('layouts.navbar')

@section('content')

<style>

.track-wrapper{
max-width:750px;
margin:60px auto;
padding:40px;
background:white;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.track-title{
font-size:28px;
font-weight:bold;
text-align:center;
color:#003366;
margin-bottom:8px;
}

.track-desc{
text-align:center;
color:#666;
margin-bottom:30px;
}

.track-form{
display:flex;
gap:10px;
}

.track-input{
flex:1;
padding:12px;
font-size:16px;
border:1px solid #ccc;
border-radius:6px;
}

.track-btn{
padding:12px 22px;
background:#003366;
color:white;
border:none;
border-radius:6px;
font-weight:bold;
cursor:pointer;
}

.track-btn:hover{
background:#001f4d;
}

/* RESULT */

.result-box{
margin-top:30px;
padding:25px;
border-radius:10px;
background:#f9fbff;
border:1px solid #e3e8f0;
}

.result-row{
margin-bottom:8px;
font-size:15px;
}

/* STATUS BADGE */

.status{
font-weight:bold;
padding:5px 12px;
border-radius:20px;
font-size:13px;
}

.status.pending{background:#ffeaea;color:#c62828;}
.status.assigned{background:#fff4e5;color:#ef6c00;}
.status.inprogress{background:#e3f2fd;color:#1565c0;}
.status.resolved{background:#e8f5e9;color:#2e7d32;}
.status.closed{background:#eeeeee;color:#424242;}

/* PROGRESS TRACKER */

.progress-container{
margin-top:30px;
display:flex;
justify-content:space-between;
position:relative;
}

.progress-container::before{
content:"";
position:absolute;
top:15px;
left:0;
width:100%;
height:4px;
background:#ddd;
z-index:0;
}

.progress-step{
position:relative;
z-index:1;
text-align:center;
flex:1;
}

.progress-circle{
width:30px;
height:30px;
border-radius:50%;
background:#ddd;
margin:auto;
line-height:30px;
font-size:14px;
color:white;
}

.progress-step.active .progress-circle{
background:#003366;
}

.progress-label{
margin-top:6px;
font-size:12px;
color:#666;
}

.next-step{
margin-top:20px;
padding:12px;
background:#eef5ff;
border-radius:8px;
font-size:14px;
color:#003366;
}

</style>


<div class="track-wrapper">

<div class="track-title">
Track Your Request
</div>

<div class="track-desc">
Enter your ticket number to check the status of your inquiry or complaint.
</div>

<form method="GET" action="{{ route('track') }}" class="track-form">

<input
type="text"
name="ticket"
class="track-input"
placeholder="Enter Ticket Number"
value="{{ request('ticket') }}"
required>

<button type="submit" class="track-btn">
Track
</button>

</form>


@if(isset($result) && $result)

@php

$steps = ['Pending','Assigned','In Progress','Resolved'];

$currentIndex = array_search($result->status,$steps);

@endphp

<div class="result-box">

<div class="result-row">
<strong>Ticket Number:</strong> {{ $result->ticket_number }}
</div>

<div class="result-row">
<strong>Account Name:</strong> {{ $result->account_name }}
</div>

<div class="result-row">
<strong>Address:</strong> {{ $result->address }}
</div>

<div class="result-row">
<strong>Category:</strong> {{ $result->category }}
</div>

<div class="result-row">
<strong>Status:</strong>

<span class="status {{ strtolower(str_replace(' ','',$result->status)) }}">
{{ $result->status }}
</span>

</div>

<div class="result-row">
<strong>Date Submitted:</strong>
{{ date('M d, Y', strtotime($result->created_at)) }}
</div>


<!-- Progress Tracker -->

<div class="progress-container">

@foreach($steps as $index => $step)

<div class="progress-step {{ $index <= $currentIndex ? 'active' : '' }}">

<div class="progress-circle">
@if($index <= $currentIndex)
✔
@endif
</div>

<div class="progress-label">
{{ $step }}
</div>

</div>

@endforeach

</div>


<!-- Next Step -->

@if($currentIndex !== false && isset($steps[$currentIndex+1]))

<div class="next-step">

<strong>Next Step:</strong>
Your request will move to <b>{{ $steps[$currentIndex+1] }}</b> once processing is completed.

</div>

@endif

</div>

@endif

</div>

@endsection