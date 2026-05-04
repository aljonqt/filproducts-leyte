<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use TCPDF;

class ResidentialInquiryController extends Controller
{
        public function inquiry()
    {
        return view('inquiry.residential_inquiry');
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
$pdf->Cell(0,5,'Email: info.leyte@filproducts.ph | Website: www.leyte.filproducts-cyg.com',0,1,'C');
$pdf->Ln(4);

/* ================= APPLICATION FORM ================= */

$pdf->SetFont('helvetica','B',12);
$pdf->Cell(0,6,'APPLICATION FORM',0,1,'L');

$pdf->SetFont('helvetica','',9);
$pdf->Cell(0,5,'Please write legibly in print all required fields below and draw location below',0,1);
$pdf->Cell(0,5,'Date Applied: '.date('m/d/Y'),0,1);

$pdf->Ln(3);

/* ================= RED SECTION HEADER FUNCTION ================= */

function residentialSectionHeader($pdf,$title){
                $pdf->SetFillColor(180,0,0);
                $pdf->SetTextColor(255,255,255);
                $pdf->SetFont('helvetica','B',10);
                $pdf->Cell(0,6,$title,0,1,'L',true);
                $pdf->SetTextColor(0,0,0);
                $pdf->Ln(1);
            }

/* ================= PERSONAL INFORMATION ================= */

residentialSectionHeader($pdf,"PERSONAL INFORMATION");

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

residentialSectionHeader($pdf,"COMPLETE HOME ADDRESS");

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

residentialSectionHeader($pdf,"EMPLOYMENT / FINANCIAL INFORMATION");

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

residentialSectionHeader($pdf,"BASIC CHARGES & FEE");

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
$pageWidth = 216;
$centerX = ($pageWidth / 2) - 30;

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


 /* ================= PAGE 4 : CONTRACT PAGE 1 ================= */
            $pdf->AddPage();

            $pdf->SetFont('helvetica','B',12);
            $pdf->Cell(0,6,'CONTRACT AGREEMENT',0,1,'C');

            $pdf->Ln(3);

            $pdf->SetFont('helvetica','',8);

            /* DATE */
            $day   = date('j');
            $month = date('F');
            $year  = date('Y');

            /* FULL ADDRESS */
            $fullAddress = trim(
                ($request->street ?? '') . ', ' .
                ($request->barangay ?? '') . ', ' .
                ($request->city ?? '')
            );

            /* BRANCH FORMAT */
            $branchText = 'Tacloban City';

            $branchText = $branchMap[$request->branch] ?? $request->branch;

            /* CONTENT */
            $pdf->MultiCell(0,5,"
            KNOW ALL MEN BY THESE PRESENTS:

            This CONTRACT SUBSCRIPTION is made and entered into this day of {$day} of {$month}, {$year}
            by and between FIL PRODUCTS SERVICE TELEVISION OF LEYTE, INC., a corporation duly organized and existing under and by virtue of Philippine laws with principal office at City Center Park Real St., Brgy Aslum, Tacloban City, Leyte, Philippines, hereinafter referred to as FPSTI:

            AND

            I {$fullName}, of legal age, and resident of {$fullAddress}, hereinafter referred to as the SUBSCRIBER.

            WITNESSETH:

            1. FPSTI is an entity authorized by law to build and maintain satellite receiver and cable lines in {$branchText}, Agusan del Norte, and provide the SUBSCRIBER with cable TV and internet connection. FPSTIshall not be held legally liable for any change, injury, or illegal acts that the subscribers might have caused in the use of the said services.

            2. does not give warranty or guarantee that the cable TV and internet connection it will provide to the subscriber will be free from interruption. 

            3. FPSTI exercises no control over the content of the information that would pass through FPSTI’s cable TV and internet connection facilities, thereby freeing it from any liability whatsoever in whatever form.

            4. The SUBSCRIBER agrees to pay FPSTIthe one-time installation charges, one-month deposit and other applicable basic charges and fees as agreed upon by the SUBSCRIBERin the application form that is signed. All charges and fees shall be non-refundable.The one-month deposit being non-refundable may be consumed to cover the last month of this contract, or may be made part of the Pre-Termination Fee as provided in paragraph 8 hereof.

            5. The monthly subscription fee for the cable TV and internet connection, whether individually separate or as a package, shall become due and payable without necessity of demand or billing upon the end of each billing cycle.

            6. FPSTI reserves the right to increase the subscription fees and other charges upon prior notice to the subscriber. The FPSTIshall notify the subscriber 15 days prior to its implementation by posting the same in all FPSTIcollection offices. 

            7. All payment of subscription fees and charges shall be made at any FPSTI collection office or by collecting agencies authorized and accredited by FPSTI.

            8. The Internet Modem that FPSTI assigns to SUBSCRIBER, once connected, is not transferrable. The right to use the Internet Modem shall not be leased, transferred or assigned to another person without a written consent and notification from FPSTI. The right to use the service is not transferrable. Accounts are for SUBSCRIBER’s use only. The cable TV and internet connection provided by FPSTIfor the SUBSCRIBER are subject to a lock-in period of three (3) years. A pre-termination fee of the equivalent in PESOS (Monthly Subscription Fee x 3 months)shall be payable by the SUBSCRIBER to FPSTI; otherwise, the billing for the monthly subscription will continue to take effect.

            9. FPSTI shall be responsible in the maintenance and repair of its cable and fiber optic lines. The SUBSCRIBER agrees that only duly authorized employees/technicians of FPSTI shall be allowed to enter the former’s premises for ocular inspection/installation/disconnection/pull-out of equipments and/or repair purposes during the reasonable hours of the day. 

            10. The SUBSCRIBER agrees to grant FPSTIeasement to use an existing passage forcable TV and internet connection in the interior or neighboring premises or areas. FPSTI shall be entitled free of charge to an easement over the SUBSCRIBER’s premises for the passage of repairmen, crossing or laying of cable wire, whether aerial or underground and other connection facilities.

            11. Tampering with the INTERNET MODEM is strictly prohibited. FPSTIreserves the right to immediately suspend the service, blacklist the subscriber and confiscate the INTERNET MODEM foundtampered.

            12. Materials, equipments and accessories charged to the SUBSCRIBER are considered as FPSTIproperty during the existence and validity of the contract and even beyond the termination thereof if the SUBSCRIBER still has an outstanding or unpaid account with FPSTI.

            13. The SUBSCRIBER shall take full responsibility in safeguarding and preserving all properties of FPSTI, entrusted and installed within the premises of the SUBSCRIBER property until the same are officially turned over to the latter.

            14. The SUBSCRIBER shall be liable and responsible for any damage to FPSTI’s property, facilities and equipment entrusted to the former, caused by the negligence, misuse and abuse by the SUBSCRIBER, except through the normal wear and tear. The SUBSCRIBER shall pay corresponding charges, if any, for the necessary repair or replacement of damaged property facilities and equipment.

            15. The SUBSCRIBER is aware and cognizant of the fact that FPSTI is making use of poles owned by one or more utility companies, and that, these companies have controlling interests over the utilization of such poles. Thus, the SUBSCRIBER agrees to hold FPSTIfree from any and all claims, losses or damage that the SUBSCRIBER may incur or suffer in the event that discontinuance of the use of the said poles will transpire beyond the control of FPSTI.

            16.	FPSTI shall not be responsible for any delays, interruptions, non-service which are out of bounds of its operational limits due to power failure, acts of God, acts of nature, acts of any government or supernatural authority, war or public emergency, accident, fire, lightning, riot, strikes, lock-outs, industrial disputes and failure/breakdown of SUBSCRIBER’S owned and managed network facilities.

             17. The system installed and operated by FPSTI is passive-oriented, low voltage DC-type incapable of causing any damage to the computer or television set. This system has been tested and approved by the proper government agency and its satisfactory reception is dependent on a properly functioning computer or television set to be provided and maintained by the SUBSCRIBER under his exclusive responsibility. FPSTIshall not have any responsibility whatsoever with respect to the condition, defect or performance of the SUBSCRIBER’s computer and/or television set(s) or any such other damages.
            ",
            0,
            'J'
            );

            $pdf->Ln(5);
            $pdf->Cell(0,5,'Page 1 of 2',0,1,'R');


                        /* ================= PAGE 5 : CONTRACT PAGE 2 ================= */
            $pdf->AddPage();

            $pdf->SetFont('helvetica','',7.5);

            $pdf->MultiCell(
                0,
                5, 
                "
            18. FPSTI shall have the right to automatically deactivate the INTERNET MODEM in case of:
            a.) Non-payment of one (1) month for Bundle Subscribers. (Internet and Cable), and/or effect immediate disconnection and removal of the INTERNET MODEM/ equipment/properties from the SUBSCRIBER’s premises upon non-settlement of the account FIFTEEN DAYS (15) after the grace period extended from due date;
            b.) Violation by the SUBSCRIBER of any of the foregoing provisions of this CONTRACT, subject to FPSTI’s right to collect all the unpaid dues through the proper authority or court of jurisdiction.

