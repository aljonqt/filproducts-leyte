@extends('staff.layout')

@section('content')

<style>

/* ===== CARD ===== */
.card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

/* HEADER */
.profile-header {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* AVATAR */
.avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    font-weight: bold;
}

/* NAME */
.name {
    font-size: 18px;
    font-weight: 600;
}

/* SUBTEXT */
.subtext {
    font-size: 13px;
    color: #6b7280;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

/* FIELD */
.field {
    background: #f9fafb;
    padding: 12px;
    border-radius: 12px;
}

.label {
    font-size: 12px;
    color: #6b7280;
}

.value {
    font-size: 14px;
    font-weight: 600;
    margin-top: 3px;
}

/* BADGE */
.badge {
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge.active { background: #dcfce7; color: #166534; }
.badge.cutoff { background: #fee2e2; color: #991b1b; }
.badge.pending { background: #fef3c7; color: #92400e; }

/* BACK BUTTON */
.back-btn {
    display: inline-block;
    margin-bottom: 15px;
    background: #e5e7eb;
    padding: 8px 14px;
    border-radius: 10px;
    text-decoration: none;
    color: #111;
    font-size: 13px;
}

.back-btn:hover {
    background: #d1d5db;
}

.complaint-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #ff4d4d, #cc0000);
    color: white;
    padding: 8px 14px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px;
    margin-top: 8px;
    transition: 0.3s;
}

.complaint-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(255,0,0,0.3);
}
</style>

<a href="{{ route('staff.home') }}" class="back-btn">
    ← Back to Search
</a>

<div class="card">

    <!-- HEADER -->
    <div class="profile-header">

        <div class="avatar">
            {{ strtoupper(substr($subscriber->customer_type == 'filbiz'
                ? $subscriber->company_name
                : $subscriber->firstname, 0, 1)) }}
        </div>

        <div>
            <div class="name">
                {{ $subscriber->customer_type == 'filbiz'
                    ? $subscriber->company_name
                    : trim($subscriber->firstname . ' ' . $subscriber->middlename . ' ' . $subscriber->lastname) }}
            </div>

            <div class="subtext">
                Account #: {{ $subscriber->account_number }}
            </div>
        </div>

        <div style="margin-left:auto;">
            @if($subscriber->status == 'active')
                <span class="badge active">ACTIVE</span>
            @elseif($subscriber->status == 'cutoff')
                <span class="badge cutoff">CUT-OFF</span>
            @else
                <span class="badge pending">PENDING</span>
            @endif
        </div>
        <a href="{{ route('staff.complaint.create', $subscriber->id) }}" class="complaint-btn">
    <i class="fas fa-exclamation-circle"></i> Create Complaint
</a>

    </div>

    <!-- DETAILS -->
    <div class="grid">

        <div class="field">
            <div class="label">Customer Type</div>
            <div class="value">{{ ucfirst($subscriber->customer_type) }}</div>
        </div>

        <div class="field">
            <div class="label">Phone</div>
            <div class="value">{{ $subscriber->phone ?? '-' }}</div>
        </div>

        <div class="field">
            <div class="label">Email</div>
            <div class="value">{{ $subscriber->email ?? '-' }}</div>
        </div>

        <div class="field">
            <div class="label">Address</div>
            <div class="value">{{ $subscriber->address ?? '-' }}</div>
        </div>

        <div class="field">
            <div class="label">Created At</div>
            <div class="value">{{ $subscriber->created_at }}</div>
        </div>

    </div>

</div>

@endsection