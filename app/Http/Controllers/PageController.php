<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use TCPDF;

class PageController extends Controller
{

    public function home()
    {
        return view('pages.home');
    }

    public function news()
    {
        return view('pages.news');
    }

    public function complaint()
    {
        return view('pages.complaint');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function residentialInquiry()
    {
        return view('inquiry.residential_inquiry');
    }

    public function residentialUpgrade()
    {
        return view('inquiry.residential_upgrade');
    }

    public function filbizInquiry()
    {
        return view('inquiry.filbiz_inquiry');
    }

    public function filbizUpgrade()
    {
        return view('inquiry.filbiz_upgrade');
    }

public function submitComplaint(Request $request)
{
    $request->validate([
        'account_name' => 'required',
        'address' => 'required',
        'branch' => 'required',
        'remarks' => 'required',
        'email' => 'required|email'
    ]);

    /* =========================================
       GENERATE TICKET NUMBER (NO DB)
    ========================================= */
    $ticket = 'FP-' . date('Y') . '-' . rand(100000, 999999);

    /* =========================================
       SEND DATA TO GOOGLE SHEETS
    ========================================= */

    $sheetUrls = [
        'butuan' => 'https://script.google.com/macros/s/AKfycbwiIt66z5yUY4-ai7ggewzidl-Jy-8F61swz9C-yiqmdCBMIFpGkkhPt_GzxKYnc2RI/exec',
    ];

    $branch = 'butuan';

    if (isset($sheetUrls[$branch])) {
        Http::post($sheetUrls[$branch], [
            "date" => now()->format('Y-m-d'),
            "sales_quote" => $request->mobile_number,
            "subscriber_name" => $request->account_name,
            "address" => $request->address,
            "branch" => $branch,
            "job_type" => $request->remarks,
            "prepared_by" => "Website Complaint",
            "field_job" => ""
        ]);
    }

    /* =========================================
       SEND EMAIL
    ========================================= */

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'mail.filproducts-cyg.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@filproducts-cyg.com';
        $mail->Password = '8kKAahOE*.E,7uJZ';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('noreply@filproducts-cyg.com', 'Fil Products Samar');

                // SEND TO SUPPORT
        $mail->addAddress('info.cyg@filproducts.ph');

        // SEND TO CUSTOMER
        $mail->addAddress($request->email);

        // OPTIONAL: set reply-to as customer
        $mail->addReplyTo($request->email, $request->account_name);

        $mail->Subject = "Customer Complaint";
        $mail->isHTML(true);

        $mail->Body = "
        <h3>New Complaint</h3>
        <p><b>Name:</b> {$request->account_name}</p>
        <p><b>Email:</b> {$request->email}</p>
        <p><b>Branch:</b> {$branch}</p>
        <p><b>Remarks:</b> {$request->remarks}</p>
        ";

        $mail->send();

    } catch (\Exception $e) {
    return back()->with('error', $e->getMessage());
}

    return redirect()
        ->route('complaint')
        ->with('success', "Complaint submitted successfully.");
}


// FILBIZ INQUIRY //
public function submitFilbiz(Request $request)
{
    if(!$request->declaration_agree){
        return back()->withErrors('You must agree to the declaration.');
    }

    /* ================= BRANCH LOGIC ================= */
    $branch = 'butuan';

    $branches = [
        'butuan' => [
            'name' => 'FIL PRODUCTS SERVICE TELEVISION, INC.',
            'address' => 'N&D Bldg., Alviola Village Baan KM., Butuan City',
            'contact' => '0917-320-5871 / 0938-320-5871'
        ],
    ];

    $branchData = $branches['butuan'];

    $companyName = $request->companyname;
    $natureBiz   = $request->natureofbusiness;
    $businessAddr= $request->businessaddress;

    $firstName = $request->first_name;
    $middleName= $request->middle_name;
    $lastName  = $request->last_name;

    $mobile = $request->mobile_no;
    $email  = $request->email;
    $landline = $request->landline;
    $position = $request->position;
    $contactPerson = $request->contact_person;

    $subscription = $request->monthly_subscription;
    $signatureData = $request->digital_signature;

    /* ================= SAVE PDF ================= */
$cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $companyName);
$cleanName = str_replace(' ', '_', trim($cleanName));

$fileName = $cleanName . '_Filbiz_Application.pdf';

/* =========================
   HANDLE FILE UPLOADS
========================== */

$uploadDir = storage_path('app/public/attachments/');

if(!file_exists($uploadDir)){
    mkdir($uploadDir,0777,true);
}

$attachments = [];

function saveFile($request, $field, $uploadDir){
    if($request->hasFile($field)){
        $file = $request->file($field);
        $name = time().'_'.$file->getClientOriginalName();
        $file->move($uploadDir, $name);
        return $uploadDir.$name;
    }
    return null;
}

$businessPermitPath = saveFile($request, 'business_permit', $uploadDir);
$dtiSecPath        = saveFile($request, 'dti_sec', $uploadDir);
$validIdPath       = saveFile($request, 'valid_id', $uploadDir);

/* =========================
       GENERATE PDF
========================== */

$pdf = new TCPDF();

$pdf->AddPage();

$pdf = new TCPDF('P', 'mm', array(216, 330), true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

/* ================= HEADER ================= */

$logo = public_path('images/fil-products-logo.png');
if (file_exists($logo)) {
    $pdf->Image($logo, 15, 14, 20);
}

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetXY(0, 16);
$pdf->Cell(216, 7, $branchData['name'], 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, strtoupper($branchData['address']), 0, 1, 'C');
$pdf->Cell(0, 5, 'CEL. NOS.: ' . $branchData['contact'], 0, 1, 'C');
$pdf->Cell(0,5,'Email: info.cyg@filproducts.ph | Website: www.filproducts-cyg.com',0,1,'C');

$pdf->Ln(15);

/* ================= TITLE ================= */

$pdf->SetFont('helvetica','B',14);
$pdf->SetTextColor(180,0,0);
$pdf->Cell(0,8,'FILBIZ APPLICATION FORM',0,1);

$pdf->SetFont('helvetica','',10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,6,'Date Applied: '.date('F d, Y'),0,1);

$pdf->Ln(5);

/* ================= COMPANY INFO ================= */

$pdf->SetFillColor(240,240,240);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(0,7,'COMPANY INFORMATION',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(60, 6, 'Business or Company Name:', 0, 0);
$pdf->Cell(0, 6, $companyName, 0, 1);

$pdf->Cell(60, 6, 'Nature of Business:', 0, 0);
$pdf->Cell(0, 6, $natureBiz, 0, 1);

$pdf->Cell(60, 6, 'Business Address:', 0, 0);
$pdf->Cell(0, 6, $businessAddr, 0, 1);

$pdf->Ln(4);

/* ================= AUTHORIZED SIGNATORY ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'PERSONAL INFORMATION (AUTHORIZED SIGNATORY)',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);
$fullName = trim("$firstName $middleName $lastName");

$pdf->Cell(60, 6, 'Full Name:', 0, 0);
$pdf->Cell(0, 6, $fullName, 0, 1);

$pdf->Cell(60, 6, 'Position:', 0, 0);
$pdf->Cell(0, 6, $position, 0, 1);

$pdf->Cell(60, 6, 'Mobile:', 0, 0);
$pdf->Cell(0, 6, $mobile, 0, 1);

$pdf->Cell(60, 6, 'Landline:', 0, 0);
$pdf->Cell(0, 6, $landline, 0, 1);

$pdf->Cell(60, 6, 'Email:', 0, 0);
$pdf->Cell(0, 6, $email, 0, 1);

$pdf->Ln(5);

/* ================= TYPE OF BUSINESS ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'TYPE OF BUSINESS',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0,6,'[ ] Corporation / Partnership / Cooperative / Association',0,1);
$pdf->Cell(0,6,'[ ] Sole Proprietorship',0,1);
$pdf->Cell(0,6,'[ ] LGU',0,1);

$pdf->Ln(4);


/* ================= CHECKLIST OF DOCUMENT REQUIREMENT ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'CHECKLIST OF DOCUMENT REQUIREMENT',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(0,6,'[ ] Photocopy of Valid ID of Authorized Signatory (front & back)',0,1);
$pdf->Cell(0,6,'[ ] Photocopy of Valid ID of Contact Person (front & back)',0,1);
$pdf->Cell(0,6,'[ ] Photocopy of COR',0,1);

$pdf->Ln(4);


/* ================= TAX EXEMPTION ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'FOR ELIGIBILITY OF TAX EXEMPTION',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);

$pdf->MultiCell(
    0,
    6,
    '[ ] Photocopy of Tax Exemption Certificate Issued by BIR or Authorized Government Agency',
    0,
    'L'
);

$pdf->Ln(4);


/* ================= SELECTED PLAN ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'Subscription',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);

if (!empty($subscription)) {
    $pdf->MultiCell(0, 6, $subscription, 0, 'L');
    $pdf->Ln(6);
}

/* ================= SAVE SIGNATURE TO SIGNATURE FOLDER ================= */

$sigPath = '/Signature';

if (!empty($signatureData)) {

    $sig = preg_replace('#^data:image/\w+;base64,#i', '', $signatureData);
    $sigImage = base64_decode($sig);

    $img = imagecreatefromstring($sigImage);

    if ($img !== false) {

        // Create Signature folder if not exists
        $signatureDir = __DIR__ . '/Signature';

        if (!is_dir($signatureDir)) {
            mkdir($signatureDir, 0777, true);
        }

        // Clean company name for filename
        $cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $companyName);
        $cleanName = str_replace(' ', '_', trim($cleanName));

        $sigFileName = $cleanName . '_signature_' . time() . '.jpg';
        $sigPath = $signatureDir . '/' . $sigFileName;

        // Create white background
        $whiteBg = imagecreatetruecolor(imagesx($img), imagesy($img));
        $white = imagecolorallocate($whiteBg, 255, 255, 255);
        imagefill($whiteBg, 0, 0, $white);

        imagecopy($whiteBg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));

        // Save as JPG
        imagejpeg($whiteBg, $sigPath, 95);

        imagedestroy($img);
        imagedestroy($whiteBg);

        // Insert into PDF (Page 1)
        $pageWidth = 216;
        $centerX = ($pageWidth / 2) - 30;
        $pdf->Image($sigPath, $centerX, $pdf->GetY(), 60);
    }
}

$pdf->Ln(22);

$pdf->SetFont('helvetica', 'U', 10);
$pdf->Cell(0, 6, $fullName, 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Signature Over Printed Nmae/Date', 0, 1, 'C');



/* ================= PAGE 2 ================= */
$pdf->AddPage(); 

/* DECLARATION */
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'SUBSCRIBER\'S DECLARATIONS', 0, 1);

$pdf->SetFont('helvetica', '', 9);

$declaration = "
    1. I hereby confirm that the foregoing information is true and correct, that supporting documents attached hereto are
    genuine and authentic, and that I voluntarily submitted the said information and documents for the purpose of facilitating my
    application to the Service.

