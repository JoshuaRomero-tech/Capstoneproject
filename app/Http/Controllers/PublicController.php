<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\Official;
use App\Models\Certificate;
use App\Models\Blotter;
use App\Models\Announcement;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Public landing page
     */
    public function home()
    {
        $totalResidents = Resident::where('status', 'Active')->count();
        $totalHouseholds = Household::count();
        $totalOfficials = Official::where('status', 'Active')->count();
        $officials = Official::with('resident')->where('status', 'Active')->orderByRaw("
            CASE position
                WHEN 'Barangay Captain' THEN 1
                WHEN 'Barangay Secretary' THEN 2
                WHEN 'Barangay Treasurer' THEN 3
                ELSE 4
            END
        ")->take(4)->get();

        $announcements = Announcement::published()
            ->latest('publish_date')
            ->take(5)
            ->get();

        return view('public.home', compact('totalResidents', 'totalHouseholds', 'totalOfficials', 'officials', 'announcements'));
    }

    /**
     * Public officials listing
     */
    public function officials()
    {
        $officials = Official::with('resident')
            ->where('status', 'Active')
            ->orderByRaw("
                CASE position
                    WHEN 'Barangay Captain' THEN 1
                    WHEN 'Barangay Secretary' THEN 2
                    WHEN 'Barangay Treasurer' THEN 3
                    ELSE 4
                END
            ")
            ->get();

        return view('public.officials', compact('officials'));
    }

    /**
     * Services overview page
     */
    public function services()
    {
        return view('public.services');
    }

    /**
     * Certificate request form
     */
    public function certificateRequest()
    {
        $residents = Resident::where('status', 'Active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('public.certificate-request', compact('residents'));
    }

    /**
     * Submit certificate request
     */
    public function certificateRequestStore(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'type' => 'required|in:Barangay Clearance,Certificate of Residency,Certificate of Indigency,Business Clearance,Barangay ID',
            'purpose' => 'required|string|max:255',
        ]);

        $validated['issued_by'] = 1; // Default to admin (will be reviewed by staff)
        $validated['amount'] = 0;
        $validated['date_issued'] = now();
        $validated['status'] = 'Pending';
        $validated['remarks'] = 'Requested via public portal - pending processing';

        Certificate::create($validated);

        return redirect()->route('public.services')
            ->with('success', 'Your certificate request has been submitted successfully! It is now pending review by barangay staff.');
    }

    /**
     * Blotter filing form
     */
    public function blotterFile()
    {
        $residents = Resident::where('status', 'Active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('public.blotter-file', compact('residents'));
    }

    /**
     * Submit blotter report
     */
    public function blotterFileStore(Request $request)
    {
        $validated = $request->validate([
            'complainant_id' => 'required|exists:residents,id',
            'respondent_id' => 'required|exists:residents,id|different:complainant_id',
            'incident_type' => 'required|string|max:100',
            'incident_date' => 'required|date|before_or_equal:today',
            'incident_location' => 'required|string|max:255',
            'narrative' => 'required|string',
        ]);

        // Map narrative to incident_details column
        $validated['incident_details'] = $validated['narrative'];
        unset($validated['narrative']);

        // Generate case number
        $year = date('Y');
        $lastBlotter = Blotter::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $nextNum = $lastBlotter ? (intval(substr($lastBlotter->case_no, -4)) + 1) : 1;
        $validated['case_no'] = 'BLT-' . $year . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        $validated['status'] = 'Pending';
        $validated['recorded_by'] = 1; // Default admin
        $validated['remarks'] = 'Filed via public portal - pending review';

        Blotter::create($validated);

        return redirect()->route('public.services')
            ->with('success', 'Your blotter report has been filed successfully with Case No: ' . $validated['case_no'] . '. Please visit the barangay hall for follow-up.');
    }
}
