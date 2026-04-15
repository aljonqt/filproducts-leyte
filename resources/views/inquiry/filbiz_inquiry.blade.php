@extends('layouts.navbar')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="{{ asset('css/filbiz.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="page-wrapper">
<div class="form-container">

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:15px;margin-bottom:20px;border-radius:6px;">
{{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('filbiz.submit') }}" enctype="multipart/form-data">
@csrf

<div class="section-header">Filbiz Application Form</div>

<div class="section-title">BUSINESS OR COMPANY INFORMATION</div>
<input type="hidden" name="branch" value="butuan">
<div class="row row-2">
<div class="form-group">
<label>Business or Company Name</label>
<input type="text" name="companyname" required>
</div>

<div class="form-group">
<label>Nature of Business</label>
<input type="text" name="natureofbusiness" required>
</div>

</div>

<div class="form-group">
<label>Business or Main Office Address</label>
<input type="text" name="businessaddress" required>
</div>

<br>

<div class="section-title">Personal Information (Authorized Signatory)</div>

<div class="row row-3">

<div class="form-group">
<label>First Name</label>
<input type="text" name="first_name" required>
</div>

<div class="form-group">
<label>Middle Name</label>
<input type="text" name="middle_name">
</div>

<div class="form-group">
<label>Last Name</label>
<input type="text" name="last_name" required>
</div>

</div>

<div class="row row-3">

<div class="form-group">
<label>Mobile No.</label>
<input type="text" name="mobile_no" required>
</div>

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Landline</label>
<input type="text" name="landline">
</div>

</div>

<div class="row row-2">

<div class="form-group">
<label>Position</label>
<input type="text" name="position" required>
</div>

<div class="form-group">
<label>Company Contact Person</label>
<input type="text" name="contact_person" required>
</div>

</div>

<div class="section-title" style="margin-top:40px;">
BUSINESS / FILBIZ SUBSCRIPTION
</div>

<div class="plan-grid">

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="Up to 100MBPS + Premium Cable TV PHP 1,299" required>
<h4>Business Starter</h4>
<p class="speed">100 Mbps</p>
<span class="price">₱1,299</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="Up to 200MBPS + Premium Cable TV PHP 1,599">
<h4>Business Pro</h4>
<p class="speed">200 Mbps</p>
<span class="price">₱1,599</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="Up to 300MBPS + Premium Cable TV PHP 1,899">
<h4>Business Premium</h4>
<p class="speed">300 Mbps</p>
<span class="price">₱1,899</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

<label class="plan-card">
<input type="radio" name="monthly_subscription" value="Up to 500MBPS + Premium Cable TV PHP 2,000">
<h4>Enterprise</h4>
<p class="speed">500 Mbps</p>
<span class="price">₱2,000</span>
<p class="plan-desc">Free Premium Cable TV</p>
</label>

</div>

<div class="attachment-section">

    <div class="section-title" style="margin-top:40px;">
        📎 Required Document Attachments
    </div>

    <div class="attachment-grid">

        <!-- BUSINESS PERMIT -->
        <div class="upload-card required">
            <label>Business Permit <span>*</span></label>

            <div class="upload-box">
            <i class="fas fa-building"></i>
            <span>Upload Business Permit</span>
            <small>JPG, PNG, PDF (Max 5MB)</small>
        
            <input type="file"
                   name="business_permit"
                   class="file-input"
                   accept=".jpg,.jpeg,.png,.pdf"
                   required>
        
            <div class="file-name">No file chosen</div>
        </div>
        </div>

        <!-- DTI / SEC -->
        <div class="upload-card required">
            <label>DTI / SEC Registration <span>*</span></label>

            <div class="upload-box">
                <i class="fas fa-file-contract"></i>
                <span>Upload Registration</span>
                <small>JPG, PNG, PDF (Max 5MB)</small>

                <input type="file"
                   name="dti_sec"
                   class="file-input"
                   accept=".jpg,.jpeg,.png,.pdf"
                   required>
            
            <div class="file-name">No file chosen</div>
            </div>
        </div>

        <!-- VALID ID -->
        <div class="upload-card required">
            <label>Authorized Signatory Valid ID <span>*</span></label>

            <div class="upload-box">
                <i class="fas fa-id-card"></i>
                <span>Upload Valid ID</span>
                <small>JPG, PNG, PDF (Max 5MB)</small>
                <input type="file"
                       name="valid_id"
                       class="file-input"
                       accept=".jpg,.jpeg,.png,.pdf"
                       required>
                
                <div class="file-name">No file chosen</div>
            </div>
        </div>

</div>

</div>
<div class="section-title" style="margin-top:40px;">
SUBSCRIBER'S DECLARATION
</div>

<div class="row declaration-grid">

<!-- LEFT SIDE -->
<div>

<div style="display:flex; align-items:center; gap:10px; margin-bottom:15px; " >

<input type="checkbox"
id="declarationCheck"
name="declaration_agree"
value="1"
style="width:18px;height:18px;cursor:pointer;">

<span
style="color:#003366;font-weight:600;text-decoration:underline;cursor:pointer; max-width:500px;"
onclick="openDeclaration()">

I have read and agree to the Subscriber's Declaration

</span>

</div>

<button
type="button"
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
padding:12px 40px;
border:none;
border-radius:6px;
font-weight:600;
cursor:not-allowed;
font-size:15px;
">

Submit Residential Inquiry

</button>


<input type="hidden" name="digital_signature" id="digitalSignatureInput">

</div>


<!-- RIGHT SIDE SIGNATURE PREVIEW -->
<div>

