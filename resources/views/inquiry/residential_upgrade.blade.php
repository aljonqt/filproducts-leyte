@extends('layouts.navbar')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="{{ asset('css/residential.css') }}">
<div class="page-wrapper">
<div class="form-container">

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:15px;margin-bottom:20px;border-radius:6px;">
{!! session('success') !!}
</div>
@endif
@if(session('error'))
<div style="background:#f8d7da;color:#721c24;padding:15px;margin-bottom:20px;border-radius:6px;">
{{ session('error') }}
</div>
@endif
<form method="POST" enctype="multipart/form-data" action="{{ route('residential.upgrade.submit') }}">
@csrf
<div class="section-header">Residential Upgrade Form</div>
<div class="section-title">PERSONAL INFORMATION</div>
<input type="hidden" name="branch" value="leyte">

<!-- ROW 1 -->
<div class="row row-3">
    <div class="form-group" >
        <label>Salutation</label>
        <select name="salutation" required>
            <option>Mr.</option>
            <option>Ms.</option>
            <option>Mrs.</option>
            <option>Others</option>
        </select>
    </div>

    <div class="form-group">
        <label>Gender</label>
        <select name="gender" required>
            <option>Male</option>
            <option>Female</option>
        </select>
    </div>

    <div class="form-group">
        <label>Birthday</label>
        <input type="date" name="birthday" required>
    </div>
</div>

<!-- ROW 2 -->
<div class="row row-2">
    <div class="form-group">
        <label>Civil Status</label>
        <select name="civil_status" required>
            <option>Single</option>
            <option>Married</option>
            <option>Widow/Widower</option>
            <option>Legally Separated</option>
        </select>
    </div>

    <div class="form-group">
        <label>Citizenship</label>
        <input type="text" name="citizenship" required>
    </div>
</div>

<!-- ROW 3 -->
<div class="row row-4">
    <div class="form-group">
        <label>First Name</label>
        <input type="text" name="first_name" required>
    </div>

    <div class="form-group">
        <label>Middle Name</label>
        <input type="text" name="middle_name" required>
    </div>

    <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="last_name" required>
    </div>

    <div class="form-group">
        <label>Mobile No.</label>
        <input type="text" name="mobile_no" required>
    </div>
</div>

<div class="row row-4">
    <div class="form-group">
        <label>TIN No.</label>
        <input type="text" name="tin_no">
    </div>

    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Home Tel. No.</label>
        <input type="text" name="home_tel">
    </div>

    <div class="form-group">
        <label>GSIS / SSS No.</label>
        <input type="text" name="gsis_sss">
    </div>
</div>
<!-- COMPLETE ADDRESS -->
<div class="section-title">COMPLETE HOME ADDRESS</div>

<div class="row row-4">

    <div class="form-group">
        <label>Room / Floor / Bldg. / No. / Street</label>
        <input type="text" name="street" required>
    </div>

    <div class="form-group">
        <label>Barangay / Municipality / Town</label>
        <input type="text" name="barangay" required>
    </div>

    <div class="form-group">
        <label>City / Province</label>
        <input type="text" name="city" required>
    </div>

    <div class="form-group">
        <label>Postal Code / Zip Code</label>
        <input type="text" name="zip" required>
    </div>

</div>

<!-- ================= SUBSCRIPTION + DECLARATION + MAP ================= -->
<div style="margin-top:40px; display:grid; grid-template-columns: 1fr; gap:30px;" class="subscription-map-wrapper">

    <!-- LEFT SIDE -->
    <div>

        <!-- MONTHLY SUBSCRIPTION -->
        <div class="section-title">
            MONTHLY SUBSCRIPTION
        </div>

<div class="plan-grid">

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="50Mbps PHP 799" required>
<p class="speed">50 Mbps</p>
<span class="price">₱799</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="80Mbps PHP 899">
<p class="speed">80 Mbps</p>
<span class="price">₱899</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="100Mbps PHP 999">
<p class="speed">100 Mbps</p>
<span class="price">₱999</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="150Mbps PHP 1,199">
<p class="speed">150 Mbps</p>
<span class="price">₱1,199</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="250Mbps PHP 1,299">
<p class="speed">250 Mbps</p>
<span class="price">₱1,299</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="300Mbps PHP 1,499">
<p class="speed">300 Mbps</p>
<span class="price">₱1,499</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="200Mbps PHP 1,250">
<div class="speed">200 Mbps</div>
<div class="price">₱1,250</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="350Mbps PHP 1,690">
<div class="speed">350 Mbps</div>
<div class="price">₱1,690</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="350Mbps PHP 1,799">
<div class="speed">350 Mbps</div>
<div class="price">₱1,799</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="400Mbps PHP 1,999">
<div class="speed">400 Mbps</div>
<div class="price">₱1,999</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="500Mbps PHP 2,000">
<div class="speed">500 Mbps</div>
<div class="price">₱2,000</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="500Mbps PHP 2,400">
<div class="speed">500 Mbps</div>
<div class="price">₱2,400</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="500Mbps PHP 2,750">
<div class="speed">500 Mbps</div>
<div class="price">₱2,750</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="500Mbps PHP 3,000">
<div class="speed">500 Mbps</div>
<div class="price">₱3,000</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="500Mbps PHP 3,800">
<div class="speed">500 Mbps</div>
<div class="price">₱3,800</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="500Mbps PHP 6,000">
<div class="speed">500 Mbps</div>
<div class="price">₱6,000</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="500Mbps PHP 9,000">
<div class="speed">500 Mbps</div>
<div class="price">₱9,000</div>
<div class="plan-desc">Free Premium Cable TV</div>
</label>

