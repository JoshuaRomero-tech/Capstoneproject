<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\Resident;
use App\Imports\OfficialImport;
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $importer = new OfficialImport();
        $importer->import($request->file('file')->getPathname());

        $message = "{$importer->getImportedCount()} official(s) imported successfully.";
        if ($importer->getSkippedCount() > 0) {
            $message .= " {$importer->getSkippedCount()} row(s) skipped.";
        }

        $redirect = redirect()->route('officials.index');

        if ($importer->hasErrors() && $importer->getImportedCount() === 0) {
            return $redirect->with('error', $message)->with('import_errors', $importer->getErrors());
        }
        if ($importer->hasErrors()) {
            return $redirect->with('warning', $message)->with('import_errors', $importer->getErrors());
        }
        return $redirect->with('success', $message);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="officials_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'position', 'committee', 'term_start', 'term_end', 'status']);
            fputcsv($file, ['Juan Dela Cruz', 'Barangay Captain', 'Peace and Order', '2025-01-01', '2028-12-31', 'Active']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
