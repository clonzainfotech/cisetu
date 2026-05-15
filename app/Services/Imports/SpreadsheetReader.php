<?php

namespace App\Services\Imports;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;

class SpreadsheetReader
{
    /**
     * @return list<list<string|null>>
     */
    public function read(string $path, ?string $originalName = null): array
    {
        $extension = strtolower(pathinfo($originalName ?? $path, PATHINFO_EXTENSION));

        if (in_array($extension, ['xlsx', 'xls', 'ods'], true)) {
            return $this->readSpreadsheet($path);
        }

        if (in_array($extension, ['csv', 'txt'], true)) {
            return $this->readCsv($path);
        }

        throw new RuntimeException('Unsupported file type. Please upload CSV or Excel (.xlsx, .xls).');
    }

    /**
     * @return list<list<string|null>>
     */
    private function readSpreadsheet(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getSheet(0);

        return $this->worksheetToRows($sheet);
    }

    /**
     * @return list<list<string|null>>
     */
    private function readCsv(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException('Could not read the uploaded file.');
        }

        while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            $rows[] = array_map(
                fn ($cell) => $cell === null || $cell === '' ? null : trim((string) $cell),
                $data,
            );
        }

        fclose($handle);

        return $rows;
    }

    /**
     * @return list<list<string|null>>
     */
    private function worksheetToRows(Worksheet $sheet): array
    {
        $rows = [];
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        for ($row = 1; $row <= $highestRow; $row++) {
            $cells = [];
            $hasValue = false;

            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $value = $sheet->getCell([$col, $row])->getFormattedValue();
                $value = is_string($value) ? trim($value) : (string) $value;
                $cells[] = $value === '' ? null : $value;

                if ($value !== '') {
                    $hasValue = true;
                }
            }

            if ($hasValue) {
                $rows[] = $cells;
            }
        }

        return $rows;
    }
}
