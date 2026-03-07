<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Imports\HouseholdImport;
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $importer = new HouseholdImport();
        $importer->import($request->file('file')->getPathname());

        $message = "{$importer->getImportedCount()} household(s) imported successfully.";
        if ($importer->getSkippedCount() > 0) {
            $message .= " {$importer->getSkippedCount()} row(s) skipped.";
        }

        $redirect = redirect()->route('households.index');

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
            'Content-Disposition' => 'attachment; filename="households_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['household_no', 'address']);
            fputcsv($file, ['HH-001', '123 Main St, Brgy. Sample']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