    2. I hereby further confirm that I applied for and, once my application is approved, that I have voluntarily availed of the
    plans, products and/or services chosen by me in this application form, as well as the inclusions and special features of such
    plans, products and/or services and that any enrolment I have indicated herein have been knowingly made by me.

    3. I hereby authorize FIL PRODUCTS SERVICE TELEVISION OF CALBAYOG, INC. (hereinafter you) or any person or
    entity authorized by you, to verify any information about me and/or documents available from whatever source including but
    not limited to (i) your subsidiaries, affiliates, and/or their service providers: or (ii) banks, credit card companies, and other
    lending and/or financial institution, and I hereby authorize the holder, controller and processor of such information and/or
    document, has the same is defined in Republic Act No. 10173 (otherwise known as Data Privacy Act of 2012), or any
    amendment or modification of the same, to conform, release and verify the existence, truthfulness, and/or accuracy of such
    information and/or document.
    
    4. I give you permission to use, disclose and share with your business partners, subsidiaries and affiliates (and their
    business partners) information contained in this application about me and my subscription, my network and connections, my
    service usage and payment patterns, information about the device and equipment I use to access you service, websites
    and app used in your services, information from your third party partners and advertisers, including any data or analytics
    derived therefrom, in whatever form (hereinafter Personal Information), for the following purposes: processing any
    application or request for availment of any product and/or service which they offer, improving your/ their products and
    services, credit investigation and scoring, advertising and promoting new products and services, to the end of improving my
    and/or the public's customer experience.

    5. I consent to your business partners', subsidiaries' and affiliates' (and their business partners') disclosure to you of any
    Personal Information in their possession to achieve any of the purposes stated above.

    6. I hereby likewise authorize you, your business partners, subsidiaries and affiliates, to send me SMS alerts or any
    communication, advertisement or promotional material pertaining to any new or current product and/or service offered by
    you, your business partners, subsidiaries and affiliates

    7. I acknowledge and agree to the Holding Period for the relevant service availed of. If I choose to downgrade my plan,
    transfer and rights or obligations of my subscription or pre-terminate or cancel my subscription within the Holding Period
    then I agree to pay the relevant fees, charges and penalties imposed by you.

    8. I am aware of the fees, rates and charges relevant of the service availed of and I agree to pay the same within the due
    dates. I understand that I will be subject to, and hereby agree and undertake, interest and penalties for late payment or non-payment stated in the terms and condition.

    9. I hereby confirm that I have read and understood the Terms and Conditions of our Subscription Agreement and that I
    shall strictly comply and abide by these terms and conditions and any future amendments thereto.

    10. I agree that this Subscription Agreement shall govern our relationship for the service currently availed of and the service
    I will avail of in the future.

    11. I agree to pay my application's cancellation fee equivalent to 20% of application charges (Deposit, Installation fee and
    equipments).
";

$pdf->MultiCell(0, 5, $declaration);

$pdf->Ln(10);


/* ================= REUSE SAVED SIGNATURE ================= */

if (!empty($sigPath) && file_exists($sigPath)) {

    $pageWidth = 216;
    $centerX = ($pageWidth / 2) - 30;

    $pdf->Image($sigPath, $centerX, $pdf->GetY(), 60);
}

$pdf->Ln(22);

