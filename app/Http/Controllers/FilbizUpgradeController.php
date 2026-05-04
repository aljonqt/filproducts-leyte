<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use TCPDF;

class FilbizUpgradeController extends Controller
{
    
    public function upgrade()
    {
        return view('inquiry.filbiz_upgrade');
    }

    private function configureSMTP($mail)
    {
        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = env('MAIL_PORT', 465);
    } 

public function submit(Request $request)
{

    if(!$request->declaration_agree){
        return back()->withErrors('You must agree to the declaration.');
    }

    /* ================= BRANCH LOGIC ================= */
    $branch = 'leyte';

    $branches = [
            'leyte' => [
                'name' => 'FIL PRODUCTS SERVICE TELEVISION, INC.',
                'address' => 'City Center Park Real St., Brgy Aslum, Tacloban City, Leyte',
                'contact' => '0917-320-5871 / 0938-320-5871'
            ],
        ];

    $branchData = $branches['leyte'];

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
$pdf->Cell(0,5,'Email: info.leyte@filproducts.ph | Website: www.leyte.filproducts-cyg.com',0,1,'C');
$pdf->Ln(15);


/* ================= TITLE ================= */

$pdf->SetFont('helvetica','B',14);
$pdf->SetTextColor(180,0,0);
$pdf->Cell(0,8,'FILBIZ UPGRADE FORM',0,1);

/* 🔥 RESET COLOR BACK TO BLACK */
$pdf->SetTextColor(0,0,0);

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

            3. I hereby authorize FIL PRODUCTS SERVICE TELEVISION OF LEYTE, INC. (hereinafter you) or any person or
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
            dates. I understand that I will be subject to, and hereby agree and undertake, interest and penalties for late payment or 
            non-payment stated in the terms and condition.

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
   FIXED BRANCH (LEYTE ONLY)
========================== */
$branch = 'Leyte';
$branchRecipient = 'info.leyte@filproducts.ph';

/* =========================
   SANITIZE INPUT
========================== */
$companyName = htmlspecialchars($companyName ?? '');
$firstName   = htmlspecialchars($firstName ?? '');
$lastName    = htmlspecialchars($lastName ?? '');
$email       = htmlspecialchars($email ?? '');
$subscription= htmlspecialchars($subscription ?? '');

/* =========================
   SEND EMAIL (MAIN)
========================== */
$mail = new PHPMailer(true);

/* ✅ USE HELPER */
$this->configureSMTP($mail);

/* SSL FIX */
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];

/* FROM */
$mail->setFrom(env('MAIL_USERNAME'), 'Fil Products Leyte');

/* HEADERS */
$mail->Sender = env('MAIL_USERNAME');
$mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());

/* =========================
   RECIPIENTS (ADMIN ONLY)
========================== */

// ✅ Send same formatted email to internal team
$mail->addAddress('info.leyte@filproducts.ph');

// Optional: reply goes to customer
if (!empty($email)) {
    $mail->addReplyTo($email, $companyName);
}

/* =========================
   RECIPIENTS
========================== */

/* CUSTOMER */
if (!empty($email)) {
    $mail->addAddress($email);
    $mail->addReplyTo($email, $companyName);
}



/* =========================
   CONTENT (MATCH INQUIRY)
========================== */
$mail->isHTML(true);
$mail->CharSet = 'UTF-8';

$mail->Subject = "Filbiz Upgrade - {$companyName}";

$mail->Body = "
<div style='font-family: Arial, sans-serif; font-size:14px; color:#333;'>
    <h2 style='color:#5cb85c;'>Filbiz Upgrade Request</h2>
    <hr>

    <table style='width:100%; border-collapse: collapse;'>
        <tr>
            <td><strong>Company:</strong></td>
            <td>{$companyName}</td>
        </tr>
        <tr>
            <td><strong>Applicant:</strong></td>
            <td>{$firstName} {$lastName}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td>{$email}</td>
        </tr>
        <tr>
            <td><strong>Branch:</strong></td>
            <td>{$branch}</td>
        </tr>
        <tr>
            <td><strong>Plan:</strong></td>
            <td>{$subscription}</td>
        </tr>
    </table>

    <br>
    <p style='font-size:12px;color:#555;'>
        This upgrade request was submitted via Fil Products System.
    </p>
</div>
";

/* =========================
   ATTACHMENTS
========================== */
if (!empty($pdfContent) && !empty($fileName)) {
    $mail->addStringAttachment($pdfContent, $fileName);
}

/* =========================
   SEND MAIN EMAIL
========================== */
$mail->send();

/* =========================
   CUSTOMER CONFIRMATION
========================== */
$mailCustomer = new PHPMailer(true);
$this->configureSMTP($mailCustomer);

$mailCustomer->setFrom(env('MAIL_USERNAME'), 'Fil Products Leyte');
$mailCustomer->addAddress($email);

$mailCustomer->isHTML(true);
$mailCustomer->Subject = "Filbiz Upgrade Request Received";

$mailCustomer->Body = "
<h3>Upgrade Request Received</h3>
<p>Thank you {$companyName},</p>

<p>Your Filbiz upgrade request has been successfully submitted.</p>
<p>Our Leyte team will review your request and contact you shortly.</p>

<br>
<p>Fil Products Leyte</p>
";

$mailCustomer->send();

/* =========================
   RESPONSE
========================== */
return redirect()
    ->route('filbiz.upgrade')
    ->with('success', '
    ✅ Your Filbiz Upgrade request has been successfully submitted.<br>
    📧 A copy has been sent to your email.<br>
    Our Leyte team will contact you shortly.
    ');
}
}