            19. If disconnection and discontinuation of internet services are effected by FPSTI due to default of bill payments on the part of the SUBSCRIBER, the latter may apply for reconnection and resumption of subscription services for the remainder of the present CONTRACT after satisfying the conditions for reconnection.

            20. Except by expressed written waiver, any delay, neglect or forbearance of FPSTI to require or enforce any of the provisions of this CONTRACT shall not prejudice the right of FPSTI to exercise or to act strictly afterwards in accordance with the said provisions.

            21. Any action arising from this CONTRACT shall be filed in the appropriate Trial Court in Tacloban City to the exclusion of any court. The aggrieved party shall be entitled to attorney’s fees and collection expenses equivalent to 25% of the total amount due which in no case shall be less than Php 3,000.00.

            22. This contract shall be enforced until terminated by FPSTI or by the SUBSCRIBER upon five-day (5) prior notice in writing with or without cause. All unpaid dues, arrears and monthly subscriptions for the period shall be settled by the latter prior to the effectivity of the termination.

            IN WITNESS THEREOF, the parties hereto have hereunto signed this contract the day of year first above-written at City Center Park Real St., Brgy Aslum, Tacloban City, Leyte, Philippines.
            ",
                0,
                'J',
                false
            );

            $pdf->Ln(15);

            /* ================= SIGNATURES ================= */

            $pageWidth = 216;

            /* LEFT SIDE (SUBSCRIBER) */
            $leftX = 30;

            /* RIGHT SIDE (FPSTI) */
            $rightX = 120;

            /* SIGNATURE IMAGE */
            if (!empty($sigPath) && file_exists($sigPath)) {
                $pdf->Image($sigPath, $leftX, $pdf->GetY(), 50, 0, 'PNG');
            }

            $pdf->Ln(25);

            /* SUBSCRIBER NAME */
            $pdf->SetFont('helvetica','U',10);
            $pdf->SetX($leftX);
            $pdf->Cell(60,6,$fullName,0,0,'C');

            /* FPSTI LINE */
            $pdf->SetX($rightX);
            $pdf->Cell(60,6,'_________________________',0,1,'C');

            $pdf->SetFont('helvetica','',10);

            /* LABELS */
            $pdf->SetX($leftX);
            $pdf->Cell(60,6,'SUBSCRIBER',0,0,'C');

            $pdf->SetX($rightX);
            $pdf->Cell(60,6,'FPSTI REPRESENTATIVE',0,1,'C');

            $pdf->Ln(10);

            /* WITNESSES */
            $pdf->Cell(90,6,'_________________________',0,0,'C');
            $pdf->Cell(90,6,'_________________________',0,1,'C');

            $pdf->Cell(90,6,'Witness',0,0,'C');
            $pdf->Cell(90,6,'Witness',0,1,'C');

            $pdf->Ln(10);

            /* ================= ACKNOWLEDGEMENT ================= */

            $pdf->SetFont('helvetica','B',10);
            $pdf->Cell(0,6,'ACKNOWLEDGEMENT',0,1,'C');

            $pdf->Ln(2);

            $pdf->SetFont('helvetica','',9);

            /* LEFT HEADER */
            $pdf->Cell(0,5,'REPUBLIC OF THE PHILIPPINES )',0,1);
            $pdf->Cell(0,5,'CITY OF TACLOBAN           ) SS',0,1);
            $pdf->Cell(0,5,'PROVINCE OF LEYTE         )',0,1);

            $pdf->Ln(3);

            /* DATE */
            $day   = date('j');
            $month = date('F');
            $year  = date('Y');
            $fullDate = $month . ' ' . $day . ', ' . $year;

            /* INTRO */
            $pdf->MultiCell(0,5,"
            BEFORE ME, personally appeared this _________ in _________, Tacloban City, Philippines, the following with their evidence of identity written opposite their name below:
            ");

            $pdf->Ln(3);

            /* HEADER LINE */
            $pdf->SetFont('helvetica','B',9);
            $pdf->Cell(70,6,'Name',0,0,'L');
            $pdf->Cell(50,6,'ID No.',0,0,'L');
            $pdf->Cell(70,6,'Date/Place of Issue',0,1,'L');

            /* UNDERLINE HEADER */
            $pdf->Cell(70,6,'______________________________',0,0);
            $pdf->Cell(50,6,'____________________',0,0);
            $pdf->Cell(70,6,'______________________________',0,1);

            /* DATA LINE */
            $pdf->SetFont('helvetica','',9);
            $pdf->Cell(50,6,'',0,0);
            $pdf->Cell(70,6,'',0,1);

            /* EXTRA BLANK LINE */
            $pdf->Cell(70,6,'______________________________',0,0);
            $pdf->Cell(50,6,'____________________',0,0);
            $pdf->Cell(70,6,'______________________________',0,1);

            $pdf->Ln(4);

            /* PARAGRAPH */
            $pdf->MultiCell(0,5,"
            All known to me and to me known to be the same persons who executed the foregoing instrument and they acknowledged that the same is their free and voluntary act and deed. This instrument consists of two (2) pages including the page where this acknowledgement is written, signed by the parties together with their instrumental witnesses in all pages hereof.
            ");

            $pdf->Ln(3);

            $pdf->MultiCell(0,5,"
            Witness my hand and seal, on the day, year and place first written above.
            ");

            $pdf->Ln(5);

            /* DOC DETAILS */
            $pdf->Cell(0,5,'Doc. No. _______',0,1);
            $pdf->Cell(0,5,'Page No. _______',0,1);
            $pdf->Cell(0,5,'Book No. _______',0,1);
            $pdf->Cell(0,5,'Series of _______',0,1);

            $pdf->Ln(5);
            $pdf->Cell(0,5,'Page 2 of 2',0,1,'R');

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

/* SMTP (keep or replace with helper if available) */
$mail->isSMTP();
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



/* ============================
               CONTENT
            ============================ */

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $mail->Subject = "Residential Application - " . $fullNameSafe;

            $mail->Body = '
            <div style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; line-height: 1.6; color: #333;">
                <div style="max-width: 600px; margin: 0 auto; background-color: #FFFFFF; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                    
                    <div style="background-color: #003366; color: #FFFFFF; padding: 20px; text-align: center;">
                        <h2 style="margin: 0; font-size: 20px;">Residential Application Received</h2>
                    </div>
                    
                    <div style="padding: 25px;">
                        <p style="margin-top: 0; color: #4b5563;">
                            A new residential application has been successfully submitted and recorded in our system.
                        </p>
                        
                        <h4 style="color: #003366; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                            Application Details
                        </h4>
                        
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;"><strong>Applicant Name:</strong></td>
                                <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;"><strong>' . htmlspecialchars($fullNameSafe) . '</strong></td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;"><strong>Email Address:</strong></td>
                                <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;">' . htmlspecialchars($customerEmail) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;"><strong>Selected Branch:</strong></td>
                                <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;">' . htmlspecialchars($branchText) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;"><strong>Plan Selected:</strong></td>
                                <td style="padding: 10px; border-bottom: 1px solid #f3f4f6; color:#003366;"><strong>' . htmlspecialchars($planSafe) . '</strong></td>
                            </tr>
                            <tr>
                                <td style="padding: 10px;"><strong>Date Submitted:</strong></td>
                                <td style="padding: 10px;">' . date('F j, Y h:i A') . '</td>
                            </tr>
                        </table>

                        <div style="background-color: #f3f4f6; padding: 15px; border-left: 4px solid #003366;">
                            <strong>Note:</strong> Attached is your application PDF.
                        </div>
                    </div>

                    <div style="background-color: #f9fafb; padding: 15px; text-align: center; font-size: 12px; color: #9ca3af;">
                        System Generated Email • Fil Products System
                    </div>
                    
                </div>
            </div>
            ';

/* =========================
   ATTACHMENTS
========================== */

/* PDF */
if (!empty($pdfContent) && !empty($fileName)) {
    $mail->addStringAttachment($pdfContent, $fileName);
}

/* LIMIT EXTRA FILES */
$maxAttachments = 3;
$count = 0;

foreach ($attachments as $file) {
    if ($file && file_exists($file)) {
        $mail->addAttachment($file);
        $count++;
        if ($count >= $maxAttachments) break;
    }
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
$mailCustomer->Subject = "Residential Application Received";

$mailCustomer->Body = "
<h3>Application Received</h3>
<p>Thank you {$fullNameSafe},</p>

<p>Your residential application has been successfully submitted.</p>
<p>Our Leyte team will review your application and contact you shortly.</p>

<br>
<p>Fil Products Leyte</p>
";

$mailCustomer->send();

/* =========================
   RESPONSE
========================== */

return redirect()
    ->route('residential.inquiry')
    ->with('success',
        '✅ Your Residential Application has been successfully submitted.
        📧 A copy has been sent to your email.
        Our Leyte team will contact you shortly.'
    );

} catch (\Exception $e) {

    return redirect()
        ->route('residential.inquiry')
        ->with('error', 'Submission failed: '.$e->getMessage());

}

}

}