$pdf->SetFont('helvetica', 'U', 10);
$pdf->Cell(0, 6, $fullName, 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Signature Over Printed Name of Authorized Representative ', 0, 1, 'C');

/* =========================================================
   INTERNAL USE / ASSIGNMENT SECTION (COMPACT)
========================================================= */

$pdf->Ln(3); // reduced spacing

$pdf->SetFont('helvetica', '', 9); // smaller font

$labelWidth = 32;   // reduced
$fieldWidth = 50;   // reduced
$height = 6;        // reduced box height

$pdf->Cell($labelWidth, $height, 'Referred by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 1);

/* Row 2 */
$pdf->Ln(2);

$pdf->Cell($labelWidth, $height, 'Checked by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 0);

$pdf->Cell(10);

$pdf->Cell($labelWidth, $height, 'Approved by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 1);


/* ================= MAP PAGE ================= */

if(!empty($request->map_image)){

    $pdf->AddPage();

    $pdf->SetFont('helvetica','B',12);
    $pdf->Cell(0,6,'HOUSE LOCATION MAP',0,1,'C');

    $pdf->Ln(5);

    // decode base64 map
    $mapData = preg_replace('#^data:image/\w+;base64,#i','',$request->map_image);
    $mapDecoded = base64_decode($mapData);

    // save temporary image
    $mapFile = storage_path('app/public/map_'.time().'.png');
    file_put_contents($mapFile,$mapDecoded);

    // insert image to PDF (FULL WIDTH)
    $pdf->Image($mapFile, 10, 30, 190);

    $pdf->Ln(120);

    // coordinates
    $pdf->SetFont('helvetica','',10);
    $pdf->Cell(0,6,'Latitude: '.$request->latitude,0,1,'C');
    $pdf->Cell(0,6,'Longitude: '.$request->longitude,0,1,'C');
}

/* ================= SAVE PDF ================= */
$cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $companyName);
$cleanName = str_replace(' ', '_', trim($cleanName));

$fileName = $cleanName . '_Filbiz_Application.pdf';

$pdfContent = $pdf->Output('', 'S');
/* ============================
   SAVE PDF FILE
============================ */

$pdfDir = storage_path('app/public/applications/');

if(!file_exists($pdfDir)){
    mkdir($pdfDir,0777,true);
}

$pdfPath = $pdfDir.$fileName;

file_put_contents($pdfPath,$pdfContent);


/* =========================
   BRANCH EMAIL MAP
========================== */

$branchEmails = [
    'butuan' => 'info.cyg@filproducts.ph'
];

/* ✅ USE REQUEST ONLY */
$selectedBranch = strtolower($request->branch ?? '');
$branch = $request->branch ?? 'N/A';

/* =========================
   SEND EMAIL
========================== */

$mail = new PHPMailer(true);

$mail->isSMTP();

/* SMTP */
$mail->Host = 'mail.filproducts-cyg.com';
$mail->SMTPAuth = true;
$mail->Username = 'noreply@filproducts-cyg.com';
$mail->Password = '8kKAahOE*.E,7uJZ';

$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;

/* SSL FIX */
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];

/* FROM */
$mail->setFrom('noreply@filproducts-cyg.com', 'Fil Products Samar');

/* HEADERS */
$mail->Sender = 'noreply@filproducts-cyg.com';
$mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());

/* ============================
   RECIPIENTS
============================ */

$customerEmail = $email ?? null;

/* ✅ SEND TO CUSTOMER */
if ($customerEmail) {
    $mail->addAddress($customerEmail);
    $mail->addReplyTo($customerEmail, $companyName);
}

/* ✅ SEND TO BRANCH */
if (isset($branchEmails[$selectedBranch])) {
    $mail->addAddress($branchEmails['butuan']);
} else {
    $mail->addAddress('info.cyg@filproducts.ph');
}

/* ✅ ALWAYS SEND TO ADMIN (OPTIONAL BUT GOOD) */
$mail->addCC('info.cyg@filproducts.ph');

/* =========================
   CONTENT
========================== */

$mail->isHTML(true);
$mail->CharSet = 'UTF-8';

$mail->Subject = "Filbiz Application - " . $companyName;

$mail->Body = "
<h3 style='margin-bottom:10px;'>Filbiz Application</h3>

<p><b>Company:</b> {$companyName}</p>
<p><b>Applicant:</b> {$firstName} {$lastName}</p>
<p><b>Email:</b> {$email}</p>
<p><b>Branch:</b> {$branch}</p>
<p><b>Plan:</b> {$subscription}</p>

<hr>

<p style='font-size:12px;color:#555;'>
This application was submitted via Fil Products System.
</p>
";

/* =========================
   ATTACHMENTS
========================== */

$mail->addStringAttachment($pdfContent, $fileName);

/* =========================
   SEND
========================== */
$mail->send();



    return redirect()
        ->route('filbiz.inquiry')
        ->with('success','
    ✅Your Filbiz Application has been successfully submitted.
    📧 A copy of your application form has been sent to your email.
    Our Fil Products team will review your application and contact you shortly for the next steps.
    ');

}


// FILBIZ UPGRADE // 
public function submitFilbizUpgrade(Request $request)
{

    if(!$request->declaration_agree){
        return back()->withErrors('You must agree to the declaration.');
    }

    /* ================= BRANCH LOGIC ================= */
    $branch = 'butuan';

    $branches = [
            'butuan' => [
                'name' => 'FIL PRODUCTS SERVICE TELEVISION, INC.',
                'address' => 'N&D Bldg., Alviola Village Baan KM., Butuan City',
                'contact' => '0917-320-5871 / 0938-320-5871'
            ],
        ];

    $branchData = $branches['butuan'];

    $companyName = $request->companyname;
    $natureBiz   = $request->natureofbusiness;
    $businessAddr= $request->businessaddress;

    $firstName = $request->first_name;
    $middleName= $request->middle_name;
    $lastName  = $request->last_name;

    $mobile = $request->mobile_no;
    $email  = $request->email;
    $landline = $request->landline;
    $position = $request->position;
    $contactPerson = $request->contact_person;

    $subscription = $request->monthly_subscription;
    $signatureData = $request->digital_signature;

    /* ================= SAVE PDF ================= */
$cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $companyName);
$cleanName = str_replace(' ', '_', trim($cleanName));

$fileName = $cleanName . '_Filbiz_Upgrade.pdf';

    /* =============================
       GENERATE PDF
    ============================== */

    $pdf = new TCPDF();

    $pdf->AddPage();

    $pdf = new TCPDF('P', 'mm', array(216, 330), true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

/* ================= HEADER ================= */

$logo = public_path('images/fil-products-logo.png');
if (file_exists($logo)) {
    $pdf->Image($logo, 15, 14, 20);
}

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetXY(0, 16);
$pdf->Cell(216, 7, $branchData['name'], 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, strtoupper($branchData['address']), 0, 1, 'C');
$pdf->Cell(0, 5, 'CEL. NOS.: ' . $branchData['contact'], 0, 1, 'C');
$pdf->Cell(0,5,'Email: info.cyg@filproducts.ph | Website: www.filproducts-cyg.com',0,1,'C');
$pdf->Ln(15);


/* ================= TITLE ================= */

$pdf->SetFont('helvetica','B',14);
$pdf->SetTextColor(180,0,0);
$pdf->Cell(0,8,'FILBIZ UPGRADE FORM',0,1);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Date Applied: ' . date('m/d/Y'), 0, 1);

$pdf->Ln(5);

/* ================= COMPANY INFO ================= */

$pdf->SetFillColor(240,240,240);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(0,7,'COMPANY INFORMATION',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(60, 6, 'Business or Company Name:', 0, 0);
$pdf->Cell(0, 6, $companyName, 0, 1);

$pdf->Cell(60, 6, 'Nature of Business:', 0, 0);
$pdf->Cell(0, 6, $natureBiz, 0, 1);

$pdf->Cell(60, 6, 'Business Address:', 0, 0);
$pdf->Cell(0, 6, $businessAddr, 0, 1);

$pdf->Ln(4);

/* ================= AUTHORIZED SIGNATORY ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'PERSONAL INFORMATION (AUTHORIZED SIGNATORY)',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);
$fullName = trim("$firstName $middleName $lastName");

$pdf->Cell(60, 6, 'Full Name:', 0, 0);
$pdf->Cell(0, 6, $fullName, 0, 1);

$pdf->Cell(60, 6, 'Position:', 0, 0);
$pdf->Cell(0, 6, $position, 0, 1);

$pdf->Cell(60, 6, 'Mobile:', 0, 0);
$pdf->Cell(0, 6, $mobile, 0, 1);

$pdf->Cell(60, 6, 'Landline:', 0, 0);
$pdf->Cell(0, 6, $landline, 0, 1);

$pdf->Cell(60, 6, 'Email:', 0, 0);
$pdf->Cell(0, 6, $email, 0, 1);

$pdf->Ln(5);

/* ================= TYPE OF BUSINESS ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'TYPE OF BUSINESS',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0,6,'[ ] Corporation / Partnership / Cooperative / Association',0,1);
$pdf->Cell(0,6,'[ ] Sole Proprietorship',0,1);
$pdf->Cell(0,6,'[ ] LGU',0,1);

$pdf->Ln(4);


/* ================= CHECKLIST OF DOCUMENT REQUIREMENT ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'CHECKLIST OF DOCUMENT REQUIREMENT',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(0,6,'[ ] Photocopy of Valid ID of Authorized Signatory (front & back)',0,1);
$pdf->Cell(0,6,'[ ] Photocopy of Valid ID of Contact Person (front & back)',0,1);
$pdf->Cell(0,6,'[ ] Photocopy of COR',0,1);

$pdf->Ln(4);


/* ================= TAX EXEMPTION ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'FOR ELIGIBILITY OF TAX EXEMPTION',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);

$pdf->MultiCell(
    0,
    6,
    '[ ] Photocopy of Tax Exemption Certificate Issued by BIR or Authorized Government Agency',
    0,
    'L'
);

$pdf->Ln(4);


/* ================= SELECTED PLAN ================= */

$pdf->SetFont('helvetica','B',11);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(0,7,'Subscription',0,1,'L',true);

$pdf->SetFont('helvetica', '', 10);

if (!empty($subscription)) {
    $pdf->MultiCell(0, 6, $subscription, 0, 'L');
    $pdf->Ln(6);
}

/* ================= SAVE SIGNATURE TO SIGNATURE FOLDER ================= */

$sigPath = '/Signature';

if (!empty($signatureData)) {

    $sig = preg_replace('#^data:image/\w+;base64,#i', '', $signatureData);
    $sigImage = base64_decode($sig);

    $img = imagecreatefromstring($sigImage);

    if ($img !== false) {

        // Create Signature folder if not exists
        $signatureDir = __DIR__ . '/Signature';

        if (!is_dir($signatureDir)) {
            mkdir($signatureDir, 0777, true);
        }

        // Clean company name for filename
        $cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $companyName);
        $cleanName = str_replace(' ', '_', trim($cleanName));

        $sigFileName = $cleanName . '_signature_' . time() . '.jpg';
        $sigPath = $signatureDir . '/' . $sigFileName;

        // Create white background
        $whiteBg = imagecreatetruecolor(imagesx($img), imagesy($img));
        $white = imagecolorallocate($whiteBg, 255, 255, 255);
        imagefill($whiteBg, 0, 0, $white);

        imagecopy($whiteBg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));

        // Save as JPG
        imagejpeg($whiteBg, $sigPath, 95);

        imagedestroy($img);
        imagedestroy($whiteBg);

        // Insert into PDF (Page 1)
        $pageWidth = 216;
        $centerX = ($pageWidth / 2) - 30;
        $pdf->Image($sigPath, $centerX, $pdf->GetY(), 60);
    }
}

$pdf->Ln(22);

$pdf->SetFont('helvetica', 'U', 10);
$pdf->Cell(0, 6, $fullName, 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Signature Over Printed Nmae/Date', 0, 1, 'C');



/* ================= PAGE 2 ================= */
$pdf->AddPage(); 

/* DECLARATION */
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'SUBSCRIBER\'S DECLARATIONS', 0, 1);

$pdf->SetFont('helvetica', '', 9);

$declaration = "
    1. I hereby confirm that the foregoing information is true and correct, that supporting documents attached hereto are
    genuine and authentic, and that I voluntarily submitted the said information and documents for the purpose of facilitating my
    application to the Service.

    2. I hereby further confirm that I applied for and, once my application is approved, that I have voluntarily availed of the
    plans, products and/or services chosen by me in this application form, as well as the inclusions and special features of such
    plans, products and/or services and that any enrolment I have indicated herein have been knowingly made by me.

    3. I hereby authorize FIL PRODUCTS SERVICE TELEVISION OF CALBAYOG, INC. (hereinafter you) or any person or
    entity authorized by you, to verify any information about me and/or documents available from whatever source including but
    not limited to (i) your subsidiaries, affiliates, and/or their service providers: or (ii) banks, credit card companies, and other
    lending and/or financial institution, and I hereby authorize the holder, controller and processor of such information and/or
    document, has the same is defined in Republic Act No. 10173 (otherwise known as Data Privacy Act of 2012), or any
    amendment or modification of the same, to conform, release and verify the existence, truthfulness, and/or accuracy of such
    information and/or document.
    
    4. I give you permission to use, disclose and share with your business partners, subsidiaries and affiliates (and their
    business partners) information contained in this application about me and my subscription, my network and connections, my
    service usage and payment patterns, information about the device and equipment I use to access you service, websites
    and app used in your services, information from your third party partners and advertisers, including any data or analytics
    derived therefrom, in whatever form (hereinafter Personal Information), for the following purposes: processing any
    application or request for availment of any product and/or service which they offer, improving your/ their products and
    services, credit investigation and scoring, advertising and promoting new products and services, to the end of improving my
    and/or the public's customer experience.

    5. I consent to your business partners', subsidiaries' and affiliates' (and their business partners') disclosure to you of any
    Personal Information in their possession to achieve any of the purposes stated above.

    6. I hereby likewise authorize you, your business partners, subsidiaries and affiliates, to send me SMS alerts or any
    communication, advertisement or promotional material pertaining to any new or current product and/or service offered by
    you, your business partners, subsidiaries and affiliates

    7. I acknowledge and agree to the Holding Period for the relevant service availed of. If I choose to downgrade my plan,
    transfer and rights or obligations of my subscription or pre-terminate or cancel my subscription within the Holding Period
    then I agree to pay the relevant fees, charges and penalties imposed by you.

    8. I am aware of the fees, rates and charges relevant of the service availed of and I agree to pay the same within the due
    dates. I understand that I will be subject to, and hereby agree and undertake, interest and penalties for late payment or non-payment stated in the terms and condition.

    9. I hereby confirm that I have read and understood the Terms and Conditions of our Subscription Agreement and that I
    shall strictly comply and abide by these terms and conditions and any future amendments thereto.

    10. I agree that this Subscription Agreement shall govern our relationship for the service currently availed of and the service
    I will avail of in the future.

    11. I agree to pay my application's cancellation fee equivalent to 20% of application charges (Deposit, Installation fee and
    equipments).
