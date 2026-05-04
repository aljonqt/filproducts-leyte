<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use TCPDF;

class ResidentialUpgradeController extends Controller
{

        public function upgrade()
    {
        return view('inquiry.residential_upgrade');
    }

    public function submit(Request $request)
    {

    if (!$request->declaration_agree) {
        return back()->with('error','You must agree to the Subscriber Declaration');
    }

        $branch = 'leyte';

        $branches = [
        'leyte' => [
            'name' => 'FIL PRODUCTS SERVICE TELEVISION, INC.',
            'address' => 'City Center Park Real St., Brgy Aslum, Tacloban City, Leyte',
            'contact' => '0995-415-1821'
        ],
    ];

    $branchData = $branches['leyte'];

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
$pdf->Cell(0,5,'Email: info.leyte@filproducts.ph | Website: www.leyte.filproducts-cyg.com',0,1,'C');
$pdf->Ln(4);

/* ================= APPLICATION FORM ================= */

$pdf->SetFont('helvetica','B',12);
$pdf->Cell(0,6,'UPGRADE FORM',0,1,'L');

$pdf->SetFont('helvetica','',9);
$pdf->Cell(0,5,'Please write legibly in print all required fields below and draw location below',0,1);
$pdf->Cell(0,5,'Date Applied: '.date('m/d/Y'),0,1);

$pdf->Ln(3);

/* ================= RED SECTION HEADER FUNCTION ================= */

function upgradeSectionHeader($pdf,$title){
$pdf->SetFillColor(180,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(0,6,$title,0,1,'L',true);
$pdf->SetTextColor(0,0,0);
$pdf->Ln(1);
}

/* ================= PERSONAL INFORMATION ================= */

upgradeSectionHeader($pdf,"PERSONAL INFORMATION");

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

upgradeSectionHeader($pdf,"COMPLETE HOME ADDRESS");

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

upgradeSectionHeader($pdf,"BASIC CHARGES & FEE");

$pdf->SetFont('helvetica','',10);

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
   FIXED BRANCH (LEYTE ONLY)
========================== */
$branch = 'Leyte';
$branchRecipient = 'info.leyte@filproducts.ph';

/* =========================
   DATA
========================== */
$customerEmail = $request->email ?? null;
$plan = $request->monthly_subscription ?? '';

$fullNameSafe = htmlspecialchars($fullName ?? '');
$emailSafe = htmlspecialchars($customerEmail ?? '');
$planSafe = htmlspecialchars($plan ?? '');

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
$mail->setFrom('noreply@filproducts-cyg.com', 'Fil Products Leyte');

/* =========================
   RECIPIENTS (ADMIN ONLY)
========================== */

// ✅ Internal recipients (same formatted email)
$mail->addAddress('info.leyte@filproducts.ph');

// Optional: reply goes to customer
if (!empty($customerEmail)) {
    $mail->addReplyTo($customerEmail, $fullName);
}

/* HEADERS */
$mail->Sender = 'noreply@filproducts-cyg.com';
$mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());


/* =========================
   CONTENT (TABLE FORMAT)
========================== */

$mail->isHTML(true);
$mail->CharSet = 'UTF-8';

$mail->Subject = "Residential Upgrade - {$fullNameSafe}";

$mail->Body = "
<div style='font-family: Arial, sans-serif; font-size:14px; color:#333;'>
    <h2 style='color:#5cb85c;'>Residential Upgrade Request</h2>
    <hr>

    <table style='width:100%; border-collapse: collapse;'>
        <tr>
            <td><strong>Applicant:</strong></td>
            <td>{$fullNameSafe}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td>{$emailSafe}</td>
        </tr>
        <tr>
            <td><strong>Branch:</strong></td>
            <td>{$branch}</td>
        </tr>
        <tr>
            <td><strong>Plan:</strong></td>
            <td>{$planSafe}</td>
        </tr>
    </table>

    <br>
    <p style='font-size:12px;color:#555;'>
        This upgrade request was submitted via Fil Products System.
    </p>
</div>
";

/* =========================
   ATTACHMENT
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

$mailCustomer->isSMTP();
$mailCustomer->Host = 'mail.filproducts-cyg.com';
$mailCustomer->SMTPAuth = true;
$mailCustomer->Username = 'noreply@filproducts-cyg.com';
$mailCustomer->Password = '8kKAahOE*.E,7uJZ';
$mailCustomer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mailCustomer->Port = 465;

$mailCustomer->setFrom('noreply@filproducts-cyg.com', 'Fil Products Leyte');
$mailCustomer->addAddress($customerEmail);

$mailCustomer->isHTML(true);
$mailCustomer->Subject = "Residential Upgrade Request Received";

$mailCustomer->Body = "
<h3>Upgrade Request Received</h3>
<p>Thank you {$fullNameSafe},</p>

<p>Your residential upgrade request has been successfully submitted.</p>
<p>Our Leyte team will review your request and contact you shortly.</p>

<br>
<p>Fil Products Leyte</p>
";

$mailCustomer->send();

/* =========================
   RESPONSE
========================== */

return redirect()
    ->route('residential.upgrade')
    ->with('success',
        '✅ Your Residential Upgrade request has been successfully submitted.
        📧 A copy has been sent to your email.
        Our Leyte team will contact you shortly.'
    );

} catch (\Exception $e) {

    return redirect()
        ->route('residential.upgrade')
        ->with('error', 'Submission failed: '.$e->getMessage());

}

}

}