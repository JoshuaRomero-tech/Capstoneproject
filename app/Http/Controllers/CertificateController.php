<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Resident;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['resident', 'issuedBy']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $certificates = $query->latest()->paginate(15);
        return view('certificates.index', compact('certificates'));
    }

    public function create()
    {
        $residents = Resident::where('status', 'Active')->get();
        return view('certificates.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'type' => 'required|in:Barangay Clearance,Certificate of Residency,Certificate of Indigency,Business Clearance,Barangay ID',
            'purpose' => 'required|string|max:255',
            'or_number' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:0',
            'date_issued' => 'required|date',
            'valid_until' => 'nullable|date|after:date_issued',
            'remarks' => 'nullable|string',
        ]);

        $validated['issued_by'] = auth()->id();

        Certificate::create($validated);

        return redirect()->route('certificates.index')
            ->with('success', 'Certificate issued successfully.');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['resident', 'issuedBy']);
        return view('certificates.show', compact('certificate'));
    }

    public function print(Certificate $certificate)
    {
        $certificate->load(['resident', 'issuedBy']);
        return view('certificates.print', compact('certificate'));
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }
}