";

$pdf->MultiCell(0, 5, $declaration);

$pdf->Ln(10);


/* ================= REUSE SAVED SIGNATURE ================= */

if (!empty($sigPath) && file_exists($sigPath)) {

    $pageWidth = 216;
    $centerX = ($pageWidth / 2) - 30;

    $pdf->Image($sigPath, $centerX, $pdf->GetY(), 60);
}

$pdf->Ln(22);

$pdf->SetFont('helvetica', 'U', 10);
$pdf->Cell(0, 6, $fullName, 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Signature Over Printed Name of Authorized Representative ', 0, 1, 'C');

/* =========================================================
   INTERNAL USE / ASSIGNMENT SECTION (COMPACT)
========================================================= */

$pdf->Ln(3); // reduced spacing

$pdf->SetFont('helvetica', '', 9); // smaller font

$labelWidth = 32;   // reduced
$fieldWidth = 50;   // reduced
$height = 6;        // reduced box height

$pdf->Cell($labelWidth, $height, 'Referred by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 1);

/* Row 2 */
$pdf->Ln(2);

$pdf->Cell($labelWidth, $height, 'Checked by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 0);

$pdf->Cell(10);

$pdf->Cell($labelWidth, $height, 'Approved by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 1);

/* ================= SAVE PDF ================= */
$cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $companyName);
$cleanName = str_replace(' ', '_', trim($cleanName));

$fileName = $cleanName . '_Filbiz_Upgrade.pdf';

$pdfContent = $pdf->Output('', 'S');


/* ============================
   SAVE PDF FILE
============================ */

$pdfDir = storage_path('app/public/applications/');

if(!file_exists($pdfDir)){
    mkdir($pdfDir,0777,true);
}

$pdfPath = $pdfDir.$fileName;

file_put_contents($pdfPath,$pdfContent);


/* =========================
   BRANCH EMAIL MAP
========================== */

$branchEmails = [
    'calbayog'   => 'info.cyg@filproducts.ph',
    'allen'      => 'filproductsallen2022@gmail.com',
    'mondragon'  => 'mondragon.csr013026@gmail.com',
    'sanjorge'   => 'filproducts.sanjorge@gmail.com',
    'catarman'   => 'csrfilproductscatarman@gmail.com',
    'catbalogan' => 'filproducts.catbalogancsr@gmail.com',
    'tacloban'   => 'filproductsleyte@gmail.com',
    'palo'   => 'filproductsleyte@gmail.com',
];

/* ✅ USE REQUEST (NOT $_POST) */
$selectedBranch = strtolower($request->branch ?? '');
$branch = $request->branch ?? 'N/A';

/* =========================
   SEND EMAIL
========================== */

$mail = new PHPMailer(true);

$mail->isSMTP();

/* SMTP */
$mail->Host = 'mail.filproducts-cyg.com';
$mail->SMTPAuth = true;
$mail->Username = 'noreply@filproducts-cyg.com';
$mail->Password = '8kKAahOE*.E,7uJZ';

$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;

/* SSL FIX */
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];

/* FROM */
$mail->setFrom('noreply@filproducts-cyg.com', 'Fil Products Samar');

/* HEADERS */
$mail->Sender = 'noreply@filproducts-cyg.com';
$mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());

/* ============================
   RECIPIENTS
============================ */

$customerEmail = $email ?? null;

/* ✅ SEND TO CUSTOMER */
if ($customerEmail) {
    $mail->addAddress($customerEmail);
    $mail->addReplyTo($customerEmail, $companyName);
}

/* ✅ SEND TO SELECTED BRANCH */
if (isset($branchEmails[$selectedBranch])) {
    $mail->addAddress($branchEmails['butuan']);
} else {
    $mail->addAddress('info.cyg@filproducts.ph');
}

/* ✅ SEND COPY TO ADMIN */
$mail->addCC('info.cyg@filproducts.ph');

/* =========================
   CONTENT
========================== */

$mail->isHTML(true);
$mail->CharSet = 'UTF-8';

$mail->Subject = "Filbiz Upgrade - " . $companyName;

$mail->Body = "
<h3 style='margin-bottom:10px;'>Filbiz Upgrade Request</h3>

<p><b>Company:</b> {$companyName}</p>
<p><b>Applicant:</b> {$firstName} {$lastName}</p>
<p><b>Email:</b> {$email}</p>
<p><b>Branch:</b> {$branch}</p>
<p><b>Plan:</b> {$subscription}</p>

<hr>

<p style='font-size:12px;color:#555;'>
This upgrade request was submitted via Fil Products System.
</p>
";

/* =========================
   ATTACHMENT (SAFE)
========================== */

$mail->addStringAttachment($pdfContent, $fileName);

/* =========================
   SEND
========================== */

$mail->send();



    return redirect()
        ->route('filbiz.upgrade')
        ->with('success','✅ Your Filbiz Upgrade request has been successfully submitted.

📧 A copy of your request has been sent to your email for your reference.

Our team will review your upgrade request and contact you shortly regarding the next steps.

Thank you for choosing Fil Products Samar.
');

}


// RESIDENTIAL INQUIRY //
public function submitResidential(Request $request)
{

    if (!$request->declaration_agree) {
        return back()->with('error','You must agree to the Subscriber Declaration');
    }
        $branch = 'butuan';

        $branches = [
        'butuan' => [
            'name' => 'FIL PRODUCTS SERVICE TELEVISION, INC.',
            'address' => 'N&D Bldg., Alviola Village Baan KM., Butuan City',
            'contact' => '0917-320-5871 / 0938-320-5871'
        ],
    ];

    $branchData = $branches['butuan'];

    try {
        

/* ============================
   FULL NAME
============================ */

    $fullName = trim(
    $request->first_name.' '.
    $request->middle_name.' '.
    $request->last_name
);

/* ============================
   GENERATE FILE NAME
============================ */

    $cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $fullName);
    $cleanName = str_replace(' ', '_', trim($cleanName));

    $fileName = $cleanName.'_Residential_Application.pdf';
    $filePath = 'applications/'.$fileName;

        /* ============================
           SAVE ATTACHMENTS
        ============================ */

        $attachments = [];
        $files = ['valid_id','proof_billing','other_attachment'];

        foreach($files as $file){

            if($request->hasFile($file)){

                $path = $request->file($file)->store('attachments','public');

                $attachments[] = storage_path('app/public/'.$path);
            }

        }



        /* ============================
           SAVE SIGNATURE
        ============================ */

        $sigPath = null;

        if ($request->digital_signature) {

            $signatureData = preg_replace(
                '#^data:image/\w+;base64,#i',
                '',
                $request->digital_signature
            );

            $image = base64_decode($signatureData);

        $fileName = 'signature_'.time().'.png';

        $sigDir = storage_path('app/public/signatures/');

        if(!file_exists($sigDir)){
        mkdir($sigDir,0777,true);
    }

        $sigPath = $sigDir.$fileName;

        file_put_contents($sigPath,$image);
        }

/* ============================
   SAVE MAP IMAGE FROM FORM
============================ */

$mapPath = null;

if ($request->map_image) {

    $mapData = preg_replace(
        '#^data:image/\w+;base64,#i',
        '',
        $request->map_image
    );

    $mapImage = base64_decode($mapData);

    $mapFile = 'map_'.time().'.png';

    $mapDir = storage_path('app/public/maps/');

    if(!file_exists($mapDir)){
        mkdir($mapDir,0777,true);
    }

    $mapPath = $mapDir.$mapFile;

    file_put_contents($mapPath,$mapImage);
}

        /* ============================
           GENERATE PDF
        ============================ */

 $pdf = new TCPDF('P','mm',array(216,330),true,'UTF-8',false);
$pdf->SetFont('helvetica','',10); 
$pdf->SetMargins(10,10,10);
$pdf->SetAutoPageBreak(TRUE,10);
$pdf->AddPage();

/* ================= HEADER ================= */

$logo = public_path('images/fil-products-logo.png');
if(file_exists($logo)){
    $pdf->Image($logo,15,12,22);
}

$pdf->SetFont('helvetica','B',15);
$pdf->SetXY(0,15);
$pdf->Cell(216, 7, $branchData['name'], 0, 1, 'C');

$pdf->SetFont('helvetica','',10);
$pdf->Cell(0, 5, strtoupper($branchData['address']), 0, 1, 'C');
$pdf->Cell(0, 5, 'CEL. NOS.: ' . $branchData['contact'], 0, 1, 'C');
$pdf->Cell(0,5,'Email: info.cyg@filproducts.ph | Website: www.filproducts-cyg.com',0,1,'C');
$pdf->Ln(4);

/* ================= APPLICATION FORM ================= */

$pdf->SetFont('helvetica','B',12);
$pdf->Cell(0,6,'APPLICATION FORM',0,1,'L');

$pdf->SetFont('helvetica','',9);
$pdf->Cell(0,5,'Please write legibly in print all required fields below and draw location below',0,1);
$pdf->Cell(0,5,'Date Applied: '.date('m/d/Y'),0,1);

$pdf->Ln(3);

/* ================= RED SECTION HEADER FUNCTION ================= */

function sectionHeader($pdf,$title){
$pdf->SetFillColor(180,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(0,6,$title,0,1,'L',true);
$pdf->SetTextColor(0,0,0);
$pdf->Ln(1);
}

/* ================= PERSONAL INFORMATION ================= */

sectionHeader($pdf,"PERSONAL INFORMATION");

$pdf->SetFont('helvetica','',10);

$pdf->Cell(60,6,"Salutation: ".$_POST['salutation'],0,0);
$pdf->Cell(60,6,"Gender: ".$_POST['gender'],0,0);
$pdf->Cell(60,6,"Birthday: ".$_POST['birthday'],0,1);

$pdf->Cell(60,6,"Civil Status: ".$_POST['civil_status'],0,0);
$pdf->Cell(60,6,"Citizenship: ".$_POST['citizenship'],0,1);

$pdf->Cell(60,6,"First Name: ".$_POST['first_name'],0,0);
$pdf->Cell(60,6,"Mobile No: ".$_POST['mobile_no'],0,0);
$pdf->Cell(60,6,"Home Tel No: ".$_POST['home_tel'],0,1);

$pdf->Cell(60,6,"Middle Name: ".$_POST['middle_name'],0,0);
$pdf->Cell(60,6,"TIN No: ".$_POST['tin_no'],0,0);
$pdf->Cell(60,6,"GSIS/SSS No: ".$_POST['gsis_sss'],0,1);

$pdf->Cell(60,6,"Last Name: ".$_POST['last_name'],0,0);
$pdf->Cell(120,6,"Email: ".$_POST['email'],0,1);

$pdf->Ln(2);

$motherFull = trim(
    ($_POST['mother_first'] ?? '') . ' ' .
    ($_POST['mother_middle'] ?? '') . ' ' .
    ($_POST['mother_last'] ?? '')
);

$pdf->Cell(0,6,"Mother’s Full Maiden Name: ".$motherFull,0,1);

$pdf->Ln(2);

/* ================= HOME ADDRESS ================= */

sectionHeader($pdf,"COMPLETE HOME ADDRESS");

$pdf->SetFont('helvetica','',10);

$pdf->Cell(45,6,"Street:",0,0);
$pdf->Cell(135,6,$_POST['street'] ?? '',0,1);

$pdf->Cell(45,6,"Barangay:",0,0);
$pdf->Cell(135,6,$_POST['barangay'] ?? '',0,1);

$pdf->Cell(45,6,"City / Province:",0,0);
$pdf->Cell(135,6,$_POST['city'] ?? '',0,1);

$pdf->Cell(45,6,"Zip Code:",0,0);
$pdf->Cell(135,6,$_POST['zip'] ?? '',0,1);

$pdf->Ln(3);

/* ================= EMPLOYMENT ================= */

sectionHeader($pdf,"EMPLOYMENT / FINANCIAL INFORMATION");

$pdf->SetFont('helvetica','',10);

$pdf->Cell(45,6,"Employer:",0,0);
$pdf->Cell(135,6,$_POST['industry'] ?? '',0,1);

$pdf->Cell(45,6,"Street:",0,0);
$pdf->Cell(135,6,$_POST['office_street'] ?? '',0,1);

$pdf->Cell(45,6,"Barangay:",0,0);
$pdf->Cell(135,6,$_POST['office_barangay'] ?? '',0,1);

$pdf->Cell(45,6,"City / Province:",0,0);
$pdf->Cell(135,6,$_POST['office_city'] ?? '',0,1);

$pdf->Cell(45,6,"Zip Code:",0,0);
$pdf->Cell(135,6,$_POST['office_zip'] ?? '',0,1);

$pdf->Cell(45,6,"Office Tel:",0,0);
$pdf->Cell(60,6,$_POST['office_tel'] ?? '',0,0);
$pdf->Cell(30,6,"Years:",0,0);
$pdf->Cell(45,6,$_POST['years_company'] ?? '',0,1);

$pdf->Cell(45,6,"Position:",0,0);
$pdf->Cell(60,6,$_POST['position'] ?? '',0,0);
$pdf->Cell(30,6,"Income:",0,0);
$pdf->Cell(45,6,$_POST['monthly_income'] ?? '',0,1);

$pdf->Ln(3);

/* ================= AUTHORIZED CONTACT ================= */
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(0,6,"Authorized Contact Person:",0,1 );

$authName = trim(
    ($_POST['auth_first'] ?? '') . ' ' .
    ($_POST['auth_middle'] ?? '') . ' ' .
    ($_POST['auth_last'] ?? '')
);
$pdf->SetFont('helvetica','',10);
$pdf->Cell(90,6,"Name: ".$authName,0,0);
$pdf->Cell(60,6,"Relation: ".$_POST['auth_relation'],0,0);
$pdf->Cell(60,6,"Contact: ".$_POST['auth_contact'],0,1);
$pdf->Cell(90,6,"Relation: ".$_POST['auth_relation'],0,0);
$pdf->Cell(60,6,"Contact: ".$_POST['auth_contact'],0,1);

$pdf->Ln(4);

/* ================= BASIC FEES ================= */

sectionHeader($pdf,"BASIC CHARGES & FEE");

$pdf->SetFont('helvetica','',10);

$pdf->Cell(120,6,"Installation Fee",0,0);
$pdf->Cell(40,6,"= 1000.00",0,1);

$pdf->Cell(120,6,"One Month Deposit",0,0);
$pdf->Cell(40,6,"=",0,1);

$pdf->Cell(120,6,"RG06 Wire (x P20/M)",0,0);
$pdf->Cell(40,6,"=",0,1);

$pdf->Cell(120,6,"2 Way Splitter P50",0,0);
$pdf->Cell(40,6,"=",0,1);

$pdf->Cell(120,6,"3 Way Splitter P75",0,0);
$pdf->Cell(40,6,"=",0,1);

$pdf->Ln(3);

/* ================= RATE ================= */
$pdf->Ln(3);

$pdf->SetFont('helvetica','B',10);
$pdf->Cell(0,6,"Subscription Plan",0,1);

$pdf->SetFont('helvetica','',10);

$selectedPlan = $_POST['monthly_subscription'] ?? '';

$pdf->Cell(120,6,$selectedPlan,0,1);

/* ================= DRAW SKETCH NOTE AT VERY BOTTOM ================= */

$pdf->SetY(-20); // move to bottom of page

$pdf->SetFont('helvetica','B',10);
$pdf->Cell(0,6,"Draw a Sketch at the back >>>>",0,1,'R');


/* ================= PAGE 2 ================= */
$pdf->AddPage();
/* DECLARATION */
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'SUBSCRIBER\'S DECLARATIONS', 0, 1);

$pdf->SetFont('helvetica', '', 9);

$declaration = "
    1. I hereby confirm that the foregoing information is true and correct, that supporting documents attached hereto are
    genuine and authentic, and that I voluntarily submitted the said information and documents for the purpose of facilitating my
    application to the Service.

    2. I hereby further confirm that I applied for and, once my application is approved, that I have voluntarily availed of the
    plans, products and/or services chosen by me in this application form, as well as the inclusions and special features of such
    plans, products and/or services and that any enrolment I have indicated herein have been knowingly made by me.

    3. I hereby authorize FIL PRODUCTS SERVICE TELEVISION OF CALBAYOG, INC. (hereinafter you) or any person or
    entity authorized by you, to verify any information about me and/or documents available from whatever source including but
    not limited to (i) your subsidiaries, affiliates, and/or their service providers: or (ii) banks, credit card companies, and other
    lending and/or financial institution, and I hereby authorize the holder, controller and processor of such information and/or
    document, has the same is defined in Republic Act No. 10173 (otherwise known as Data Privacy Act of 2012), or any
    amendment or modification of the same, to conform, release and verify the existence, truthfulness, and/or accuracy of such
    information and/or document.
    
    4. I give you permission to use, disclose and share with your business partners, subsidiaries and affiliates (and their
    business partners) information contained in this application about me and my subscription, my network and connections, my
    service usage and payment patterns, information about the device and equipment I use to access you service, websites
    and app used in your services, information from your third party partners and advertisers, including any data or analytics
    derived therefrom, in whatever form (hereinafter Personal Information), for the following purposes: processing any
    application or request for availment of any product and/or service which they offer, improving your/ their products and
    services, credit investigation and scoring, advertising and promoting new products and services, to the end of improving my
    and/or the public's customer experience.

    5. I consent to your business partners', subsidiaries' and affiliates' (and their business partners') disclosure to you of any
    Personal Information in their possession to achieve any of the purposes stated above.

    6. I hereby likewise authorize you, your business partners, subsidiaries and affiliates, to send me SMS alerts or any
    communication, advertisement or promotional material pertaining to any new or current product and/or service offered by
    you, your business partners, subsidiaries and affiliates

    7. I acknowledge and agree to the Holding Period for the relevant service availed of. If I choose to downgrade my plan,
    transfer and rights or obligations of my subscription or pre-terminate or cancel my subscription within the Holding Period
    then I agree to pay the relevant fees, charges and penalties imposed by you.

    8. I am aware of the fees, rates and charges relevant of the service availed of and I agree to pay the same within the due
    dates. I understand that I will be subject to, and hereby agree and undertake, interest and penalties for late payment or non-payment stated in the terms and condition.

    9. I hereby confirm that I have read and understood the Terms and Conditions of our Subscription Agreement and that I
    shall strictly comply and abide by these terms and conditions and any future amendments thereto.

    10. I agree that this Subscription Agreement shall govern our relationship for the service currently availed of and the service
    I will avail of in the future.

    11. I agree to pay my application's cancellation fee equivalent to 20% of application charges (Deposit, Installation fee and
    equipments).
