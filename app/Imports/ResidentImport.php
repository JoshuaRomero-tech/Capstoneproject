<?php

namespace App\Imports;

use App\Models\Resident;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Carbon;

class ResidentImport
{
    protected array $errors = [];
    protected int $imported = 0;
    protected int $skipped = 0;

    /**
     * Expected column headers (lowercase, trimmed) mapped to DB fields.
     */
    protected array $columnMap = [
        'first_name'              => 'first_name',
        'first name'              => 'first_name',
        'middle_name'             => 'middle_name',
        'middle name'             => 'middle_name',
        'last_name'               => 'last_name',
        'last name'               => 'last_name',
        'suffix'                  => 'suffix',
        'date_of_birth'           => 'date_of_birth',
        'date of birth'           => 'date_of_birth',
        'birthday'                => 'date_of_birth',
        'birthdate'               => 'date_of_birth',
        'place_of_birth'          => 'place_of_birth',
        'place of birth'          => 'place_of_birth',
        'birthplace'              => 'place_of_birth',
        'gender'                  => 'gender',
        'sex'                     => 'gender',
        'civil_status'            => 'civil_status',
        'civil status'            => 'civil_status',
        'nationality'             => 'nationality',
        'religion'                => 'religion',
        'contact_number'          => 'contact_number',
        'contact number'          => 'contact_number',
        'contact'                 => 'contact_number',
        'phone'                   => 'contact_number',
        'email'                   => 'email',
        'address'                 => 'address',
        'occupation'              => 'occupation',
        'educational_attainment'  => 'educational_attainment',
        'educational attainment'  => 'educational_attainment',
        'education'               => 'educational_attainment',
        'voter_status'            => 'voter_status',
        'voter status'            => 'voter_status',
        'voter'                   => 'voter_status',
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

        // Map header row to DB fields
        $headers = array_map(fn($h) => strtolower(trim((string) $h)), $rows[0]);
        $fieldMap = [];
        foreach ($headers as $index => $header) {
            if (isset($this->columnMap[$header])) {
                $fieldMap[$index] = $this->columnMap[$header];
            }
        }

        // Validate required columns exist
        $mappedFields = array_values($fieldMap);
        foreach (['first_name', 'last_name', 'date_of_birth', 'gender', 'address'] as $required) {
            if (!in_array($required, $mappedFields)) {
                $this->errors[] = "Missing required column: {$required}";
            }
        }
        if (!empty($this->errors)) {
            return $this;
        }

        // Process data rows
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $rowNum = $i + 1;

            // Skip completely empty rows
            if (empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) {
                continue;
            }

            $data = [];
            foreach ($fieldMap as $colIndex => $field) {
                $data[$field] = trim((string) ($row[$colIndex] ?? ''));
            }

            // Validate required fields
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['gender']) || empty($data['address'])) {
                $this->errors[] = "Row {$rowNum}: Missing required field (first_name, last_name, gender, or address).";
                $this->skipped++;
                continue;
            }

            // Parse date_of_birth
            $data['date_of_birth'] = $this->parseDate($data['date_of_birth'] ?? '', $rowNum);
            if ($data['date_of_birth'] === null) {
                $this->skipped++;
                continue;
            }

            // Normalize gender
            $gender = ucfirst(strtolower($data['gender']));
            if (!in_array($gender, ['Male', 'Female'])) {
                $this->errors[] = "Row {$rowNum}: Invalid gender '{$data['gender']}'. Must be Male or Female.";
                $this->skipped++;
                continue;
            }
            $data['gender'] = $gender;

            // Normalize civil_status
            if (!empty($data['civil_status'])) {
                $data['civil_status'] = ucfirst(strtolower($data['civil_status']));
                if (!in_array($data['civil_status'], ['Single', 'Married', 'Widowed', 'Separated', 'Divorced'])) {
                    $data['civil_status'] = 'Single';
                }
            } else {
                $data['civil_status'] = 'Single';
            }

            // Normalize voter_status
            if (!empty($data['voter_status'])) {
                $vs = strtolower($data['voter_status']);
                $data['voter_status'] = in_array($vs, ['registered', 'yes', '1', 'true']) ? 'Registered' : 'Not Registered';
            } else {
                $data['voter_status'] = 'Not Registered';
            }

            // Set defaults
            if (empty($data['nationality'])) {
                $data['nationality'] = 'Filipino';
            }

            // Remove empty optional fields
            $data = array_filter($data, fn($v) => $v !== '');

            try {
                Resident::create($data);
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNum}: " . $e->getMessage();
                $this->skipped++;
            }
        }

        return $this;
    }

    protected function parseDate(string $value, int $rowNum): ?string
    {
        if (empty($value)) {
            $this->errors[] = "Row {$rowNum}: date_of_birth is required.";
            return null;
        }

        // Handle Excel numeric date serial
        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->format('Y-m-d');
            } catch (\Exception $e) {
                // fall through to string parsing
            }
        }

        // Try common date formats
        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'M d, Y', 'F d, Y', 'm-d-Y', 'd-m-Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->errors[] = "Row {$rowNum}: Invalid date format '{$value}'. Use YYYY-MM-DD or MM/DD/YYYY.";
        return null;
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
