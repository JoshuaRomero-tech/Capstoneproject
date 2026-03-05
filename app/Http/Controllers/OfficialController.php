<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\Resident;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    public function index()
    {
        $officials = Official::with('resident')
            ->latest()
            ->paginate(15);
        return view('officials.index', compact('officials'));
    }

    public function create()
    {
        $residents = Resident::where('status', 'Active')
            ->doesntHave('official')
            ->get();
        return view('officials.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id|unique:officials,resident_id',
            'position' => 'required|string|max:255',
            'committee' => 'nullable|string|max:255',
            'term_start' => 'required|date',
            'term_end' => 'required|date|after:term_start',
            'status' => 'required|in:Active,Inactive',
        ]);

        Official::create($validated);

        return redirect()->route('officials.index')
            ->with('success', 'Official added successfully.');
    }

    public function show(Official $official)
    {
        $official->load('resident');
        return view('officials.show', compact('official'));
    }

    public function edit(Official $official)
    {
        $residents = Resident::where('status', 'Active')
            ->where(function ($q) use ($official) {
                $q->doesntHave('official')
                  ->orWhere('id', $official->resident_id);
            })->get();
        return view('officials.edit', compact('official', 'residents'));
    }

    public function update(Request $request, Official $official)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id|unique:officials,resident_id,' . $official->id,
            'position' => 'required|string|max:255',
            'committee' => 'nullable|string|max:255',
            'term_start' => 'required|date',
            'term_end' => 'required|date|after:term_start',
            'status' => 'required|in:Active,Inactive',
        ]);

        $official->update($validated);

        return redirect()->route('officials.index')
            ->with('success', 'Official updated successfully.');
    }

    public function destroy(Official $official)
    {
        $official->delete();
        return redirect()->route('officials.index')
            ->with('success', 'Official deleted successfully.');
    }
}