";

$pdf->MultiCell(0, 5, $declaration);

$pdf->Ln(10);

/* CENTER POSITION */
$pageWidth = 216; // Long bond width in mm
$centerX = ($pageWidth / 2) - 30; // Adjust for signature width

/* ================= INSERT SAVED SIGNATURE ================= */

if (!empty($sigPath) && file_exists($sigPath)) {

    $pageWidth = 216;
    $centerX = ($pageWidth / 2) - 30;

    $pdf->Image($sigPath, $centerX, $pdf->GetY(), 60, 0, 'PNG');
}

$pdf->Ln(20);

/* CENTERED TEXT */
$pdf->SetFont('helvetica', '', 10);

/* Underlined Name */
$pdf->SetFont('', 'U');
$pdf->Cell(0, 6, $fullName, 0, 1, 'C');

$pdf->SetFont('', ' ');
$pdf->Cell(0, 6, 'Applicant', 0, 1, 'C');
$pdf->Cell(0, 6, '(Signature over printed name)', 0, 1, 'C');

/* =========================================================
   INTERNAL USE / ASSIGNMENT SECTION (COMPACT)
========================================================= */

$pdf->Ln(3); // reduced spacing

$pdf->SetFont('helvetica', '', 9); // smaller font

$labelWidth = 32;   // reduced
$fieldWidth = 50;   // reduced
$height = 6;        // reduced box height

