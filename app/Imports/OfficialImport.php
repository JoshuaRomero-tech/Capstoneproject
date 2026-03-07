<?php

namespace App\Imports;

use App\Models\Official;
use App\Models\Resident;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Carbon;

class OfficialImport
{
    protected array $errors = [];
    protected int $imported = 0;
    protected int $skipped = 0;

    protected array $columnMap = [
        'resident_name'  => 'resident_name',
        'resident name'  => 'resident_name',
        'name'           => 'resident_name',
        'full_name'      => 'resident_name',
        'full name'      => 'resident_name',
        'resident_id'    => 'resident_id',
        'resident id'    => 'resident_id',
        'position'       => 'position',
        'committee'      => 'committee',
        'term_start'     => 'term_start',
        'term start'     => 'term_start',
        'start_date'     => 'term_start',
        'start date'     => 'term_start',
        'term_end'       => 'term_end',
        'term end'       => 'term_end',
        'end_date'       => 'term_end',
        'end date'       => 'term_end',
        'status'         => 'status',
    ];

    public function import(string $filePath): self
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (count($rows) < 2) {
            $this->errors[] = 'The file must have a header row and at least one data row.';
            return $this;
        }

        $headers = array_map(fn($h) => strtolower(trim((string) $h)), $rows[0]);
        $fieldMap = [];
        foreach ($headers as $index => $header) {
            if (isset($this->columnMap[$header])) {
                $fieldMap[$index] = $this->columnMap[$header];
            }
        }

        $mappedFields = array_values($fieldMap);

        // Must have either resident_name or resident_id
        $hasResidentRef = in_array('resident_name', $mappedFields) || in_array('resident_id', $mappedFields);
        if (!$hasResidentRef) {
            $this->errors[] = 'Missing required column: resident_name (or name, full_name).';
        }
        foreach (['position', 'term_start', 'term_end'] as $required) {
            if (!in_array($required, $mappedFields)) {
                $this->errors[] = "Missing required column: {$required}";
            }
        }
        if (!empty($this->errors)) {
            return $this;
        }

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $rowNum = $i + 1;

            if (empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) {
                continue;
            }

            $data = [];
            foreach ($fieldMap as $colIndex => $field) {
                $data[$field] = trim((string) ($row[$colIndex] ?? ''));
            }

            // Resolve resident_id
            if (!empty($data['resident_id']) && is_numeric($data['resident_id'])) {
                $resident = Resident::find((int) $data['resident_id']);
                if (!$resident) {
                    $this->errors[] = "Row {$rowNum}: Resident ID {$data['resident_id']} not found.";
                    $this->skipped++;
                    continue;
                }
                $residentId = $resident->id;
            } elseif (!empty($data['resident_name'])) {
                $residentId = $this->findResidentByName($data['resident_name']);
                if ($residentId === null) {
                    $this->errors[] = "Row {$rowNum}: Resident '{$data['resident_name']}' not found. Make sure the resident exists first.";
                    $this->skipped++;
                    continue;
                }
            } else {
                $this->errors[] = "Row {$rowNum}: No resident name or ID provided.";
                $this->skipped++;
                continue;
            }

            // Check if resident already has an official record
            if (Official::where('resident_id', $residentId)->exists()) {
                $this->errors[] = "Row {$rowNum}: This resident already has an official record.";
                $this->skipped++;
                continue;
            }

            if (empty($data['position'])) {
                $this->errors[] = "Row {$rowNum}: Position is required.";
                $this->skipped++;
                continue;
            }

            $termStart = $this->parseDate($data['term_start'] ?? '', $rowNum, 'term_start');
            $termEnd = $this->parseDate($data['term_end'] ?? '', $rowNum, 'term_end');
            if ($termStart === null || $termEnd === null) {
                $this->skipped++;
                continue;
            }

            $status = 'Active';
            if (!empty($data['status'])) {
                $status = ucfirst(strtolower($data['status']));
                if (!in_array($status, ['Active', 'Inactive'])) {
                    $status = 'Active';
                }
            }

            try {
                Official::create([
                    'resident_id' => $residentId,
                    'position'    => $data['position'],
                    'committee'   => $data['committee'] ?? null,
                    'term_start'  => $termStart,
                    'term_end'    => $termEnd,
                    'status'      => $status,
                ]);
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNum}: " . $e->getMessage();
                $this->skipped++;
            }
        }

        return $this;
    }

    protected function findResidentByName(string $name): ?int
    {
        $parts = preg_split('/[\s,]+/', trim($name));
        $parts = array_filter($parts);

        if (count($parts) === 0) {
            return null;
        }

        $query = Resident::where('status', 'Active');

        if (count($parts) === 1) {
            $query->where(function ($q) use ($parts) {
                $q->where('first_name', 'like', "%{$parts[0]}%")
                  ->orWhere('last_name', 'like', "%{$parts[0]}%");
            });
        } else {
            $firstName = $parts[0];
            $lastName = end($parts);
            $query->where(function ($q) use ($firstName, $lastName) {
                $q->where(function ($q2) use ($firstName, $lastName) {
                    $q2->where('first_name', 'like', "%{$firstName}%")
                       ->where('last_name', 'like', "%{$lastName}%");
                })->orWhere(function ($q2) use ($firstName, $lastName) {
                    $q2->where('first_name', 'like', "%{$lastName}%")
                       ->where('last_name', 'like', "%{$firstName}%");
                });
            });
        }

        $resident = $query->first();
        return $resident?->id;
    }

    protected function parseDate(string $value, int $rowNum, string $fieldName): ?string
    {
        if (empty($value)) {
            $this->errors[] = "Row {$rowNum}: {$fieldName} is required.";
            return null;
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->format('Y-m-d');
            } catch (\Exception $e) {
                // fall through
            }
        }

        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'M d, Y', 'F d, Y', 'm-d-Y', 'd-m-Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->errors[] = "Row {$rowNum}: Invalid date format for {$fieldName}: '{$value}'.";
        return null;
    }

    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int { return $this->skipped; }
    public function getErrors(): array { return $this->errors; }
    public function hasErrors(): bool { return !empty($this->errors); }
}