</div>

    </div>

<!-- ================= SUBSCRIBER'S DECLARATION ================= -->
<div class="section-title" style="margin-top:40px;">
SUBSCRIBER'S DECLARATION
</div>

<div class="row" style="
display:grid;
grid-template-columns: 1fr 1fr 1.2fr;
gap:20px;
align-items:start;
">

<!-- COLUMN 1 : DECLARATION -->
<div>

<div style="display:flex; align-items:center; gap:10px; margin-bottom:15px;">

<input type="checkbox" id="declarationCheck" name="declaration_agree" value="1"
style="width:18px;height:18px;cursor:pointer;">

<span style="color:#003366;font-weight:600;cursor:pointer;text-decoration:underline;"
onclick="openDeclaration()">
I have read and agree to the Subscriber's Declaration
</span>

</div>

<button type="button"
onclick="openSignaturePad()"
style="
background:#003366;
color:white;
padding:10px 18px;
border:none;
border-radius:6px;
cursor:pointer;
font-size:14px;
">

Add Digital Signature

</button>
<br><br>
<button 
type="submit"
id="submitBtn"
disabled
style="
background:#999;
color:white;
padding:12px 30px;
border:none;
border-radius:6px;
font-weight:600;
cursor:not-allowed;
">
Submit Residential Inquiry
</button>

<input type="hidden" name="digital_signature" id="digitalSignatureInput">

</div>


<!-- COLUMN 2 : SIGNATURE PREVIEW -->
<div>

<label style="font-weight:600;color:#003366;">Signature Preview</label>

<div style="
border:2px dashed #003366;
border-radius:8px;
height:120px;
display:flex;
align-items:center;
justify-content:center;
background:#fff;
margin-top:8px;
">

<img id="signaturePreview"
style="max-height:100px; display:none;">

<span id="signaturePlaceholder" style="color:#888;">
No signature yet
</span>

</div>

</div>
</div>
</div>
    </div>

<!-- DECLARATION MODAL -->
<div class="modal-overlay" id="declarationModal">
    <div class="modal-content modal-declaration">

        <h3>SUBSCRIBER'S DECLARATION</h3>

        <div class="modal-body">
            <p>
                1. I hereby confirm that the foregoing information is true and correct, that supporting documents attached hereto are
genuine and authentic, and that I voluntarily submitted the said information and documents for the purpose of facilitating my
application to the Service.
            </p>

            <p>
                2. I hereby further confirm that I applied for and, once my application is approved, that I have voluntarily availed of the
plans, products and/or services chosen by me in this application form, as well as the inclusions and special features of such
plans, products and/or services and that any enrolment I have indicated herein have been knowingly made by me.

            </p>

            <p>
               3. I hereby authorize FIL PRODUCTS SERVICE TELEVISION OF CALBAYOG, INC. (hereinafter "you") or any person or
entity authorized by you, to verify any information about me and/or documents available from whatever source including but
not limited to (i) your subsidiaries, affiliates, and/or their service providers: or (ii) banks, credit card companies, and other
lending and/or financial institution, and I hereby authorize the holder, controller and processor of such information and/or
document, has the same is defined in Republic Act No. 10173 (otherwise known as "Data Privacy Act of 2012), or any
amendment or modification of the same, to conform, release and verify the existence, truthfulness, and/or accuracy of such
information and/or document.
            </p>
            <p>
                4. I give you permission to use, disclose and share with your business partners, subsidiaries and affiliates (and their
business partners) information contained in this application about me and my subscription, my network and connections, my
service usage and payment patterns, information about the device and equipment I use to access you service, websites
and app used in your services, information from your third party partners and advertisers, including any data or analytics
derived therefrom, in whatever form (hereinafter "Personal Information"), for the following purposes: processing any
application or request for availment of any product and/or service which they offer, improving your/ their products and
services, credit investigation and scoring, advertising and promoting new products and services, to the end of improving my
and/or the public's customer experience.
            </p>
            <p>
                5. I consent to your business partners', subsidiaries' and affiliates' (and their business partners') disclosure to you of any
Personal Information in their possession to achieve any of the purposes stated above.
            </p>
            <p>
            6. I hereby likewise authorize you, your business partners, subsidiaries and affiliates, to send me SMS alerts or any
communication, advertisement or promotional material pertaining to any new or current product and/or service offered by
you, your business partners, subsidiaries and affiliates
            </p>
            <p>
            7. I acknowledge and agree to the Holding Period for the relevant service availed of. If I choose to downgrade my plan,