/* Row 1 */
$pdf->Cell($labelWidth, $height, 'Application:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 0);

$pdf->Cell(10); // reduced gap

$pdf->Cell($labelWidth, $height, 'Referred by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 1);

/* Row 2 */
$pdf->Ln(2);

$pdf->Cell($labelWidth, $height, 'Checked by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 0);

$pdf->Cell(10);

$pdf->Cell($labelWidth, $height, 'Approved by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 1);

/* Equipment Section */
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(0, 5, 'STB ASSIGNMENT SECTION', 0, 1);

$pdf->SetFont('helvetica', '', 9);

/* Smartcard */
$pdf->Cell($labelWidth, $height, 'Smartcard No.:', 0, 0);
$pdf->Cell(110, $height, '', 1, 1); // fixed full width box

$pdf->Ln(2);

/* Modem */
$pdf->Cell($labelWidth, $height, 'Modem Assignment:', 0, 0);
$pdf->Cell(110, $height, '', 1, 1);

$pdf->Ln(3);

$pdf->Cell($labelWidth, $height, 'Modem Assignment:', 0, 0);
$pdf->Cell($fieldWidth + 40, $height, '', 1, 1);

/* ================= PAGE 3 : HOUSE LOCATION MAP ================= */

$pdf->AddPage();

$pdf->SetFont('helvetica','B',12);
$pdf->Cell(0,10,'HOUSE LOCATION MAP',0,1,'C');

$pdf->Ln(5);

if (!empty($mapPath) && file_exists($mapPath)) {

    $pageWidth = 216;
    $mapWidth = 180;

    $centerX = ($pageWidth - $mapWidth) / 2;

    $pdf->Image($mapPath,$centerX,$pdf->GetY(),$mapWidth,0,'PNG');

}

$pdf->Ln(5);

$pdf->SetFont('helvetica','',10);

$pdf->Cell(0,6,'Latitude: '.$request->latitude,0,1,'C');
$pdf->Cell(0,6,'Longitude: '.$request->longitude,0,1,'C');

$pdf->Ln(5);

$googleMapLink = "https://www.google.com/maps?q=".$request->latitude.",".$request->longitude;

$pdf->SetTextColor(0,0,255);
$pdf->Cell(0,6,'Open in Google Maps: '.$googleMapLink,0,1,'C');
$pdf->SetTextColor(0,0,0);
/* ================= GENERATE PDF CONTENT ================= */

$cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $fullName);
$cleanName = str_replace(' ', '_', trim($cleanName));

$fileName = $cleanName . '_Residential_Application.pdf';

$pdfContent = $pdf->Output('', 'S');

/* ============================
   SAVE PDF FILE
============================ */

$pdfDir = storage_path('app/public/applications/');

if(!file_exists($pdfDir)){
    mkdir($pdfDir,0777,true);
}

$pdfPath = $pdfDir.$fileName;

file_put_contents($pdfPath,$pdfContent);


/* =========================
   BRANCH EMAIL MAP
========================== */

$branchEmails = [
    'calbayog'   => 'info.cyg@filproducts.ph',
    'allen'      => 'filproductsallen2022@gmail.com',
    'mondragon'  => 'mondragon.csr013026@gmail.com',
    'sanjorge'   => 'filproducts.sanjorge@gmail.com',
    'catarman'   => 'csrfilproductscatarman@gmail.com',
    'catbalogan' => 'filproducts.catbalogancsr@gmail.com',
    'tacloban'   => 'filproductsleyte@gmail.com',
    'palo'   => 'filproductsleyte@gmail.com',
];

/* ✅ USE REQUEST (NOT $_POST) */
$selectedBranch = strtolower($request->branch ?? '');
$branch = $request->branch ?? 'N/A';
$customerEmail = $request->email ?? null;
$plan = $request->monthly_subscription ?? '';

/* =========================
   SEND EMAIL
========================== */

$mail = new PHPMailer(true);

$mail->isSMTP();

/* SMTP */
$mail->Host = 'mail.filproducts-cyg.com';
$mail->SMTPAuth = true;
$mail->Username = 'noreply@filproducts-cyg.com';
$mail->Password = '8kKAahOE*.E,7uJZ';

$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;

/* SSL FIX */
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];

/* FROM */
$mail->setFrom('noreply@filproducts-cyg.com', 'Fil Products Samar');

/* HEADERS */
$mail->Sender = 'noreply@filproducts-cyg.com';
$mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());

/* ============================
   RECIPIENTS
============================ */

/* ✅ SEND TO CUSTOMER */
if ($customerEmail) {
    $mail->addAddress($customerEmail);
    $mail->addReplyTo($customerEmail, $fullName);
}

/* ✅ SEND TO SELECTED BRANCH */
if (isset($branchEmails[$selectedBranch])) {
    $mail->addAddress($branchEmails['butuan']);
} else {
    $mail->addAddress('info.cyg@filproducts.ph');
}

/* ✅ SEND COPY TO ADMIN */
$mail->addCC('info.cyg@filproducts.ph');

/* ============================
   CONTENT
============================ */

$mail->isHTML(true);
$mail->CharSet = 'UTF-8';

$mail->Subject = "Residential Application - " . $fullName;

$mail->Body = "
<h3 style='margin-bottom:10px;'>Residential Application</h3>

<p><b>Applicant Name:</b> {$fullName}</p>
<p><b>Email:</b> {$customerEmail}</p>
<p><b>Branch:</b> {$branch}</p>
<p><b>Plan Selected:</b> {$plan}</p>

<hr>

<p style='font-size:12px;color:#555;'>
This application was submitted via Fil Products System.
</p>
";

/* ============================
   ATTACHMENTS
============================ */

$mail->addStringAttachment($pdfContent, $fileName);

/* ⚠️ LIMIT EXTRA FILES */
$maxAttachments = 2;
$count = 0;

foreach ($attachments as $file) {
    if ($file && file_exists($file)) {
        $mail->addAttachment($file);
        $count++;

        if ($count >= $maxAttachments) break;
    }
}

/* ============================
   SEND
============================ */

$mail->send();

return redirect()
    ->route('residential.inquiry')
    ->with('success',
        '✅ Your Residential Application has been successfully submitted.

📧 A copy of your application has been sent to your email for your reference.

Our team will review your application and contact you shortly to schedule your installation.

Thank you for choosing Fil Products Samar.
'
    );

} catch (\Exception $e) {

    return redirect()
        ->route('residential.inquiry')
        ->with('error', 'Submission failed: '.$e->getMessage());

}

}