<label style="font-weight:600;color:#003366;">
Signature Preview
</label>

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

<img
id="signaturePreview"
style="max-height:100px;display:none;">

<span
id="signaturePlaceholder"
style="color:#888;">
No signature yet
</span>

</div>

</div>

<!-- COLUMN 3 : HOUSE LOCATION MAP -->
<div>

<label style="font-weight:600;color:#003366;">Mark Your House Location</label>

<div id="map" style="
height:200px;
border-radius:8px;
border:2px solid #003366;
margin-top:8px;
"></div>

<input type="hidden" name="latitude" id="latitude">
<input type="hidden" name="longitude" id="longitude">
<input type="hidden" name="map_image" id="mapImage">

<div style="margin-top:8px;font-size:12px;color:#666;">
Click the map to mark your house location.
</div>

<button type="button"
onclick="getUserLocation()"
style="
margin-top:8px;
background:#28a745;
color:white;
padding:6px 12px;
border:none;
border-radius:5px;
cursor:pointer;
font-size:13px;
">
Auto Detect My Location
</button>

</div>

</div>


</form>

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
            border-radius:9px;
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

<style>
/* SMALLER CUSTOM MAP PIN */

.map-pin{
    width:24px;
    height:24px;
    background:#e60023;
    border-radius:50% 50% 50% 0;
    transform:rotate(-45deg);
    position:relative;
    box-shadow:0 3px 8px rgba(0,0,0,0.25);
}

.map-pin::after{
    content:"";
    width:9px;
    height:9px;
    background:white;
    position:absolute;
    top:7px;
    left:7px;
    border-radius:50%;
}

.pin-shadow{
    width:7px;
    height:7px;
    background:#e60023;
    border-radius:50%;
    position:absolute;
    top:26px;
    left:9px;
}

.pin-drop{
    animation:pinDrop 0.35s ease-out;
}

@keyframes pinDrop{
    0%{
        transform:translateY(-120px) rotate(-45deg);
        opacity:0;
    }
    70%{
        transform:translateY(8px) rotate(-45deg);
    }
    100%{
        transform:translateY(0) rotate(-45deg);
        opacity:1;
    }
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

// ================= MAP =================

let map;
let marker;

const modernIcon = L.divIcon({
    className:'',
    html:`
        <div style="position:relative">
            <div class="map-pin"></div>
            <div class="pin-shadow"></div>
        </div>
    `,
    iconSize:[24,32],
    iconAnchor:[12,30]
});

function initMap(){

const defaultLat = 12.0665;
const defaultLng = 124.5965;

map = L.map('map',{
    zoomControl:true
}).setView([defaultLat, defaultLng],15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    maxZoom:19
}).addTo(map);


// ================= FIXED CENTER MARKER =================

marker = L.marker(map.getCenter(),{
    icon: modernIcon,
    interactive:false
}).addTo(map);

// ================= MARKER ANIMATION FUNCTION =================

function animatePin(){

    const el = marker.getElement();
    if(!el) return;

    const pin = el.querySelector(".map-pin");

    pin.classList.remove("pin-drop");

    void pin.offsetWidth; // restart animation

    pin.classList.add("pin-drop");

}

setTimeout(()=>{
    animatePin();
},200);



// SAVE INITIAL COORDINATES

document.getElementById("latitude").value = defaultLat;
document.getElementById("longitude").value = defaultLng;


// ================= WHEN MAP MOVES =================

map.on("move",function(){

    const center = map.getCenter();

    marker.setLatLng(center);

    document.getElementById("latitude").value = center.lat;
    document.getElementById("longitude").value = center.lng;

});

map.on("moveend",function(){

    animatePin();

});

}

// ================= FILE UPLOAD DISPLAY =================
document.querySelectorAll('.file-input').forEach(input => {

    input.addEventListener('change', function () {

        const file = this.files[0];
        const uploadBox = this.closest('.upload-box');

        if (!uploadBox) return;

        let fileNameDiv = uploadBox.querySelector('.file-name');

        // auto-create if missing (extra safe)
        if (!fileNameDiv) {
            fileNameDiv = document.createElement('div');
            fileNameDiv.className = 'file-name';
            uploadBox.appendChild(fileNameDiv);
        }

        if (!file) {
            fileNameDiv.textContent = "No file chosen";
            uploadBox.classList.remove('has-file');
            return;
        }

        fileNameDiv.textContent = file.name;
        uploadBox.classList.add('has-file');

    });

});

// ================= LOAD MAP =================

window.addEventListener("load",initMap);



// ================= GPS LOCATION =================

function getUserLocation(){

if(navigator.geolocation){

navigator.geolocation.getCurrentPosition(function(position){

const lat = position.coords.latitude;
const lng = position.coords.longitude;

map.setView([lat,lng],17);

marker.setLatLng([lat,lng]);

animateMarker();

document.getElementById("latitude").value = lat;
document.getElementById("longitude").value = lng;

},function(){

alert("Unable to detect your location.");

});

}else{

alert("Geolocation not supported by this browser.");

}

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
const form = document.querySelector("form");

form.addEventListener("submit", function(e){

    e.preventDefault();

    // ensure map tiles are loaded
    map.whenReady(function(){

        setTimeout(function(){

            html2canvas(document.querySelector("#map"),{
                useCORS:true,
                allowTaint:true,
                scale:2
            }).then(canvas => {

                const mapImage = canvas.toDataURL("image/png");

                document.getElementById("mapImage").value = mapImage;

                form.submit();

            });

        },500); // wait for tiles

    });

});


</script>

@include('layouts.footer') 

@endsection