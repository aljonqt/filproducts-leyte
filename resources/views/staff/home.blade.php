@extends('staff.layout')

@section('content')

<style>

/* ===== CARD ===== */
.card {
    background: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
}

/* ===== TITLE ===== */
.title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 15px;
}

/* ===== SEARCH FORM ===== */
.search-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* SEARCH BOX */
.search-box {
    flex: 1;
    display: flex;
    align-items: center;
    background: #f8fafc;
    border-radius: 14px;
    padding: 12px 15px;
    border: 1px solid #e5e7eb;
    transition: 0.3s;
}

.search-box:focus-within {
    border-color: #0072ff;
    box-shadow: 0 0 0 3px rgba(0,114,255,0.1);
}

.search-box i {
    color: #0072ff;
    margin-right: 10px;
}

.search-box input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
}

/* BUTTON */
.search-btn {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    border: none;
    color: white;
    padding: 12px 22px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,114,255,0.3);
}

/* ===== TABLE ===== */
.table-container {
    overflow-x: auto;
    margin-top: 10px;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

/* HEADER */
.table th {
    text-align: left;
    padding: 12px;
    font-size: 13px;
    color: #6b7280;
    border-bottom: 1px solid #eee;
}

/* ROW */
.table td {
    padding: 14px 12px;
    border-bottom: 1px solid #f1f1f1;
    font-size: 14px;
}

/* HOVER */
.table tbody tr:hover {
    background: #f9fbff;
}

/* ===== NAME STYLE ===== */
.name {
    font-weight: 600;
    color: #111827;
}

/* ===== BADGE ===== */
.badge {
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
}

.badge.active { background: #dcfce7; color: #166534; }
.badge.cutoff { background: #fee2e2; color: #991b1b; }
.badge.pending { background: #fef3c7; color: #92400e; }

/* ===== BUTTON ===== */
.view-btn {
    background: #0072ff;
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    transition: 0.3s;
}

.view-btn:hover {
    background: #0056cc;
}

/* EMPTY STATE */
.empty {
    text-align: center;
    padding: 30px;
    color: #6b7280;
}

</style>

<!-- SEARCH -->
<div class="card">

    <div class="title">🔎 Search Subscriber</div>

    <form method="GET" action="{{ route('staff.search') }}" class="search-form">

        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" 
                   name="query" 
                   value="{{ request('query') }}"
                   placeholder="Search account number, name, or company..." 
                   required>
        </div>

        <button type="submit" class="search-btn">
            Search
        </button>

    </form>

</div>

{{-- RESULTS --}}
@if(isset($results))

<div class="card" style="margin-top: 20px;">

    <div class="title">
        Results ({{ count($results) }})
    </div>

    @if(count($results) > 0)

    <div class="table-container">

    <table class="table">

        <thead>
            <tr>
                <th>Account #</th>
                <th>Name</th>
                <th>Company</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        @foreach($results as $row)

        <tr>

            <td>{{ $row->account_number }}</td>

            <td class="name">
                {{ $row->customer_type == 'filbiz'
                    ? $row->company_name
                    : trim($row->firstname . ' ' . $row->middlename . ' ' . $row->lastname) }}
            </td>

            <td>{{ $row->company_name ?? '-' }}</td>

            <td>
                @if($row->status == 'active')
                    <span class="badge active">ACTIVE</span>
                @elseif($row->status == 'cutoff')
                    <span class="badge cutoff">CUT-OFF</span>
                @else
                    <span class="badge pending">PENDING</span>
                @endif
            </td>

            <td>
                <a href="{{ route('staff.subscriber.view', $row->id) }}" class="view-btn">
                    View
                </a>
            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

    </div>

    @else
        <div class="empty">
            No subscribers found
        </div>
    @endif

</div>

@endif

@endsection