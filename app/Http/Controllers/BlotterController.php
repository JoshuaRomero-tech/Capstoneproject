<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function updateStatus(Request $request, Blotter $blotter, SmsService $smsService)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Ongoing,Resolved,Dismissed',
            'action_taken' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $previousStatus = $blotter->status;

        $blotter->update($validated);

        if ($this->shouldNotifyRequester($previousStatus, $blotter->status)) {
            $this->notifyRequesterViaSms($smsService, $blotter->fresh('complainant'));
        }

        return redirect()->route('blotters.show', $blotter)
            ->with('success', 'Blotter status updated successfully.');
    }

    public function destroy(Blotter $blotter)
    {
        $blotter->delete();
        return redirect()->route('blotters.index')
            ->with('success', 'Blotter record deleted successfully.');
    }

    private function shouldNotifyRequester(string $previousStatus, string $newStatus): bool
    {
        if ($previousStatus === $newStatus) {
            return false;
        }

        return in_array($newStatus, ['Resolved', 'Dismissed'], true);
    }

    private function notifyRequesterViaSms(SmsService $smsService, Blotter $blotter): void
    {
        $requester = $blotter->complainant;

        if (!$requester || !$requester->contact_number) {
            return;
        }

        $isApproved = $blotter->status === 'Resolved';
        $decision = $isApproved ? 'APPROVED' : 'DISAPPROVED';

        $message = "CiviTrack: Your blotter report {$blotter->case_no} is {$decision}. Current status: {$blotter->status}.";

        if ($blotter->remarks) {
            $message .= ' Note: ' . Str::limit($blotter->remarks, 90);
        }

        $smsService->send($requester->contact_number, $message, [
            'feature' => 'blotter_status_update',
            'blotter_id' => $blotter->id,
            'case_no' => $blotter->case_no,
            'resident_id' => $requester->id,
            'status' => $blotter->status,
        ]);
    }
}
