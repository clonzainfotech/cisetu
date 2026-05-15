<?php

namespace App\Services\Imports;

use App\Models\Home;
use App\Models\Village;
use App\Services\Imports\Parsers\HomeSpreadsheetParser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class HomeImportService
{
    public function __construct(
        private readonly SpreadsheetReader $reader,
        private readonly HomeSpreadsheetParser $parser,
        private readonly OpenAiImportMapper $aiMapper,
    ) {}

    public function import(Village $village, UploadedFile $file, bool $useAi = true): ImportResult
    {
        $rows = $this->reader->read($file->getRealPath(), $file->getClientOriginalName());
        $method = 'heuristic';
        $records = $this->parser->parse($rows);

        $dataRowEstimate = max(0, count($rows) - 5);

        if ($useAi && config('services.openai.api_key') && ($records === [] || count($records) < ($dataRowEstimate * 0.25))) {
            $mapping = $this->aiMapper->detectColumns(
                $rows,
                ['property_no', 'house_no', 'owner', 'occupant', 'address', 'total'],
                'Gujarati/English home property tax ledger',
            );

            if ($mapping !== null) {
                $records = $this->parser->parse(
                    $rows,
                    $mapping['columns'],
                    $mapping['header_row'],
                );
                $method = 'ai-columns';
            }

            if ($records === [] || count($records) < ($dataRowEstimate * 0.25)) {
                $aiRecords = $this->aiMapper->normalizeRecords(
                    $rows,
                    ['property_no', 'house_no', 'owner', 'occupant', 'address', 'total'],
                    'home property tax import',
                );
                $records = $this->normalizeAiRecords($aiRecords);
                $method = 'ai-full';
            }
        }

        if ($records === []) {
            return new ImportResult(0, $method, skipped: count($rows));
        }

        $imported = 0;

        DB::transaction(function () use ($village, $records, &$imported): void {
            foreach ($records as $record) {
                Home::query()->updateOrCreate(
                    [
                        'village_id' => $village->id,
                        'property_no' => $record['property_no'],
                    ],
                    [
                        'house_no' => $record['house_no'],
                        'owner' => $record['owner'],
                        'occupant' => $record['occupant'],
                        'address' => $record['address'],
                        'total' => $record['total'],
                    ],
                );
                $imported++;
            }
        });

        return new ImportResult($imported, $method, skipped: max(0, count($rows) - $imported));
    }

    /**
     * @param  list<array<string, mixed>>  $aiRecords
     * @return list<array{property_no: string, house_no: string, owner: string, occupant: string, address: string, total: float}>
     */
    private function normalizeAiRecords(array $aiRecords): array
    {
        $records = [];

        foreach ($aiRecords as $row) {
            $propertyNo = trim((string) ($row['property_no'] ?? ''));

            if ($propertyNo === '') {
                continue;
            }

            $owner = trim((string) ($row['owner'] ?? ''));
            $occupant = trim((string) ($row['occupant'] ?? $owner));
            $total = is_numeric($row['total'] ?? null) ? round((float) $row['total'], 2) : null;

            if ($total === null) {
                continue;
            }

            $records[] = [
                'property_no' => $propertyNo,
                'house_no' => trim((string) ($row['house_no'] ?? $propertyNo)) ?: $propertyNo,
                'owner' => $owner !== '' ? $owner : 'Unknown',
                'occupant' => $occupant !== '' ? $occupant : ($owner !== '' ? $owner : 'પોતે'),
                'address' => trim((string) ($row['address'] ?? '-')) ?: '-',
                'total' => $total,
            ];
        }

        return $records;
    }
}
