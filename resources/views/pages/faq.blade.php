@extends('layouts.navbar')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/faq.css') }}">



<section class="faq-page">

<div class="faq-container">

<div class="faq-header">

<h1>Frequently Asked Questions</h1>

<p>Everything you need to know about Fil Products services.</p>

</div>



<!-- FAQ ITEM -->

<div class="faq-item">

<div class="faq-question">
1. What are the Requirements to apply Internet with Free Cable TV?
<i class="fas fa-chevron-down"></i>
</div>

<div class="faq-answer">

Two (2) valid IDs of the applicant<br>
Proof of Billing (Water or Electric Bill)<br><br>

If it is not under your name, you need to have:<br>
• Authorization Letter from the owner<br>
• Owner ID (Photocopy)<br><br>

If the owner has already died you need:<br>
• Death Certificate or Brgy Certificate

</div>

</div>



<!-- FAQ -->

<div class="faq-item">

<div class="faq-question">
2. Is there a contract?
<i class="fas fa-chevron-down"></i>
</div>

<div class="faq-answer">
Yes, there is a <strong>3 years contract</strong>.
</div>

</div>



<div class="faq-item">

<div class="faq-question">
3. Is the installation free?
<i class="fas fa-chevron-down"></i>
</div>

<div class="faq-answer">
Yes, installation is free with up to <strong>150 meters free wire</strong>.
</div>

</div>



<div class="faq-item">

<div class="faq-question">
4. Where can I pay the bill?
<i class="fas fa-chevron-down"></i>
</div>

<div class="faq-answer">
You can pay at the nearest <strong>Fil Products office</strong> in your area or through our <strong>payment partners</strong>.
</div>

</div>



<div class="faq-item">

<div class="faq-question">
5. What are the Requirements to apply Filbiz Plan?
<i class="fas fa-chevron-down"></i>
</div>

<div class="faq-answer">

• Business Permit<br>
• DTI or SEC Registration<br>
• Authorized Signatory’s Valid ID

</div>

</div>



<!-- GCASH PAYMENT -->

<div class="faq-item">

<div class="faq-question">
How to Pay Bill using GCash?
<i class="fas fa-chevron-down"></i>
</div>

<div class="faq-answer">

<div class="gcash-steps">

<div class="gcash-step">
<img src="images/1.png">
<p>Step 1: Tap Pay Bills</p>
</div>

<div class="gcash-step">
<img src="images/2.png">
<p>Step 2: Tap Cable/Internet</p>
</div>

<div class="gcash-step">
<img src="images/3.png">
<p>Step 3: Search Fil Products Inc</p>
</div>

<div class="gcash-step">
<img src="images/4.png">
<p>Step 4: Enter Amount, Account Number, and Account Name</p>
</div>

</div>

</div>

</div>
</div>
</section>

<script>

/* FAQ ACCORDION */

document.querySelectorAll(".faq-question").forEach(item=>{
item.addEventListener("click",()=>{

const parent=item.parentElement;

parent.classList.toggle("active");

});
});

</script>

@include('layouts.footer') 

@endsection