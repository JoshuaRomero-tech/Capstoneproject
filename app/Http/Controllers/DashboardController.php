<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\Official;
use App\Models\Certificate;
use App\Models\Blotter;

class DashboardController extends Controller
{
    public function index()
    {
        $totalResidents = Resident::where('status', 'Active')->count();
        $totalHouseholds = Household::count();
        $totalMale = Resident::where('status', 'Active')->where('gender', 'Male')->count();
        $totalFemale = Resident::where('status', 'Active')->where('gender', 'Female')->count();
        $totalSeniorCitizens = Resident::where('status', 'Active')->where('is_senior_citizen', true)->count();
        $totalPwd = Resident::where('status', 'Active')->where('is_pwd', true)->count();
        $totalVoters = Resident::where('status', 'Active')->where('voter_status', 'Registered')->count();
        $totalOfficials = Official::where('status', 'Active')->count();
        $pendingBlotters = Blotter::where('status', 'Pending')->count();
        $pendingCertificates = Certificate::where('status', 'Pending')->count();
        $certificatesThisMonth = Certificate::whereMonth('date_issued', now()->month)
            ->whereYear('date_issued', now()->year)->count();

        $recentResidents = Resident::where('status', 'Active')
            ->latest()->take(5)->get();
        $recentBlotters = Blotter::with(['complainant', 'respondent'])
            ->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalResidents',
            'totalHouseholds',
            'totalMale',
            'totalFemale',
            'totalSeniorCitizens',
            'totalPwd',
            'totalVoters',
            'totalOfficials',
            'pendingBlotters',
            'pendingCertificates',
            'certificatesThisMonth',
            'recentResidents',
            'recentBlotters'
        ));
    }
}
