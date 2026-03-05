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

        // Counts for status cards
        $pendingCount = Blotter::where('status', 'Pending')->count();
        $ongoingCount = Blotter::where('status', 'Ongoing')->count();
        $resolvedCount = Blotter::where('status', 'Resolved')->count();
        $dismissedCount = Blotter::where('status', 'Dismissed')->count();

        return view('blotters.index', compact('blotters', 'pendingCount', 'ongoingCount', 'resolvedCount', 'dismissedCount'));
    }

    public function show(Blotter $blotter)
    {
        $blotter->load(['complainant', 'respondent', 'recordedBy']);
        return view('blotters.show', compact('blotter'));
    }

    public function review(Blotter $blotter)
    {
        $blotter->load(['complainant', 'respondent', 'recordedBy']);
        return view('blotters.review', compact('blotter'));
    }

    public function updateStatus(Request $request, Blotter $blotter)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Ongoing,Resolved,Dismissed',
            'action_taken' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $blotter->update($validated);

        return redirect()->route('blotters.show', $blotter)
            ->with('success', 'Blotter status updated successfully.');
    }

    public function destroy(Blotter $blotter)
    {
        $blotter->delete();
        return redirect()->route('blotters.index')
            ->with('success', 'Blotter record deleted successfully.');
    }
}
