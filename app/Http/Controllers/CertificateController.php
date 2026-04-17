<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['resident', 'issuedBy', 'reviewedBy']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $certificates = $query->latest()->paginate(15);

        // Counts for status tabs
        $pendingCount = Certificate::where('status', 'Pending')->count();
        $approvedCount = Certificate::where('status', 'Approved')->count();
        $disapprovedCount = Certificate::where('status', 'Disapproved')->count();

        return view('certificates.index', compact('certificates', 'pendingCount', 'approvedCount', 'disapprovedCount'));
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['resident', 'issuedBy', 'reviewedBy']);
        return view('certificates.show', compact('certificate'));
    }

    public function approve(Request $request, Certificate $certificate, SmsService $smsService)
    {
        $validated = $request->validate([
            'or_number' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:0',
            'valid_until' => 'nullable|date|after:today',
            'review_remarks' => 'nullable|string',
        ]);

        $certificate->update([
            'status' => 'Approved',
            'or_number' => $validated['or_number'] ?? null,
            'amount' => $validated['amount'],
            'valid_until' => $validated['valid_until'] ?? null,
            'review_remarks' => $validated['review_remarks'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'date_issued' => now(),
        ]);

        $this->notifyRequesterViaSms($smsService, $certificate->fresh('resident'));

        return redirect()->route('certificates.show', $certificate)
            ->with('success', 'Certificate request approved successfully.');
    }

    public function disapprove(Request $request, Certificate $certificate, SmsService $smsService)
    {
        $validated = $request->validate([
            'review_remarks' => 'required|string|max:500',
        ]);

        $certificate->update([
            'status' => 'Disapproved',
            'review_remarks' => $validated['review_remarks'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->notifyRequesterViaSms($smsService, $certificate->fresh('resident'));

        return redirect()->route('certificates.show', $certificate)
            ->with('success', 'Certificate request has been disapproved.');
    }

    public function print(Certificate $certificate)
    {
        if ($certificate->status !== 'Approved') {
            return redirect()->route('certificates.show', $certificate)
                ->with('error', 'Only approved certificates can be printed.');
        }

        $certificate->load(['resident', 'issuedBy', 'reviewedBy']);
        return view('certificates.print', compact('certificate'));
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('certificates.index')
            ->with('success', 'Certificate request deleted successfully.');
    }

    private function notifyRequesterViaSms(SmsService $smsService, Certificate $certificate): void
    {
        $resident = $certificate->resident;

        if (!$resident || !$resident->contact_number) {
            return;
        }

        $message = "CiviTrack: Your {$certificate->type} request is " . strtoupper($certificate->status) . ". Ref #CERT-{$certificate->id}.";

        if ($certificate->status === 'Approved') {
            $message .= ' Please visit the barangay hall to claim your certificate.';
        }

        if ($certificate->status === 'Disapproved') {
            $message .= ' Please contact the barangay office for details.';

            if ($certificate->review_remarks) {
                $message .= ' Reason: ' . Str::limit($certificate->review_remarks, 90);
            }
        }

        $smsService->send($resident->contact_number, $message, [
            'feature' => 'certificate_status_update',
            'certificate_id' => $certificate->id,
            'resident_id' => $resident->id,
            'status' => $certificate->status,
        ]);
    }
}
