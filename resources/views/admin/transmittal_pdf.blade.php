<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
font-family: Arial, sans-serif;
margin:0;
padding:0;
}

.page{
width:100%;
padding:30px;
box-sizing:border-box;
}

/* HEADER LAYOUT */
.header-table{
width:100%;
border:none;
margin-bottom:10px;
}

.header-table td{
border:none;
vertical-align:middle;
}

.logo{
width:80px;
}

.header-text{
text-align:center;
font-size:13px;
font-weight:bold;
line-height:1.3;
}

.date{
margin-top:10px;
font-size:12px;
}

.title{
text-align:center;
font-weight:bold;
margin:8px 0 15px 0;
}

table{
width:100%;
border-collapse:collapse;
font-size:11px;
}

th,td{
border:1px solid #000;
padding:5px;
}

th{
text-align:center;
}

.center{
text-align:center;
}

.signature{
margin-top:25px;
width:100%;
}

.signature td{
border:none;
font-size:12px;
}

</style>

</head>

<body>

<div class="page">

<!-- HEADER WITH LOGO -->
<table class="header-table">

<tr>

<td width="15%">
<img src="{{ public_path('images/fil-products-logo.png') }}" class="logo">
</td>

<td class="header-text">
FIL PRODUCTS SERVICE TV OF CALBAYOG, INC<br>
Bernates Compound, Capoocan<br>
Calbayog City
</td>

<td width="15%"></td>

</tr>

</table>


<div class="date">
DATE: {{ $date }}
</div>

<div class="title">
JOB ORDER TRANSMITTAL
</div>


<table>

<thead>
<tr>
<th width="5%">NO</th>
<th width="25%">ADDRESS</th>
<th width="35%">NAME OF SUBSCRIBER</th>
<th width="15%">NATURE</th>
<th width="20%">REMARKS</th>
</tr>
</thead>

<tbody>

@php $i = 1; @endphp

@foreach($complaints as $row)

<tr>

<td class="center">{{ $i++ }}</td>

<td>{{ $row->address }}</td>

<td>{{ $row->account_name }}</td>

<td class="center">{{ $row->category }}</td>

<td>{{ $row->remarks }}</td>

</tr>

@endforeach

</tbody>

</table>


<table class="signature">

<tr>

<td width="50%" style="text-align:center;">
Prepared by:<br><br>

<span style="border-top:1px solid #000; display:inline-block; width:150px;"></span><br>
CSR
</td>

<td width="50%" style="text-align:center;">
Received by:<br><br>

{{ $teamLeader->team_leader ?? '' }}<br>
<span style="border-top:1px solid #000; display:inline-block; width:150px;"></span><br>
TEAM
</td>

</tr>

</table>

</div>

</body>
</html>