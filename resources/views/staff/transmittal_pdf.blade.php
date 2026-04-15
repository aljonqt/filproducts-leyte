<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #003366;
            padding-bottom: 10px;
            margin-bottom: 15px;
            position: relative;
        }

/* LOGO LEFT */
        .logo-box {
            position: absolute;
            left: 0;
            top: 0;
        }

.logo-box img {
    width: 70px;
}

/* CENTER COMPANY INFO */
.company-info {
    text-align: center;
}

.company-info h2 {
    margin: 0;
    font-size: 16px;
    color: #003366;
}

.company-info p {
    margin: 2px 0;
    font-size: 11px;
}

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            color: #003366;
        }

        .meta {
            margin-bottom: 10px;
        }

        .meta p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #003366;
            color: white;
            padding: 8px;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border: 1px solid #ccc;
            font-size: 11px;
        }

        .footer {
            margin-top: 40px;
        }

        .signature {
            width: 45%;
            display: inline-block;
        }

        .line {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 80%;
        }

        .label {
            margin-top: 5px;
            font-size: 11px;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    
    <!-- LOGO LEFT -->
    <div class="logo-box">
        <img src="{{ public_path('images/fil-products-logo.png') }}">
    </div>

    <!-- CENTER TEXT -->
    <div class="company-info">
        <h2>FIL PRODUCTS SERVICE TV OF CALBAYOG, INC</h2>
        <p>Bernates Compound, Capoocan</p>
        <p>Calbayog City</p>
    </div>

</div>

<!-- TITLE -->
<div class="title">
    JOB ORDER TRANSMITTAL
</div>

<!-- META -->
<div class="meta">
    <p><strong>Date:</strong> {{ $date }}</p>

    @if(isset($area))
        <p><strong>Area:</strong> {{ $area->area_name }}</p>
        <p><strong>Team Leader:</strong> {{ $area->team_leader }}</p>
    @endif
</div>

<!-- TABLE -->
<table>
<tr>
    <th width="5%">#</th>
    <th width="25%">Address</th>
    <th width="25%">Subscriber Name</th>
    <th width="20%">Nature</th>
    <th width="25%">Remarks</th>
</tr>

@foreach($complaints as $i => $c)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $c->address }}</td>
    <td>{{ $c->account_name }}</td>
    <td>{{ $c->category }}</td>
    <td>{{ $c->remarks ?? '-' }}</td>
</tr>
@endforeach
</table>

<!-- FOOTER SIGNATURE -->
<div class="footer">

    <div class="signature">
        <div class="line"></div>
        <div class="label">Prepared by (CSR)</div>
    </div>

    <div class="signature" style="float:right;">
        <div class="line"></div>
        <div class="label">
            Received by ({{ $area->team_leader ?? 'Technical Team' }})
        </div>
    </div>

</div>

</body>
</html>