<?php

namespace App\Services\Imports\Parsers;

class HomeSpreadsheetParser
{
    /** @var list<string> */
    private const HEADER_KEYWORDS = [
        'property_no' => ['property', 'prop', 'પ્રોપટી', 'પ્રોપર્ટી', 'મિલ્કત', 'નંબર'],
        'house_no' => ['house', 'મકાન', 'ઘર'],
        'owner' => ['owner', 'માલિક', 'માલિકનું'],
        'occupant' => ['occupant', 'કબજા', 'કબજેદાર', 'ભોગવटાદાર'],
        'address' => ['address', 'સરનામું', 'એડ્રેસ'],
        'total' => ['total', 'tax', 'કુલ', 'ટેક્સ', 'રકમ'],
    ];

    /**
     * @param  list<list<string|null>>  $rows
     * @param  array<string, int>|null  $forcedColumns
     * @return list<array{property_no: string, house_no: string, owner: string, occupant: string, address: string, total: float}>
     */
    public function parse(array $rows, ?array $forcedColumns = null, int $forcedHeaderRow = -1): array
    {
        if ($rows === []) {
            return [];
        }

        $headerRow = $forcedHeaderRow;
        $columns = $forcedColumns ?? [];

        if ($columns === []) {
            $detected = $this->detectHeader($rows);
            $headerRow = $detected['header_row'];
            $columns = $detected['columns'];
        }

        if ($columns === [] || ! isset($columns['property_no'], $columns['total'])) {
            return [];
        }

        $startRow = $headerRow >= 0 ? $headerRow + 1 : 0;
        $records = [];
        $lastIndex = null;
        $pendingAddress = null;
        $previousWasProperty = false;

        for ($i = $startRow; $i < count($rows); $i++) {
            $row = $this->normalizeRow($rows[$i]);

            if ($this->isLocationLabelRow($row, $columns)) {
                $label = $this->extractLocationLabel($row, $columns);

                if ($previousWasProperty && $lastIndex !== null && ! $this->isAreaSectionLabel($label)) {
                    $records[$lastIndex]['address'] = $this->mergeAddress(
                        $records[$lastIndex]['address'],
                        $label,
                    );
                } else {
                    $pendingAddress = $label;
                }

                $previousWasProperty = false;

                continue;
            }

            $propertyNo = $this->normalizePropertyNo($this->cell($row, $columns, 'property_no'));

            if ($propertyNo === null) {
                $previousWasProperty = false;

                continue;
            }

            $owner = $this->cell($row, $columns, 'owner') ?? '';
            $occupant = $this->cell($row, $columns, 'occupant') ?? $owner;
            $houseNo = $this->cell($row, $columns, 'house_no') ?? $propertyNo;
            $address = $this->cell($row, $columns, 'address');
            $total = $this->parseAmount($this->cell($row, $columns, 'total'));

            if ($total === null) {
                $previousWasProperty = false;

                continue;
            }

            if ($address === null || $address === '-') {
                $address = $pendingAddress ?? '-';
            }

            $records[] = [
                'property_no' => $propertyNo,
                'house_no' => $houseNo !== '' ? $houseNo : $propertyNo,
                'owner' => $owner !== '' ? $owner : 'Unknown',
                'occupant' => $occupant !== '' ? $occupant : ($owner !== '' ? $owner : 'પોતે'),
                'address' => $address !== '' ? $address : '-',
                'total' => $total,
            ];

            $lastIndex = count($records) - 1;
            $previousWasProperty = true;
        }

        return $records;
    }

    /**
     * @param  list<list<string|null>>  $rows
     * @return array{header_row: int, columns: array<string, int>}
     */
    public function detectHeader(array $rows): array
    {
        $bestRow = -1;
        $bestColumns = [];
        $bestScore = 0;

        foreach ($rows as $index => $row) {
            $filled = array_values(array_filter($row, fn ($cell) => $cell !== null && trim((string) $cell) !== ''));

            if (count($filled) === 5) {
                $normalized = array_map(fn ($cell) => $this->normalizeHeader((string) ($cell ?? '')), array_slice($row, 0, 5));
                $positional = $this->mapColumnsFromHeaders($normalized);

                if (isset($positional['property_no'], $positional['total'])) {
                    return ['header_row' => $index, 'columns' => $positional];
                }

                return [
                    'header_row' => $index,
                    'columns' => [
                        'property_no' => 0,
                        'house_no' => 1,
                        'owner' => 2,
                        'occupant' => 3,
                        'total' => 4,
                    ],
                ];
            }

            $normalized = array_map(fn ($cell) => $this->normalizeHeader((string) ($cell ?? '')), $row);
            $columns = $this->mapColumnsFromHeaders($normalized);
            $score = count($columns);

            if ($score > $bestScore && isset($columns['property_no'])) {
                $bestScore = $score;
                $bestRow = $index;
                $bestColumns = $columns;
            }
        }

        return ['header_row' => $bestRow, 'columns' => $bestColumns];
    }

