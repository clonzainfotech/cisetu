<?php

namespace App\Services\Imports;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class OpenAiImportMapper
{
    /**
     * @param  list<list<string|null>>  $rows
     * @param  list<string>  $targetFields
     * @return array{header_row: int, columns: array<string, int>}|null
     */
    public function detectColumns(array $rows, array $targetFields, string $context): ?array
    {
        $apiKey = config('services.openai.api_key');

        if (! $apiKey) {
            return null;
        }

        $sample = array_slice($rows, 0, 25);

        try {
            $response = Http::withToken($apiKey)
                ->timeout((int) config('services.openai.timeout', 90))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('services.openai.model', 'gpt-4o-mini'),
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You map messy Excel/CSV tax ledger rows to a fixed schema. '
                                .'Return JSON only: {"header_row":0-based index or -1 if no header,"columns":{"field":0-based column index}}. '
                                .'Use only these fields: '.implode(', ', $targetFields).'. '
                                .'Ignore title rows. Gujarati and English headers are common. '
                                .'property_no/reg_no is the primary ID column. total is tax amount.',
                        ],
                        [
                            'role' => 'user',
                            'content' => json_encode([
                                'context' => $context,
                                'target_fields' => $targetFields,
                                'rows' => $sample,
                            ], JSON_UNESCAPED_UNICODE),
                        ],
                    ],
                    'temperature' => 0.1,
                ]);

            if (! $response->successful()) {
                Log::warning('OpenAI column mapping failed', ['body' => $response->body()]);

                return null;
            }

            $content = $response->json('choices.0.message.content');

            if (! is_string($content)) {
                return null;
            }

            /** @var array{header_row?: int, columns?: array<string, int>}|null $parsed */
            $parsed = json_decode($content, true);

            if (! is_array($parsed) || ! isset($parsed['columns']) || ! is_array($parsed['columns'])) {
                return null;
            }

            $columns = [];

            foreach ($targetFields as $field) {
                if (array_key_exists($field, $parsed['columns']) && is_int($parsed['columns'][$field])) {
                    $columns[$field] = $parsed['columns'][$field];
                }
            }

            if ($columns === []) {
                return null;
            }

            return [
                'header_row' => (int) ($parsed['header_row'] ?? -1),
                'columns' => $columns,
            ];
        } catch (\Throwable $exception) {
            Log::warning('OpenAI column mapping exception', ['message' => $exception->getMessage()]);

            return null;
        }
    }

    /**
     * @param  list<list<string|null>>  $rows
     * @return list<array<string, string|float>>
     */
    public function normalizeRecords(array $rows, array $targetFields, string $context): array
    {
        $apiKey = config('services.openai.api_key');

        if (! $apiKey) {
            throw new RuntimeException('OpenAI API key is not configured. Set OPENAI_API_KEY in .env or use a standard template file.');
        }

        $chunks = array_chunk($rows, 80);
        $records = [];

        foreach ($chunks as $index => $chunk) {
            $response = Http::withToken($apiKey)
                ->timeout((int) config('services.openai.timeout', 120))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('services.openai.model', 'gpt-4o-mini'),
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Convert spreadsheet rows into clean import records. '
                                .'Return JSON: {"records":[...]}. Each record uses only: '.implode(', ', $targetFields).'. '
                                .'Skip empty rows, header rows, and section titles. '
                                .'Merge multi-line addresses into the address field. '
                                .'Strip .0 from numeric IDs (2137.0 -> 2137). '
                                .'For homes: if occupant missing use owner; if house_no missing use property_no; if address missing use "-". '
                                .'For shops: reg_no, name, total are required.',
                        ],
                        [
                            'role' => 'user',
                            'content' => json_encode([
                                'context' => $context,
                                'chunk' => $index + 1,
                                'target_fields' => $targetFields,
                                'rows' => $chunk,
                            ], JSON_UNESCAPED_UNICODE),
                        ],
                    ],
                    'temperature' => 0.1,
                ]);

            if (! $response->successful()) {
                throw new RuntimeException('AI import failed: '.$response->json('error.message', 'Unknown OpenAI error'));
            }

            $content = $response->json('choices.0.message.content');

            if (! is_string($content)) {
                continue;
            }

            /** @var array{records?: list<array<string, mixed>>}|null $parsed */
            $parsed = json_decode($content, true);

            if (! is_array($parsed['records'] ?? null)) {
                continue;
            }

            foreach ($parsed['records'] as $record) {
                if (is_array($record)) {
                    $records[] = $record;
                }
            }
        }

        return $records;
    }
}
