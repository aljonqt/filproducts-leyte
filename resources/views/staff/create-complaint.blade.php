@extends('staff.layout')

@section('content')

<style>
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.input-group {
    display: flex;
    flex-direction: column;
}

.input-group label {
    font-size: 13px;
    margin-bottom: 5px;
    font-weight: 600;
}

.input-group input,
.input-group select,
textarea {
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
}

textarea {
    width: 100%;
    margin-top: 10px;
}

.submit-btn {
    margin-top: 15px;
    background: linear-gradient(135deg, #0072ff, #00c6ff);
    color: white;
    padding: 12px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 15px;
}
</style>

<div class="card">

    <h2>📞 Create Complaint</h2>

    <form method="POST" action="{{ route('staff.complaint.store') }}">
        @csrf

        <div class="form-grid">

            <!-- ACCOUNT NAME -->
            <div class="input-group">
                <label>Account Name</label>
                <input type="text" name="account_name"
                    value="{{ $subscriber->customer_type == 'filbiz'
                        ? $subscriber->company_name
                        : $subscriber->firstname . ' ' . $subscriber->lastname }}"
                    readonly>
            </div>

            <!-- ACCOUNT NUMBER -->
            <div class="input-group">
                <label>Account Number</label>
                <input type="text" name="account_number"
                    value="{{ $subscriber->account_number }}" readonly>
            </div>

            <!-- EMAIL -->
            <div class="input-group">
                <label>Email</label>
                <input type="text" name="email"
                    value="{{ $subscriber->email }}" readonly>
            </div>

            <!-- ADDRESS -->
            <div class="input-group">
                <label>Address</label>
                <input type="text" name="address"
                    value="{{ $subscriber->address }}" readonly>
            </div>

            <!-- MOBILE -->
            <div class="input-group">
                <label>Mobile Number</label>
                <input type="text" name="mobile_number"
                    value="{{ $subscriber->phone }}" readonly>
            </div>

            <!-- AREA -->
            <div class="input-group">
            <label>Area Category</label>
                <select name="area_category" required>

            <option value="">Select Area</option>

            @foreach($areas as $area)
        <option value="{{ $area->area_name }}">
    {{ $area->area_name }}
</option>
@endforeach

</select>
            </div>

            <!-- CATEGORY -->
            <div class="input-group">
                <label>Complaint Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="No Internet">No Internet</option>
                    <option value="Slow Connection">Slow Connection</option>
                    <option value="LOS">LOS</option>
                    <option value="Intermittent">Intermittent</option>
                </select>
            </div>

        </div>

        <!-- DETAILS -->
        <div class="input-group">
            <label>Complaint Details</label>
            <textarea name="remarks" rows="4" required></textarea>
        </div>

        <button type="submit" class="submit-btn">
            Submit Complaint
        </button>

    </form>

</div>

@endsection