transfer and rights or obligations of my subscription or pre-terminate or cancel my subscription within the Holding Period
then I agree to pay the relevant fees, charges and penalties imposed by you.
            </p>
            <p>
                8. I am aware of the fees, rates and charges relevant of the service availed of and I agree to pay the same within the due
dates. I understand that I will be subject to, and hereby agree and undertake, interest and penalties for late payment or non-payment stated in the terms and condition.
            </p>
            <p>
                9. I hereby confirm that I have read and understood the Terms and Conditions of our Subscription Agreement and that I
shall strictly comply and abide by these terms and conditions and any future amendments thereto.
            </p>
            <p>
                10. I agree that this Subscription Agreement shall govern our relationship for the service currently availed of and the service
I will avail of in the future.
            </p>
            <p>
                11. I agree to pay my application's cancellation fee equivalent to 20% of application charges (Deposit, Installation fee and
equipments).
            </p>
        </div>

        <button type="button" onclick="closeDeclaration()" class="modal-close-btn">
            Close
        </button>

    </div>
</div>

<!-- ================= SIGNATURE PAD MODAL ================= -->
<div class="modal-overlay" id="signatureModal">
    <div class="modal-content modal-signature">

        <h3>Digital Signature</h3>

        <div style="
            border:2px solid #003366;
            border-radius:8px;
            background:#fff;
        ">
            <canvas id="signatureCanvas" style="
                width:100%;
                height:250px;
                display:block;
            "></canvas>
        </div>

        <div style="margin-top:15px; display:flex; gap:10px;">
            <button type="button" onclick="clearSignature()" class="modal-close-btn">
                Clear
            </button>

            <button type="button" onclick="saveSignature()" class="modal-close-btn">
                Save Signature
            </button>
        </div>

    </div>
</div>

<script>
// ================= OTHER INDUSTRY =================
function toggleOtherIndustry(select) {
    const box = document.getElementById("otherIndustryBox");
    box.style.display = select.value === "Others" ? "flex" : "none";
}

// ================= DECLARATION MODAL =================
function openDeclaration() {
    document.getElementById("declarationModal").classList.add("active");
}
function closeDeclaration() {
    document.getElementById("declarationModal").classList.remove("active");
}

// ================= SIGNATURE PAD =================
let canvas;
let ctx;
let drawing = false;

function initSignaturePad() {
    canvas = document.getElementById("signatureCanvas");
    ctx = canvas.getContext("2d");

    setTimeout(()=>{
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.strokeStyle = "#000";
    },100);
}

function getPosition(e) {
    const rect = canvas.getBoundingClientRect();
    return {
        x:(e.clientX||e.touches[0].clientX)-rect.left,
        y:(e.clientY||e.touches[0].clientY)-rect.top
    };
}

function startDraw(e){
    e.preventDefault();
    drawing=true;
    const pos=getPosition(e);
    ctx.beginPath();
    ctx.moveTo(pos.x,pos.y);
}

function draw(e){
    if(!drawing) return;
    e.preventDefault();
    const pos=getPosition(e);
    ctx.lineTo(pos.x,pos.y);
    ctx.stroke();
}

function endDraw(){ drawing=false; }

function clearSignature(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
}

function saveSignature(){

    const dataURL = canvas.toDataURL("image/png");

    document.getElementById("digitalSignatureInput").value = dataURL;

    // SHOW PREVIEW
    const preview = document.getElementById("signaturePreview");
    const placeholder = document.getElementById("signaturePlaceholder");

    preview.src = dataURL;
    preview.style.display = "block";
    placeholder.style.display = "none";

    closeSignaturePad();
}

function openSignaturePad(){
    document.getElementById("signatureModal").classList.add("active");
    initSignaturePad();

    canvas.addEventListener("mousedown",startDraw);
    canvas.addEventListener("mousemove",draw);
    canvas.addEventListener("mouseup",endDraw);
    canvas.addEventListener("mouseleave",endDraw);

    canvas.addEventListener("touchstart",startDraw);
    canvas.addEventListener("touchmove",draw);
    canvas.addEventListener("touchend",endDraw);
}

function closeSignaturePad(){
    document.getElementById("signatureModal").classList.remove("active");
}



// ================= DECLARATION CHECK =================
const declarationCheckbox=document.getElementById("declarationCheck");
const submitBtn=document.getElementById("submitBtn");

declarationCheckbox.addEventListener("change",function(){
    if(this.checked){
        submitBtn.disabled=false;
        submitBtn.style.background="#003366";
        submitBtn.style.cursor="pointer";
    } else {
        submitBtn.disabled=true;
        submitBtn.style.background="#999";
        submitBtn.style.cursor="not-allowed";
    }
});

// ================= FORM SUBMIT =================
const form=document.querySelector("form");

form.addEventListener("submit",function(e){
    form.submit();
});
</script>

<br><br>

</form>
</div>
</div>

@include('layouts.footer') 

@endsection