// RESIDENTIAL UPGRADE //
public function submitResidentialUpgrade(Request $request)
{

    if (!$request->declaration_agree) {
        return back()->with('error','You must agree to the Subscriber Declaration');
    }

        $branch = 'butuan';

        $branches = [
        'butuan' => [
            'name' => 'FIL PRODUCTS SERVICE TELEVISION, INC.',
            'address' => 'N&D Bldg., Alviola Village Baan KM., Butuan City',
            'contact' => '0917-320-5871 / 0938-320-5871'
        ],
    ];

    $branchData = $branches['butuan'];

    try {

/* ============================
   FULL NAME
============================ */

    $fullName = trim(
    $request->first_name.' '.
    $request->middle_name.' '.
    $request->last_name
);

/* ============================
   GENERATE FILE NAME
============================ */

    $cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $fullName);
    $cleanName = str_replace(' ', '_', trim($cleanName));

    $fileName = $cleanName.'_Residential_Upgrade.pdf';
    $filePath = 'applications/'.$fileName;

        /* ============================
           SAVE ATTACHMENTS
        ============================ */

        $attachments = [];
        $files = ['valid_id','proof_billing','other_attachment'];

        foreach($files as $file){

            if($request->hasFile($file)){

                $path = $request->file($file)->store('attachments','public');

                $attachments[] = storage_path('app/public/'.$path);
            }

        }



        /* ============================
           SAVE SIGNATURE
        ============================ */

        $sigPath = null;

        if ($request->digital_signature) {

            $signatureData = preg_replace(
                '#^data:image/\w+;base64,#i',
                '',
                $request->digital_signature
            );

            $image = base64_decode($signatureData);

        $fileName = 'signature_'.time().'.png';

        $sigDir = storage_path('app/public/signatures/');

        if(!file_exists($sigDir)){
        mkdir($sigDir,0777,true);
    }

        $sigPath = $sigDir.$fileName;

        file_put_contents($sigPath,$image);
        }



        /* ============================
           GENERATE PDF
        ============================ */

 $pdf = new TCPDF('P','mm',array(216,330),true,'UTF-8',false);
$pdf->SetFont('helvetica','',10); 
$pdf->SetMargins(10,10,10);
$pdf->SetAutoPageBreak(TRUE,10);
$pdf->AddPage();

/* ================= HEADER ================= */

$logo = public_path('images/fil-products-logo.png');
if(file_exists($logo)){
    $pdf->Image($logo,15,12,22);
}

$pdf->SetFont('helvetica','B',15);
$pdf->SetXY(0,15);
$pdf->Cell(216, 7, $branchData['name'], 0, 1, 'C');

$pdf->SetFont('helvetica','',10);
$pdf->Cell(0, 5, strtoupper($branchData['address']), 0, 1, 'C');
$pdf->Cell(0, 5, 'CEL. NOS.: ' . $branchData['contact'], 0, 1, 'C');
$pdf->Cell(0,5,'Email: info.cyg@filproducts.ph | Website: www.filproducts-cyg.com',0,1,'C');
$pdf->Ln(4);

/* ================= APPLICATION FORM ================= */

$pdf->SetFont('helvetica','B',12);
$pdf->Cell(0,6,'UPGRADE FORM',0,1,'L');

$pdf->SetFont('helvetica','',9);
$pdf->Cell(0,5,'Please write legibly in print all required fields below and draw location below',0,1);
$pdf->Cell(0,5,'Date Applied: '.date('m/d/Y'),0,1);

$pdf->Ln(3);

/* ================= RED SECTION HEADER FUNCTION ================= */

function sectionHeader($pdf,$title){
$pdf->SetFillColor(180,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(0,6,$title,0,1,'L',true);
$pdf->SetTextColor(0,0,0);
$pdf->Ln(1);
}

/* ================= PERSONAL INFORMATION ================= */

sectionHeader($pdf,"PERSONAL INFORMATION");

$pdf->SetFont('helvetica','',10);

$pdf->Cell(60,6,"Salutation: ".$_POST['salutation'],0,0);
$pdf->Cell(60,6,"Gender: ".$_POST['gender'],0,0);
$pdf->Cell(60,6,"Birthday: ".$_POST['birthday'],0,1);

$pdf->Cell(60,6,"Civil Status: ".$_POST['civil_status'],0,0);
$pdf->Cell(60,6,"Citizenship: ".$_POST['citizenship'],0,1);

$pdf->Cell(60,6,"First Name: ".$_POST['first_name'],0,0);
$pdf->Cell(60,6,"Mobile No: ".$_POST['mobile_no'],0,0);
$pdf->Cell(60,6,"Home Tel No: ".$_POST['home_tel'],0,1);

$pdf->Cell(60,6,"Middle Name: ".$_POST['middle_name'],0,0);
$pdf->Cell(60,6,"TIN No: ".$_POST['tin_no'],0,0);
$pdf->Cell(60,6,"GSIS/SSS No: ".$_POST['gsis_sss'],0,1);

$pdf->Cell(60,6,"Last Name: ".$_POST['last_name'],0,0);
$pdf->Cell(120,6,"Email: ".$_POST['email'],0,1);

$pdf->Ln(2);

/* ================= HOME ADDRESS ================= */

sectionHeader($pdf,"COMPLETE HOME ADDRESS");

$pdf->SetFont('helvetica','',10);

$pdf->Cell(45,6,"Street:",0,0);
$pdf->Cell(135,6,$_POST['street'] ?? '',0,1);

$pdf->Cell(45,6,"Barangay:",0,0);
$pdf->Cell(135,6,$_POST['barangay'] ?? '',0,1);

$pdf->Cell(45,6,"City / Province:",0,0);
$pdf->Cell(135,6,$_POST['city'] ?? '',0,1);

$pdf->Cell(45,6,"Zip Code:",0,0);
$pdf->Cell(135,6,$_POST['zip'] ?? '',0,1);

$pdf->Ln(3);


/* ================= BASIC FEES ================= */

sectionHeader($pdf,"BASIC CHARGES & FEE");

$pdf->SetFont('helvetica','',10);

$pdf->Cell(120,6,"Installation Fee",0,0);
$pdf->Cell(40,6,"= 1000.00",0,1);

$pdf->Cell(120,6,"One Month Deposit",0,0);
$pdf->Cell(40,6,"=",0,1);

$pdf->Cell(120,6,"RG06 Wire (x P20/M)",0,0);
$pdf->Cell(40,6,"=",0,1);

$pdf->Cell(120,6,"2 Way Splitter P50",0,0);
$pdf->Cell(40,6,"=",0,1);

$pdf->Cell(120,6,"3 Way Splitter P75",0,0);
$pdf->Cell(40,6,"=",0,1);

$pdf->Ln(3);

/* ================= RATE ================= */
$pdf->Ln(3);

$pdf->SetFont('helvetica','B',10);
$pdf->Cell(0,6,"Subscription Plan",0,1);

$pdf->SetFont('helvetica','',10);

$selectedPlan = $_POST['monthly_subscription'] ?? '';

$pdf->Cell(120,6,$selectedPlan,0,1);


/* ================= PAGE 2 ================= */
$pdf->AddPage();
/* DECLARATION */
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'SUBSCRIBER\'S DECLARATIONS', 0, 1);

$pdf->SetFont('helvetica', '', 9);

$declaration = "
    1. I hereby confirm that the foregoing information is true and correct, that supporting documents attached hereto are
    genuine and authentic, and that I voluntarily submitted the said information and documents for the purpose of facilitating my
    application to the Service.

    2. I hereby further confirm that I applied for and, once my application is approved, that I have voluntarily availed of the
    plans, products and/or services chosen by me in this application form, as well as the inclusions and special features of such
    plans, products and/or services and that any enrolment I have indicated herein have been knowingly made by me.

    3. I hereby authorize FIL PRODUCTS SERVICE TELEVISION OF CALBAYOG, INC. (hereinafter you) or any person or
    entity authorized by you, to verify any information about me and/or documents available from whatever source including but
    not limited to (i) your subsidiaries, affiliates, and/or their service providers: or (ii) banks, credit card companies, and other
    lending and/or financial institution, and I hereby authorize the holder, controller and processor of such information and/or
    document, has the same is defined in Republic Act No. 10173 (otherwise known as Data Privacy Act of 2012), or any
    amendment or modification of the same, to conform, release and verify the existence, truthfulness, and/or accuracy of such
    information and/or document.
    
    4. I give you permission to use, disclose and share with your business partners, subsidiaries and affiliates (and their
    business partners) information contained in this application about me and my subscription, my network and connections, my
    service usage and payment patterns, information about the device and equipment I use to access you service, websites
    and app used in your services, information from your third party partners and advertisers, including any data or analytics
    derived therefrom, in whatever form (hereinafter Personal Information), for the following purposes: processing any
    application or request for availment of any product and/or service which they offer, improving your/ their products and
    services, credit investigation and scoring, advertising and promoting new products and services, to the end of improving my
    and/or the public's customer experience.

    5. I consent to your business partners', subsidiaries' and affiliates' (and their business partners') disclosure to you of any
    Personal Information in their possession to achieve any of the purposes stated above.

    6. I hereby likewise authorize you, your business partners, subsidiaries and affiliates, to send me SMS alerts or any
    communication, advertisement or promotional material pertaining to any new or current product and/or service offered by
    you, your business partners, subsidiaries and affiliates

    7. I acknowledge and agree to the Holding Period for the relevant service availed of. If I choose to downgrade my plan,
    transfer and rights or obligations of my subscription or pre-terminate or cancel my subscription within the Holding Period
    then I agree to pay the relevant fees, charges and penalties imposed by you.

    8. I am aware of the fees, rates and charges relevant of the service availed of and I agree to pay the same within the due
    dates. I understand that I will be subject to, and hereby agree and undertake, interest and penalties for late payment or non-payment stated in the terms and condition.

    9. I hereby confirm that I have read and understood the Terms and Conditions of our Subscription Agreement and that I
    shall strictly comply and abide by these terms and conditions and any future amendments thereto.

    10. I agree that this Subscription Agreement shall govern our relationship for the service currently availed of and the service
    I will avail of in the future.

    11. I agree to pay my application's cancellation fee equivalent to 20% of application charges (Deposit, Installation fee and
    equipments).
