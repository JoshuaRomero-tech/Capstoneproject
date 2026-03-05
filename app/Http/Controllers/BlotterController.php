<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Resident;
use Illuminate\Http\Request;

class BlotterController extends Controller
{
    public function index(Request $request)
    {
        $query = Blotter::with(['complainant', 'respondent', 'recordedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_no', 'like', "%{$search}%")
                  ->orWhereHas('complainant', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $blotters = $query->latest()->paginate(15);
        return view('blotters.index', compact('blotters'));
    }

    public function create()
    {
        $residents = Resident::where('status', 'Active')->get();
        return view('blotters.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'complainant_id' => 'required|exists:residents,id',
            'respondent_id' => 'required|exists:residents,id|different:complainant_id',
            'incident_details' => 'required|string',
            'incident_type' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_location' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $validated['case_no'] = 'BLT-' . date('Y') . '-' . str_pad(Blotter::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'Pending';
        $validated['recorded_by'] = auth()->id();

        Blotter::create($validated);

        return redirect()->route('blotters.index')
            ->with('success', 'Blotter record added successfully.');
    }

    public function show(Blotter $blotter)
    {
        $blotter->load(['complainant', 'respondent', 'recordedBy']);
        return view('blotters.show', compact('blotter'));
    }

    public function edit(Blotter $blotter)
    {
        $residents = Resident::where('status', 'Active')->get();
        return view('blotters.edit', compact('blotter', 'residents'));
    }

    public function update(Request $request, Blotter $blotter)
    {
        $validated = $request->validate([
            'complainant_id' => 'required|exists:residents,id',
            'respondent_id' => 'required|exists:residents,id|different:complainant_id',
            'incident_details' => 'required|string',
            'incident_type' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_location' => 'required|string|max:255',
            'status' => 'required|in:Pending,Ongoing,Resolved,Dismissed',
            'action_taken' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $blotter->update($validated);

        return redirect()->route('blotters.show', $blotter)
            ->with('success', 'Blotter record updated successfully.');
    }

    public function destroy(Blotter $blotter)
    {
        $blotter->delete();
        return redirect()->route('blotters.index')
            ->with('success', 'Blotter record deleted successfully.');
    }
}