    /**
     * @param  list<string>  $normalizedHeaders
     * @return array<string, int>
     */
    private function mapColumnsFromHeaders(array $normalizedHeaders): array
    {
        $columns = [];

        foreach ($normalizedHeaders as $colIndex => $header) {
            if ($header === '') {
                continue;
            }

            foreach (self::HEADER_KEYWORDS as $field => $keywords) {
                if (isset($columns[$field])) {
                    continue;
                }

                foreach ($keywords as $keyword) {
                    $needle = $this->normalizeHeader($keyword);

                    if ($needle !== '' && (str_contains($header, $needle) || str_contains($needle, $header))) {
                        $columns[$field] = $colIndex;

                        break;
                    }
                }
            }
        }

        return $columns;
    }

    /**
     * TGP exports use standalone rows (area / street / society name) without property no or tax.
     *
     * @param  list<string|null>  $row
     * @param  array<string, int>  $columns
     */
    private function isLocationLabelRow(array $row, array $columns): bool
    {
        if ($this->normalizePropertyNo($this->cell($row, $columns, 'property_no')) !== null) {
            return false;
        }

        if ($this->parseAmount($this->cell($row, $columns, 'total')) !== null) {
            return false;
        }

        if ($this->cell($row, $columns, 'owner') !== null || $this->cell($row, $columns, 'occupant') !== null) {
            return false;
        }

        return $this->extractLocationLabel($row, $columns) !== '';
    }

    /**
     * @param  list<string|null>  $row
     * @param  array<string, int>  $columns
     */
    private function extractLocationLabel(array $row, array $columns): string
    {
        $parts = [];

        foreach (['property_no', 'house_no', 'address'] as $field) {
            $value = $this->cell($row, $columns, $field);

            if ($value !== null && $value !== '-') {
                $parts[] = $value;
            }
        }

        if ($parts === []) {
            foreach ($row as $cell) {
                if ($cell !== null && trim((string) $cell) !== '' && trim((string) $cell) !== '-') {
                    $parts[] = trim((string) $cell);
                }
            }
        }

        return trim(implode(', ', array_unique($parts)));
    }

    private function isAreaSectionLabel(string $label): bool
    {
        $normalized = $this->normalizeHeader($label);

        $markers = [
            'પાસે', 'રોડ', 'સોસાયટી', 'વાટીકા', 'નગર', 'ચૌક', 'વિસ્તાર', 'ફલાટ',
            'road', 'street', 'society', 'colony', 'nagar', 'area', 'near', 'opp',
        ];

        foreach ($markers as $marker) {
            if (str_contains($normalized, $this->normalizeHeader($marker))) {
                return true;
            }
        }

        return str_contains($label, ',');
    }

    private function mergeAddress(string $existing, string $extra): string
    {
        $extra = trim($extra);

        if ($extra === '') {
            return $existing;
        }

        if ($existing === '' || $existing === '-') {
            return $extra;
        }

        if (str_contains($existing, $extra)) {
            return $existing;
        }

        return trim($existing.' '.$extra);
    }

    /**
     * @param  list<string|null>  $row
     * @param  array<string, int>  $columns
     */
    private function cell(array $row, array $columns, string $field): ?string
    {
        if (! isset($columns[$field])) {
            return null;
        }

        $value = $row[$columns[$field]] ?? null;

        if ($value === null) {
            return null;
        }

        $trimmed = trim((string) $value);

        return $trimmed === '' || $trimmed === '-' ? null : $trimmed;
    }

    /**
     * @param  list<string|null>  $row
     * @return list<string|null>
     */
    private function normalizeRow(array $row): array
    {
        return array_map(function ($cell) {
            if ($cell === null) {
                return null;
            }

            $value = trim((string) $cell);

            return $value === '' ? null : $value;
        }, $row);
    }

    private function normalizeHeader(string $value): string
    {
        $value = mb_strtolower(trim($value));

        return preg_replace('/\s+/u', '', $value) ?? $value;
    }

    private function normalizePropertyNo(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '' || $value === '-') {
            return null;
        }

        if (preg_match('/^\d+\.0+$/', $value)) {
            return (string) (int) $value;
        }

        if (is_numeric($value) && ! str_contains($value, '/')) {
            $numeric = (float) $value;

            if ($numeric == (int) $numeric) {
                return (string) (int) $numeric;
            }
        }

        if (preg_match('/^\d+$/', $value)) {
            return $value;
        }

        if (preg_match('/^[\d\s\-\/]+$/', $value)) {
            return preg_replace('/\s+/', '', $value) ?: null;
        }

        return null;
    }

    private function parseAmount(?string $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $clean = preg_replace('/[^\d.\-]/', '', str_replace(',', '', $value));

        if ($clean === null || $clean === '' || ! is_numeric($clean)) {
            return null;
        }

        return round((float) $clean, 2);
    }
}