";

$pdf->MultiCell(0, 5, $declaration);

$pdf->Ln(10);

/* CENTER POSITION */
$pageWidth = 216; // Long bond width in mm
$centerX = ($pageWidth / 2) - 30; // Adjust for signature width

/* ================= INSERT SAVED SIGNATURE ================= */

if (!empty($sigPath) && file_exists($sigPath)) {

    $pageWidth = 216;
    $centerX = ($pageWidth / 2) - 30;

    $pdf->Image($sigPath, $centerX, $pdf->GetY(), 60, 0, 'PNG');
}

$pdf->Ln(20);

/* CENTERED TEXT */
$pdf->SetFont('helvetica', '', 10);

/* Underlined Name */
$pdf->SetFont('', 'U');
$pdf->Cell(0, 6, $fullName, 0, 1, 'C');

$pdf->SetFont('', ' ');
$pdf->Cell(0, 6, 'Applicant', 0, 1, 'C');
$pdf->Cell(0, 6, '(Signature over printed name)', 0, 1, 'C');

/* =========================================================
   INTERNAL USE / ASSIGNMENT SECTION (COMPACT)
========================================================= */

$pdf->Ln(3); // reduced spacing

$pdf->SetFont('helvetica', '', 9); // smaller font

$labelWidth = 32;   // reduced
$fieldWidth = 50;   // reduced
$height = 6;        // reduced box height

/* Row 1 */
$pdf->Cell($labelWidth, $height, 'Application:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 0);

$pdf->Cell(10); // reduced gap

$pdf->Cell($labelWidth, $height, 'Referred by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 1);

/* Row 2 */
$pdf->Ln(2);

$pdf->Cell($labelWidth, $height, 'Checked by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 0);

$pdf->Cell(10);

$pdf->Cell($labelWidth, $height, 'Approved by:', 0, 0);
$pdf->Cell($fieldWidth, $height, '', 1, 1);

/* Equipment Section */
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(0, 5, 'STB ASSIGNMENT SECTION', 0, 1);

$pdf->SetFont('helvetica', '', 9);

/* Smartcard */
$pdf->Cell($labelWidth, $height, 'Smartcard No.:', 0, 0);
$pdf->Cell(110, $height, '', 1, 1); // fixed full width box

$pdf->Ln(2);

/* Modem */
$pdf->Cell($labelWidth, $height, 'Modem Assignment:', 0, 0);
$pdf->Cell(110, $height, '', 1, 1);

$pdf->Ln(3);

$pdf->Cell($labelWidth, $height, 'Modem Assignment:', 0, 0);
$pdf->Cell($fieldWidth + 40, $height, '', 1, 1);
/* ================= GENERATE PDF CONTENT ================= */

$cleanName = preg_replace('/[^A-Za-z0-9\- ]/', '', $fullName);
$cleanName = str_replace(' ', '_', trim($cleanName));

$fileName = $cleanName . '_Residential_Upgrade.pdf';

$pdfContent = $pdf->Output('', 'S');

/* ============================
   SAVE PDF FILE
============================ */

$pdfDir = storage_path('app/public/applications/');

if(!file_exists($pdfDir)){
    mkdir($pdfDir,0777,true);
}

$pdfPath = $pdfDir.$fileName;

file_put_contents($pdfPath,$pdfContent);


/* =========================
   BRANCH EMAIL MAP
========================== */

$branchEmails = [
    'calbayog'   => 'info.cyg@filproducts.ph',
    'allen'      => 'filproductsallen2022@gmail.com',
    'mondragon'  => 'mondragon.csr013026@gmail.com',
    'sanjorge'   => 'filproducts.sanjorge@gmail.com',
    'catarman'   => 'csrfilproductscatarman@gmail.com',
    'catbalogan' => 'filproducts.catbalogancsr@gmail.com',
    'tacloban'   => 'filproductsleyte@gmail.com',
    'palo'   => 'filproductsleyte@gmail.com',
];

/* ✅ USE REQUEST */
$selectedBranch = strtolower($request->branch ?? '');
$branch = $request->branch ?? 'N/A';
$customerEmail = $request->email ?? null;
$plan = $request->monthly_subscription ?? '';

/* =========================
   SEND EMAIL
========================== */

$mail = new PHPMailer(true);

$mail->isSMTP();

/* SMTP */
$mail->Host = 'mail.filproducts-cyg.com';
$mail->SMTPAuth = true;
$mail->Username = 'noreply@filproducts-cyg.com';
$mail->Password = '8kKAahOE*.E,7uJZ';

$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;

/* SSL FIX */
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];

/* FROM */
$mail->setFrom('noreply@filproducts-cyg.com', 'Fil Products Samar');

/* HEADERS */
$mail->Sender = 'noreply@filproducts-cyg.com';
$mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());

/* ============================
   RECIPIENTS
============================ */

/* ✅ SEND TO CUSTOMER */
if ($customerEmail) {
    $mail->addAddress($customerEmail);
    $mail->addReplyTo($customerEmail, $fullName);
}

/* ✅ SEND TO SELECTED BRANCH */
if (isset($branchEmails[$selectedBranch])) {
    $mail->addAddress($branchEmails['butuan']);
} else {
    $mail->addAddress('info.cyg@filproducts.ph');
}

/* ✅ SEND COPY TO ADMIN */
$mail->addCC('info.cyg@filproducts.ph');

/* ============================
   CONTENT
============================ */

$mail->isHTML(true);
$mail->CharSet = 'UTF-8';

$mail->Subject = "Residential Upgrade - " . $fullName;

$mail->Body = "
<h3 style='margin-bottom:10px;'>Residential Upgrade Application</h3>

<p><b>Applicant Name:</b> {$fullName}</p>
<p><b>Email:</b> {$customerEmail}</p>
<p><b>Branch:</b> {$branch}</p>
<p><b>Plan Selected:</b> {$plan}</p>

<hr>

<p style='font-size:12px;color:#555;'>
This application was submitted via Fil Products System.
</p>
";

/* ============================
   ATTACHMENT
============================ */

$mail->addStringAttachment($pdfContent, $fileName);

/* ============================
   SEND
============================ */

$mail->send();

return redirect()
    ->route('residential.upgrade')
    ->with('success',
        '✅ Your Residential Upgrade request has been successfully submitted.

📧 A copy of your request has been sent to your email for your reference.

Our team will review your upgrade request and contact you shortly to schedule your installation.

Thank you for choosing Fil Products Samar.
'
    );

} catch (\Exception $e) {

    return redirect()
        ->route('residential.upgrade')
        ->with('error', 'Submission failed: '.$e->getMessage());

}

}

public function track(Request $request)
{

$result = null;

return view('pages.track',compact('result'));

}

public function branch()
{
    return view('pages.branch');
}
}
