<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ResidentialInquiry;
use App\Models\FilbizInquiry;
use App\Models\Area;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{


public function home()
{
    return view('staff.home'); // your dashboard
}

public function search(Request $request)
{
    $query = $request->input('query');

$results = Subscriber::where('account_number', 'LIKE', "%$query%")
    ->orWhere('firstname', 'LIKE', "%$query%")
    ->orWhere('middlename', 'LIKE', "%$query%")
    ->orWhere('lastname', 'LIKE', "%$query%")
    ->orWhere('company_name', 'LIKE', "%$query%")
    ->limit(50)
    ->get();

    return view('staff.home', compact('results'));
}

public function viewSubscriber($id)
{
    $subscriber = Subscriber::findOrFail($id);

    return view('staff.subscriber-view', compact('subscriber'));
}
    /**
     * =========================
     * COMPLAINT LIST
     * =========================
     */
    public function complaints(Request $request)
    {
        $query = Complaint::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('account_name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('ticket_number', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('address', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('mobile_number', 'LIKE', '%' . $request->search . '%');
            });
        }

        // 📍 FILTER BY AREA
        if ($request->filter_area) {
            $area = Area::find($request->filter_area);
            if ($area) {
                $query->where('area', $area->area_name);
            }
        }

        // 📅 SORT
        if ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $complaints = $query->get();
        $areas = Area::orderBy('area_name')->get();

        return view('staff.complaints', compact('complaints', 'areas'));
    }

    /**
     * =========================
     * UPDATE COMPLAINT STATUS
     * =========================
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Assigned,On-going,Resolved,Cancelled'
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->status = $request->status;
        $complaint->save();

        return back()->with('success', 'Complaint updated successfully');
    }

    /**
     * =========================
     * GENERATE TRANSMITTAL
     * =========================
     */
    public function generateTransmittal(Request $request)
    {
        if ($request->selected && count($request->selected) > 0) {
            $complaints = Complaint::whereIn('id', $request->selected)->get();
            $area = null;
        } elseif ($request->area_id) {
            $area = Area::findOrFail($request->area_id);
            $complaints = Complaint::where('area', $area->area_name)->get();
        } else {
            return back()->with('error', 'Please select complaints or choose area');
        }

        if ($complaints->isEmpty()) {
            return back()->with('error', 'No complaints found for selected option');
        }

        $date = now()->format('m/d/Y');

        $pdf = Pdf::loadView('staff.transmittal_pdf', compact('complaints', 'date', 'area'));

        return $pdf->download('Job_Order_Transmittal.pdf');
    }

    /**
     * =========================
     * RESIDENTIAL INSTALLATIONS
     * =========================
     */
    public function residentialInstallations(Request $request)
    {
        $query = ResidentialInquiry::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('firstname', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('lastname', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('street', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('brgy', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('city', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->sort == 'status') {
            $query->orderByRaw("
                FIELD(status,
                    'Pending',
                    'Processing',
                    'Assigned to Technician',
                    'Full NAP',
                    'Installed',
                    'Cancelled'
                )
            ");
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $residential = $query->get();

        return view('staff.installations.residential', compact('residential'));
    }

    /**
     * =========================
     * FILBIZ INSTALLATIONS
     * =========================
     */
    public function filbizInstallations(Request $request)
    {
        $query = FilbizInquiry::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('company_name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('office_address', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('mobile_number', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->sort == 'status') {
            $query->orderByRaw("
                FIELD(status,
                    'Pending',
                    'Processing',
                    'Assigned to Technician',
                    'Full NAP',
                    'Installed',
                    'Cancelled'
                )
            ");
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $filbiz = $query->get();

        return view('staff.installations.filbiz', compact('filbiz'));
    }

    /**
     * =========================
     * UPDATE INSTALLATION STATUS
     * =========================
     */
    public function updateInstallationStatus(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        if ($type === 'residential') {
            $record = ResidentialInquiry::findOrFail($id);
        } elseif ($type === 'filbiz') {
            $record = FilbizInquiry::findOrFail($id);
        } else {
            return back()->with('error', 'Invalid installation type');
        }

        $record->status = $request->status;
        $record->save();

        return back()->with('success', 'Installation updated successfully');
    }

    public function createComplaint($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $areas = Area::orderBy('area_name')->get(); // important

        return view('staff.create-complaint', compact('subscriber', 'areas'));
    }

public function storeComplaint(Request $request)
{
    $request->validate([
        'account_name' => 'required',
        'account_number' => 'required',
        'address' => 'required',
        'mobile_number' => 'required',
        'area' => 'required',
        'category' => 'required',
    ]);

    Complaint::create([
        'ticket_number' => 'TICK-' . date('YmdHis'),

        'account_name' => $request->account_name,
        'account_number' => $request->account_number,
        'email' => $request->email,
        'address' => $request->address,
        'area' => $request->area,
        'mobile_number' => $request->mobile_number,
        'category' => $request->category,

        'status' => 'Pending',
        'remarks' => $request->remarks
    ]);

    return redirect()->route('staff.home')
        ->with('success', 'Complaint submitted successfully!');
}
}