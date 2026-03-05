<?php

namespace App\Http\Controllers;

use App\Models\Household;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function index(Request $request)
    {
        $query = Household::withCount('residents');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('household_no', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $households = $query->latest()->paginate(15);
        return view('households.index', compact('households'));
    }

    public function create()
    {
        return view('households.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_no' => 'required|string|unique:households,household_no|max:50',
            'address' => 'required|string',
        ]);

        Household::create($validated);

        return redirect()->route('households.index')
            ->with('success', 'Household added successfully.');
    }

    public function show(Household $household)
    {
        $household->load('residents');
        return view('households.show', compact('household'));
    }

    public function edit(Household $household)
    {
        return view('households.edit', compact('household'));
    }

    public function update(Request $request, Household $household)
    {
        $validated = $request->validate([
            'household_no' => 'required|string|max:50|unique:households,household_no,' . $household->id,
            'address' => 'required|string',
        ]);

        $household->update($validated);

        return redirect()->route('households.show', $household)
            ->with('success', 'Household updated successfully.');
    }

    public function destroy(Household $household)
    {
        $household->delete();
        return redirect()->route('households.index')
            ->with('success', 'Household deleted successfully.');
    }
}
