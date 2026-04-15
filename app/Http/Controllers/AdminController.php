<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Application;

class AdminController extends Controller
{

    /*
    =========================================
    ADMIN DASHBOARD
    =========================================
    */
public function dashboard()
{

    $complaints = DB::table('customers')->count();

    $applications = DB::table('residential_inquiry')->count();

    /* ==========================
       COMPLAINT STATUS COUNT
    ========================== */

    $statusCounts = DB::table('customers')
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total','status');

    $pending = $statusCounts['Pending'] ?? 0;
    $assigned = $statusCounts['Assigned'] ?? 0;
    $progress = $statusCounts['In Progress'] ?? 0;
    $resolved = $statusCounts['Resolved'] ?? 0;
    $closed = $statusCounts['Closed'] ?? 0;


    /* ==========================
       INSTALLATION BY DATE
    ========================== */

    $installData = DB::table('residential_inquiry')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $installDates = $installData->pluck('date');
    $installCounts = $installData->pluck('total');


    /* ==========================
       COMPLAINTS BY AREA
    ========================== */

    $complaintAreaData = DB::table('customers')
        ->select('area', DB::raw('count(*) as total'))
        ->groupBy('area')
        ->orderBy('total','desc')
        ->get();

    $areaLabels = $complaintAreaData->pluck('area');
    $areaCounts = $complaintAreaData->pluck('total');


    /* ==========================
       MONTHLY INSTALL GROWTH
    ========================== */

    $monthlyInstallData = DB::table('residential_inquiry')
        ->select(
            DB::raw('DATE_FORMAT(created_at,"%Y-%m") as month'),
            DB::raw('count(*) as total')
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    $installMonths = $monthlyInstallData->pluck('month');
    $installTotals = $monthlyInstallData->pluck('total');


    /* ==========================
       TOP PROBLEM CATEGORIES
    ========================== */

    $categoryData = DB::table('customers')
        ->select('category', DB::raw('count(*) as total'))
        ->groupBy('category')
        ->orderBy('total','desc')
        ->limit(5)
        ->get();

    $categoryLabels = $categoryData->pluck('category');
    $categoryCounts = $categoryData->pluck('total');


    return view('admin.dashboard', [

        'complaints' => $complaints,
        'applications' => $applications,

        'pending'=>$pending,
        'assigned'=>$assigned,
        'progress'=>$progress,
        'resolved'=>$resolved,
        'closed'=>$closed,

        'installDates'=>$installDates,
        'installCounts'=>$installCounts,

        'areaLabels'=>$areaLabels,
        'areaCounts'=>$areaCounts,

        'installMonths'=>$installMonths,
        'installTotals'=>$installTotals,

        'categoryLabels'=>$categoryLabels,
        'categoryCounts'=>$categoryCounts

    ]);
}


/*
=========================================
COMPLAINT LIST
=========================================
*/
public function complaints(Request $request)
{

$query = DB::table('customers');

if($request->search){

$query->where(function($q) use ($request){

$q->where('account_name','like','%'.$request->search.'%')
  ->orWhere('area','like','%'.$request->search.'%')
  ->orWhere('category','like','%'.$request->search.'%');

});

}

if($request->sort_area){
$query->orderBy('area',$request->sort_area);
}

if($request->sort_date){
$query->orderBy('created_at',$request->sort_date);
}else{
$query->orderBy('created_at','desc');
}

$complaints = $query->get();

return view('admin.complaints',[
'complaints'=>$complaints
]);

}


/*
=========================================
UPDATE COMPLAINT STATUS
=========================================
*/

public function updateStatus(Request $request, $id)
{

    DB::table('customers')
        ->where('id', $id)
        ->update([
            'status' => $request->input('status')
        ]);

    return redirect()
        ->route('admin.complaints')
        ->with('success', 'Complaint status updated successfully.');

}

/*
=========================================
GENERATE TRANSMITTAL PDF
=========================================
*/

public function generateTransmittal(Request $request)
{

$selectedCustomers = $request->input('customers', []);
$area = $request->input('area');

$query = DB::table('customers');

/*
-----------------------------------------
CASE 1: Selected customers
-----------------------------------------
*/
if(!empty($selectedCustomers)){

    $query->whereIn('id', $selectedCustomers);

    // UPDATE STATUS TO ASSIGNED
    DB::table('customers')
        ->whereIn('id', $selectedCustomers)
        ->update([
            'status' => 'Assigned'
        ]);
}

/*
-----------------------------------------
CASE 2: Generate by area
-----------------------------------------
*/
elseif(!empty($area)){

    $query->where('area', $area);

    // UPDATE STATUS TO ASSIGNED FOR THAT AREA
    DB::table('customers')
        ->where('area', $area)
        ->where('status','Pending')
        ->update([
            'status' => 'Assigned'
        ]);
}

/*
-----------------------------------------
CASE 3: Nothing selected
-----------------------------------------
*/
else{

    return redirect()
        ->back()
        ->with('error','Please select customers or choose an area.');
}

/*
-----------------------------------------
GET COMPLAINT DATA
-----------------------------------------
*/

$complaints = $query
        ->orderBy('area')
        ->orderBy('account_name')
        ->get();

/*
-----------------------------------------
GET TEAM LEADER BY AREA
-----------------------------------------
*/

$teamLeader = null;

if(empty($selectedCustomers) && $area){

$teamLeader = DB::table('area')
                ->where('area_name',$area)
                ->first();

}

$date = now()->format('m/d/Y');

/*
-----------------------------------------
GENERATE PDF
-----------------------------------------
*/

$pdf = Pdf::loadView(
        'admin.transmittal_pdf',
        compact('complaints','date','area','teamLeader')
        )
        ->setPaper('a4','portrait');

return $pdf->download('job-order-transmittal.pdf');

}

/*
=========================================
OLD FUNCTION (OPTIONAL KEEP)
=========================================
*/

public function transmittalArea()
{

$complaints = DB::table('customers')
        ->whereIn('status',['Assigned','In Progress'])
        ->orderBy('area')
        ->get();

$date = now()->format('m/d/Y');

$pdf = Pdf::loadView(
        'admin.transmittal_pdf',
        compact('complaints','date')
        )
        ->setPaper('a4','portrait');

return $pdf->stream('job-order-transmittal.pdf');

}


/*
=========================================
FILBIZ APPLICATION LIST
=========================================
*/
public function filbiz()
{
    $applications = DB::table('filbiz_inquiry')
        ->orderByDesc('created_at')
        ->get();

    return view('admin.applications.filbiz', compact('applications'));
}


/*
=========================================
FILBIZ UPGRADE LIST
=========================================
*/
public function filbizUpgrade()
{
    $applications = DB::table('filbiz_inquiry')
        ->orderByDesc('created_at')
        ->get();

    return view('admin.applications.filbiz_upgrade', compact('applications'));
}


/*
=========================================
RESIDENTIAL APPLICATION LIST
=========================================
*/
public function residential()
{
    $applications = DB::table('residential_inquiry')
        ->orderByDesc('created_at')
        ->get();

    return view('admin.applications.residential', compact('applications'));
}


/*
=========================================
RESIDENTIAL UPGRADE LIST
=========================================
*/
public function residentialUpgrade()
{
    $applications = DB::table('residential_inquiry')
        ->orderByDesc('created_at')
        ->get();

    return view('admin.applications.residential_upgrade', compact('applications'));
}


/*
=========================================
DOWNLOAD FILBIZ PDF
=========================================
*/
public function downloadFilbiz($id)
{
    $file = DB::table('filbiz_inquiry')->where('id',$id)->first();

    if(!$file || !$file->file){
        abort(404);
    }

    $path = storage_path('app/public/'.$file->file);

    if(!file_exists($path)){
        abort(404);
    }

    return response()->download($path);
}

public function downloadResidential($id)
{
    $file = DB::table('residential_inquiry')
        ->where('id',$id)
        ->first();

    if(!$file || !$file->file){
        abort(404);
    }

    $path = storage_path('app/public/'.$file->file);

    if(!file_exists($path)){
        abort(404);
    }

    return response()->download($path);
}


/*
=========================================
AREA MANAGEMENT
=========================================
*/

public function areas()
{

$areas = DB::table('area')->orderBy('area_name')->get();

return view('admin.areas', compact('areas'));

}


public function saveArea(Request $request)
{

DB::table('area')->insert([

'area_name' => $request->area_name,
'team_leader' => $request->team_leader,
'created_at' => now()

]);

return redirect()->back()->with('success','Area added successfully');

}


public function updateArea(Request $request, $id)
{

DB::table('area')
->where('id',$id)
->update([

'team_leader' => $request->team_leader

]);

return redirect()->back()->with('success','Team leader updated');

}


public function deleteArea($id)
{

DB::table('area')->where('id',$id)->delete();

return redirect()->back()->with('success','Area deleted');

}


}