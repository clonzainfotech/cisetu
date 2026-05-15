<?php

namespace App\Services\Imports\Parsers;

class ShopSpreadsheetParser
{
    public function __construct(
        private readonly SpreadsheetAmountParser $amountParser = new SpreadsheetAmountParser,
    ) {}

    /** @var list<string> */
    private const HEADER_KEYWORDS = [
        'reg_no' => ['reg', 'registration', 'નોંધણી', 'રજિસ્ટ્રેશન', 'દુકાન નં', 'shop no'],
        'name' => ['name', 'shop', 'દુકાન', 'નામ', 'વ્યવસાય'],
        'total' => ['total', 'tax', 'કુલ', 'ટેક્સ', 'રકમ'],
    ];

    /**
     * @param  list<list<string|null>>  $rows
     * @param  array<string, int>|null  $forcedColumns
     * @return list<array{reg_no: string, name: string, total: float}>
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

        if ($columns === [] || ! isset($columns['reg_no'], $columns['name'], $columns['total'])) {
            return [];
        }

        $startRow = $headerRow >= 0 ? $headerRow + 1 : 0;
        $records = [];

        for ($i = $startRow; $i < count($rows); $i++) {
            $row = $this->normalizeRow($rows[$i]);
            $regNo = $this->normalizeId($this->cell($row, $columns, 'reg_no'));
            $name = $this->cell($row, $columns, 'name');
            $total = $this->parseAmount($this->cell($row, $columns, 'total'));

            if ($regNo === null || $name === null || $total === null) {
                continue;
            }

            $records[] = [
                'reg_no' => $regNo,
                'name' => $name,
                'total' => $total,
            ];
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
            $normalized = array_map(fn ($cell) => $this->normalizeHeader((string) ($cell ?? '')), $row);
            $columns = [];
            $score = 0;

            foreach ($normalized as $colIndex => $header) {
                if ($header === '') {
                    continue;
                }

                foreach (self::HEADER_KEYWORDS as $field => $keywords) {
                    if (isset($columns[$field])) {
                        continue;
                    }

                    foreach ($keywords as $keyword) {
                        if (str_contains($header, $this->normalizeHeader($keyword))) {
                            $columns[$field] = $colIndex;
                            $score++;

                            break;
                        }
                    }
                }
            }

            if ($score > $bestScore && isset($columns['reg_no'], $columns['name'])) {
                $bestScore = $score;
                $bestRow = $index;
                $bestColumns = $columns;
            }
        }

        return ['header_row' => $bestRow, 'columns' => $bestColumns];
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

    private function normalizeId(?string $value): ?string
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

        return $value;
    }

    private function parseAmount(?string $value): ?float
    {
        return $this->amountParser->parse($value);
    }
}
