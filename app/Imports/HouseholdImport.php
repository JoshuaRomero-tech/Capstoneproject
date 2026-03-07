<?php

namespace App\Imports;

use App\Models\Household;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HouseholdImport
{
    protected array $errors = [];
    protected int $imported = 0;
    protected int $skipped = 0;

    protected array $columnMap = [
        'household_no'  => 'household_no',
        'household no'  => 'household_no',
        'household no.' => 'household_no',
        'household_number' => 'household_no',
        'household number' => 'household_no',
        'number'        => 'household_no',
        'no'            => 'household_no',
        'no.'           => 'household_no',
        'address'       => 'address',
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
        foreach (['household_no', 'address'] as $required) {
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

            if (empty($data['household_no']) || empty($data['address'])) {
                $this->errors[] = "Row {$rowNum}: Missing required field (household_no or address).";
                $this->skipped++;
                continue;
            }

            if (Household::where('household_no', $data['household_no'])->exists()) {
                $this->errors[] = "Row {$rowNum}: Household no. '{$data['household_no']}' already exists.";
                $this->skipped++;
                continue;
            }

            try {
                Household::create($data);
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNum}: " . $e->getMessage();
                $this->skipped++;
            }
        }

        return $this;
    }

    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int { return $this->skipped; }
    public function getErrors(): array { return $this->errors; }
    public function hasErrors(): bool { return !empty($this->errors); }
}
