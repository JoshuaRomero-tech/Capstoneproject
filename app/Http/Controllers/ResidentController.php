<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::with('household');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $residents = $query->latest()->paginate(15);
        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        $households = Household::all();
        return view('residents.create', compact('households'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Widowed,Separated,Divorced',
            'nationality' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'household_id' => 'nullable|exists:households,id',
            'occupation' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string|max:255',
            'voter_status' => 'required|in:Registered,Not Registered',
            'is_pwd' => 'boolean',
            'is_solo_parent' => 'boolean',
            'is_senior_citizen' => 'boolean',
            'philhealth_no' => 'nullable|string|max:255',
            'sss_no' => 'nullable|string|max:255',
            'tin_no' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['is_pwd'] = $request->boolean('is_pwd');
        $validated['is_solo_parent'] = $request->boolean('is_solo_parent');
        $validated['is_senior_citizen'] = $request->boolean('is_senior_citizen');

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('residents', 'public');
        }

        Resident::create($validated);

        return redirect()->route('residents.index')
            ->with('success', 'Resident added successfully.');
    }

    public function show(Resident $resident)
    {
        $resident->load(['household', 'certificates', 'official']);
        return view('residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        $households = Household::all();
        return view('residents.edit', compact('resident', 'households'));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Widowed,Separated,Divorced',
            'nationality' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'household_id' => 'nullable|exists:households,id',
            'occupation' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string|max:255',
            'voter_status' => 'required|in:Registered,Not Registered',
            'is_pwd' => 'boolean',
            'is_solo_parent' => 'boolean',
            'is_senior_citizen' => 'boolean',
            'philhealth_no' => 'nullable|string|max:255',
            'sss_no' => 'nullable|string|max:255',
            'tin_no' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Active,Inactive,Deceased',
        ]);

        $validated['is_pwd'] = $request->boolean('is_pwd');
        $validated['is_solo_parent'] = $request->boolean('is_solo_parent');
        $validated['is_senior_citizen'] = $request->boolean('is_senior_citizen');

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('residents', 'public');
        }

        $resident->update($validated);

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Resident updated successfully.');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();
        return redirect()->route('residents.index')
            ->with('success', 'Resident deleted successfully.');
    }
}
