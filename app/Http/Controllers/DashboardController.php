<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\Official;
use App\Models\Certificate;
use App\Models\Blotter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $year = $request->get('year', now()->year);
        $month = $request->get('month', null);

        // Existing statistics
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

        // Demographics Analytics
        $ageDistribution = $this->getAgeDistribution();
        $genderDistribution = $this->getGenderDistribution();
        $civilStatusDistribution = $this->getCivilStatusDistribution();
        $educationDistribution = $this->getEducationDistribution();
        $occupationDistribution = $this->getOccupationDistribution();
        $specialCategoriesDistribution = $this->getSpecialCategoriesDistribution();

        // Blotter Analytics
        $blotterTrend = $this->getBlotterTrend($year, $month);
        $incidentTypesDistribution = $this->getIncidentTypesDistribution($year);
        $blotterResolutionRate = $this->getBlotterResolutionRate();

        // Revenue Analytics
        $revenueData = $this->getCertificateRevenue($year);

        // Quick Insights
        $insights = $this->getQuickInsights();

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
            'recentBlotters',
            'year',
            'month',
            'ageDistribution',
            'genderDistribution',
            'civilStatusDistribution',
            'educationDistribution',
            'occupationDistribution',
            'specialCategoriesDistribution',
            'blotterTrend',
            'incidentTypesDistribution',
            'blotterResolutionRate',
            'revenueData',
            'insights'
        ));
    }

    /**
     * Get age distribution grouped into three categories
     */
    private function getAgeDistribution()
    {
        $residents = Resident::where('status', 'Active')
            ->get(['date_of_birth']);

        $minors = 0;
        $adults = 0;
        $seniors = 0;

        foreach ($residents as $resident) {
            $age = Carbon::parse($resident->date_of_birth)->age;
            if ($age < 18) {
                $minors++;
            } elseif ($age >= 18 && $age < 60) {
                $adults++;
            } else {
                $seniors++;
            }
        }

        return [
            'Minors (0-17)' => $minors,
            'Adults (18-59)' => $adults,
            'Seniors (60+)' => $seniors,
        ];
    }

    /**
     * Get gender distribution with counts
     */
    private function getGenderDistribution()
    {
        return [
            'Male' => Resident::where('status', 'Active')->where('gender', 'Male')->count(),
            'Female' => Resident::where('status', 'Active')->where('gender', 'Female')->count(),
        ];
    }

    /**
     * Get civil status distribution
     */
    private function getCivilStatusDistribution()
    {
        $statuses = ['Single', 'Married', 'Widowed', 'Separated', 'Divorced'];
        $distribution = [];

        foreach ($statuses as $status) {
            $count = Resident::where('status', 'Active')
                ->where('civil_status', $status)
                ->count();
            if ($count > 0) {
                $distribution[$status] = $count;
            }
        }

        return $distribution;
    }

    /**
     * Get educational attainment distribution
     */
    private function getEducationDistribution()
    {
        $distribution = Resident::where('status', 'Active')
            ->whereNotNull('educational_attainment')
            ->where('educational_attainment', '!=', '')
            ->groupBy('educational_attainment')
            ->selectRaw('educational_attainment, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'educational_attainment')
            ->toArray();

        return $distribution ?: ['No Data' => 0];
    }

    /**
     * Get top 10 occupation distribution
     */
    private function getOccupationDistribution()
    {
        $distribution = Resident::where('status', 'Active')
            ->whereNotNull('occupation')
            ->where('occupation', '!=', '')
            ->groupBy('occupation')
            ->selectRaw('occupation, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'occupation')
            ->toArray();

        return $distribution ?: ['No Data' => 0];
    }

    /**
     * Get special categories count (PWD, Senior Citizens, Solo Parents, Voters)
     */
    private function getSpecialCategoriesDistribution()
    {
        return [
            'PWD' => Resident::where('status', 'Active')->where('is_pwd', true)->count(),
            'Senior Citizens' => Resident::where('status', 'Active')->where('is_senior_citizen', true)->count(),
            'Solo Parents' => Resident::where('status', 'Active')->where('is_solo_parent', true)->count(),
            'Registered Voters' => Resident::where('status', 'Active')->where('voter_status', 'Registered')->count(),
        ];
    }

    /**
     * Get blotter incident trend by month
     */
    private function getBlotterTrend($year, $month = null)
    {
        $blotters = Blotter::whereYear('incident_date', $year)->get(['incident_date', 'status']);

        // Initialize all 12 months with zeros for each status
        $statuses = ['Pending', 'Ongoing', 'Resolved', 'Dismissed'];
        $datasets = [];

        foreach ($statuses as $status) {
            $datasets[$status] = array_fill(0, 12, 0);
        }

        // Fill in actual data
        foreach ($blotters as $blotter) {
            $monthIndex = Carbon::parse($blotter->incident_date)->month - 1; // Convert to 0-indexed
            if (isset($datasets[$blotter->status])) {
                $datasets[$blotter->status][$monthIndex]++;
            }
        }

        // Format for Chart.js
        $colors = [
            'Pending' => '#f59e0b',
            'Ongoing' => '#0ea5e9',
            'Resolved' => '#10b981',
            'Dismissed' => '#64748b',
        ];

        $chartDatasets = [];
        foreach ($statuses as $status) {
            $chartDatasets[] = [
                'label' => $status,
                'data' => $datasets[$status],
                'borderColor' => $colors[$status],
                'backgroundColor' => $colors[$status],
                'tension' => 0.4,
            ];
        }

        return $chartDatasets;
    }

    /**
     * Get incident types distribution
     */
    private function getIncidentTypesDistribution($year)
    {
        $blotters = Blotter::whereYear('incident_date', $year)
            ->get(['incident_type']);

        $distribution = [];
        foreach ($blotters as $blotter) {
            if (!isset($distribution[$blotter->incident_type])) {
                $distribution[$blotter->incident_type] = 0;
            }
            $distribution[$blotter->incident_type]++;
        }

        // Sort by count descending
        arsort($distribution);

        // Get top 10
        $distribution = array_slice($distribution, 0, 10, true);

        return $distribution ?: ['No incidents recorded' => 0];
    }

    /**
     * Calculate blotter resolution rate
     */
    private function getBlotterResolutionRate()
    {
        $total = Blotter::count();
        $resolved = Blotter::where('status', 'Resolved')->count();

        return $total > 0 ? round(($resolved / $total) * 100, 1) : 0;
    }

    /**
     * Get certificate revenue by month for the year
     */
    private function getCertificateRevenue($year)
    {
        $certificates = Certificate::where('status', 'Approved')
            ->whereYear('date_issued', $year)
            ->get(['date_issued', 'amount']);

        // Fill all 12 months with zeros
        $monthlyRevenue = array_fill(1, 12, 0);

        foreach ($certificates as $certificate) {
            $month = Carbon::parse($certificate->date_issued)->month;
            $monthlyRevenue[$month] += (float) $certificate->amount;
        }

        return array_values($monthlyRevenue);
    }

    /**
     * Get quick actionable insights
     */
    private function getQuickInsights()
    {
        // Calculate average processing days
        $approvedCertificates = Certificate::where('status', 'Approved')
            ->whereNotNull('reviewed_at')
            ->get(['created_at', 'reviewed_at']);

        $totalDays = 0;
        $count = 0;
        foreach ($approvedCertificates as $cert) {
            $days = Carbon::parse($cert->created_at)->diffInDays(Carbon::parse($cert->reviewed_at));
            $totalDays += $days;
            $count++;
        }

        $avgProcessingDays = $count > 0 ? $totalDays / $count : null;

        return [
            'pending_certificates_7days' => Certificate::where('status', 'Pending')
                ->where('created_at', '<=', now()->subDays(7))
                ->count(),
            'unresolved_blotters_30days' => Blotter::whereIn('status', ['Pending', 'Ongoing'])
                ->where('created_at', '<=', now()->subDays(30))
                ->count(),
            'top_certificate_type' => Certificate::select('type')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('type')
                ->orderByDesc('count')
                ->first(),
            'avg_processing_days' => $avgProcessingDays,
        ];
    }
}
