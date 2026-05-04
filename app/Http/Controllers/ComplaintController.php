<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;



class ComplaintController extends Controller
{
        public function complaint()
    {
        return view('pages.complaint');
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
    $request->validate([
        'account_name' => 'required|string|max:255',
        'address' => 'required|string',
        'mobile_number' => 'required|string',
        'branch' => 'required|string',
        'remarks' => 'required|string',
        'email' => 'required|email'
    ]);

    

    // ✅ SANITIZE INPUT (SECURITY)
    $name = htmlspecialchars($request->account_name);
    $email = htmlspecialchars($request->email);
    $address = htmlspecialchars($request->address);
    $mobilenumber = htmlspecialchars($request->mobile_number);
    $branch = htmlspecialchars($request->branch);
    $remarks = nl2br(htmlspecialchars($request->remarks));

    try {

        /* =========================================
           EMAIL 1: SEND TO SUPPORT
        ========================================= */
        $mail = new PHPMailer(true);
        $this->configureSMTP($mail);

        $mail->setFrom(env('MAIL_USERNAME'), 'Fil Products Leyte');
        $mail->addAddress('info.leyte@filproducts.ph');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "New Customer Complaint";

        $mail->Body = "
        <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333;'>
            <h2 style='color:#d9534f;'>New Customer Complaint</h2>
            <hr>

            <table style='width:100%; border-collapse: collapse;'>
                <tr>
                    <td style='padding:8px;'><strong>Name:</strong></td>
                    <td>{$name}</td>
                </tr>
                <tr>
                    <td style='padding:8px;'><strong>Email:</strong></td>
                    <td>{$email}</td>
                </tr>
                <tr>
                    <td style='padding:8px;'><strong>Address:</strong></td>
                    <td>{$address}</td>
                </tr>
                <tr>
                    <td style='padding:8px;'><strong>Mobile Number:</strong></td>
                    <td>{$mobilenumber}</td>
                </tr>
                <tr>
                    <td style='padding:8px;'><strong>Branch:</strong></td>
                    <td>{$branch}</td>
                </tr>
                <tr>
                    <td style='padding:8px; vertical-align: top;'><strong>Remarks:</strong></td>
                    <td>{$remarks}</td>
                </tr>
            </table>

            <br>
            <p>This complaint was submitted through the Fil Products website.</p>
        </div>
        ";

        $mail->send();


        /* =========================================
           EMAIL 2: SEND TO CUSTOMER (CONFIRMATION)
        ========================================= */
        $mailCustomer = new PHPMailer(true);
        $this->configureSMTP($mailCustomer);

        $mailCustomer->setFrom(env('MAIL_USERNAME'), 'Fil Products Leyte');
        $mailCustomer->addAddress($email);

        $mailCustomer->isHTML(true);
        $mailCustomer->Subject = "We Received Your Complaint";

        $mailCustomer->Body = "
        <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333;'>
            <h2 style='color:#5cb85c;'>Complaint Received</h2>
            <hr>

            <p>Dear <strong>{$name}</strong>,</p>

            <p>Thank you for contacting <strong>Fil Products Leyte</strong>.</p>

            <p>We have successfully received your complaint. Our team will review it and get back to you as soon as possible.</p>

            <p>If necessary, we may contact you for additional details.</p>

            <br>
            <p>Best regards,<br>
            Fil Products Team</p>
        </div>
        ";

        $mailCustomer->send();

                $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://script.google.com/macros/s/AKfycbzAon6ilmqXLIfkD1tHQhmizs08DQ_rtk-aaABvyg-IcGKxLrVb8TzoNuIWYS2bn2Rv/exec', [
            "date" => now()->format('Y-m-d'),
            "mobile" => $request->mobile_number,
            "subscriber_name" => $request->account_name,
            "address" => $request->address,
            "remarks" => $request->remarks,     
            "prepared_by" => "Website",         
            "branch" => $branch
            
        ]);

        Log::info('STATUS: ' . $response->status());
        Log::info('BODY: ' . $response->body());

    } catch (\Exception $e) {

        Log::error('Mail Error: ' . $e->getMessage());

        return back()->with('error', 'Email failed. Please try again.');
    }

    return redirect()
        ->route('complaint')
        ->with('success', "Complaint submitted successfully.");
}
}