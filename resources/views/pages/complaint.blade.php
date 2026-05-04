@extends('layouts.navbar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/complaint.css') }}">

<div class="page-wrapper">
    <div class="form-container">
        <div class="form-header">
            <span class="news-badge">Support Center</span> <h2><i class="fas fa-headset"></i> Customer Complaint</h2>
            <p>Report service issues or concerns. Our local support team is here to help.</p>
        </div>

        @if(session('success'))
            <div class="success-box">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-box">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('complaint.submit') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Account Name</label>
                    <input type="text" name="account_name" placeholder="Name on the contract" required>
                </div>

                <div class="form-group">
                    <label>Account Number <small>(Optional)</small></label>
                    <input type="text" name="account_no" placeholder="Found on your bill">
                </div>
            </div>

            <div class="form-group">
                <label>Service Address</label>
                <input type="text" name="address" placeholder="House no., St., Brgy, City" required>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="tel" name="mobile_number" placeholder="09XX XXX XXXX">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="example@email.com" required>
                </div>
            </div>

            <div class="form-grid">
                <input type="hidden" name="branch" value="leyte">
                <div class="form-group">
                    <label>Complaint Category</label>
                    <select name="category" required>
                        <option value="" disabled selected>What are you experiencing?</option>
                        <option>Internet Connection Problem</option>
                        <option>Slow Internet Speed</option>
                        <option>No Internet Connection</option>
                        <option>Cable TV Issue</option>
                        <option>Billing Concern</option>
                        <option>Other Concern</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detailed Remarks</label>
                <textarea name="remarks" placeholder="Please describe the issue in detail so we can troubleshoot effectively..." required></textarea>
            </div>

            <button type="submit" class="submit-btn">
                Submit Report <i class="fas fa-paper-plane" style="margin-left: 10px;"></i>
            </button>
        </form>

        <div class="help-box">
            <strong><i class="fas fa-info-circle"></i> Prefer to talk to us?</strong><br><br>
            Visit a <strong>Fil Products Office</strong> or call our hotlines for immediate troubleshooting. We are committed to getting you back online as fast as possible.
        </div>
    </div>
</div>

@include('layouts.footer') 

@endsection