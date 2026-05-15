<?php

namespace App\Services\Imports;

use App\Models\Shop;
use App\Models\Village;
use App\Services\Imports\Parsers\ShopSpreadsheetParser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ShopImportService
{
    public function __construct(
        private readonly SpreadsheetReader $reader,
        private readonly ShopSpreadsheetParser $parser,
        private readonly OpenAiImportMapper $aiMapper,
    ) {}

    public function import(Village $village, UploadedFile $file, bool $useAi = true): ImportResult
    {
        $rows = $this->reader->read($file->getRealPath(), $file->getClientOriginalName());
        $method = 'heuristic';
        $records = $this->parser->parse($rows);

        $dataRowEstimate = max(0, count($rows) - 3);

        if ($useAi && config('services.openai.api_key') && ($records === [] || count($records) < ($dataRowEstimate * 0.25))) {
            $mapping = $this->aiMapper->detectColumns(
                $rows,
                ['reg_no', 'name', 'total'],
                'Gujarati/English shop professional tax ledger',
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
                    ['reg_no', 'name', 'total'],
                    'shop professional tax import',
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
                Shop::query()->updateOrCreate(
                    [
                        'village_id' => $village->id,
                        'reg_no' => $record['reg_no'],
                    ],
                    [
                        'name' => $record['name'],
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
     * @return list<array{reg_no: string, name: string, total: float}>
     */
    private function normalizeAiRecords(array $aiRecords): array
    {
        $records = [];

        foreach ($aiRecords as $row) {
            $regNo = trim((string) ($row['reg_no'] ?? ''));
            $name = trim((string) ($row['name'] ?? ''));
            $total = is_numeric($row['total'] ?? null) ? round((float) $row['total'], 2) : null;

            if ($regNo === '' || $name === '' || $total === null) {
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
}
