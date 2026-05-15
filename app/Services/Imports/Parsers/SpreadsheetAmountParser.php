<?php

namespace App\Services\Imports\Parsers;

class SpreadsheetAmountParser
{
    /** @var array<string, string>|null */
    private static ?array $indicDigitMap = null;

    public function parse(?string $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $value = $this->transliterateIndicDigits(trim($value));
        $clean = preg_replace('/[^\d.\-]/', '', str_replace(',', '', $value));

        if ($clean === null || $clean === '' || ! is_numeric($clean)) {
            return null;
        }

        return round((float) $clean, 2);
    }

    private function transliterateIndicDigits(string $value): string
    {
        if (self::$indicDigitMap === null) {
            self::$indicDigitMap = [];

            for ($digit = 0; $digit <= 9; $digit++) {
                self::$indicDigitMap[mb_chr(0x0AE6 + $digit)] = (string) $digit;
                self::$indicDigitMap[mb_chr(0x0966 + $digit)] = (string) $digit;
            }
        }

        return strtr($value, self::$indicDigitMap);
    }
}
