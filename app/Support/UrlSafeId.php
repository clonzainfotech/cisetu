<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use InvalidArgumentException;

class UrlSafeId
{
    public static function encrypt(int $id): string
    {
        $encrypted = Crypt::encryptString((string) $id);

        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }

    public static function decryptToInt(string $token): int
    {
        $decoded = base64_decode(strtr($token, '-_', '+/'), true);

        if ($decoded === false) {
            throw new InvalidArgumentException('Invalid token.');
        }

        try {
            $plain = Crypt::decryptString($decoded);
        } catch (DecryptException $e) {
            throw new InvalidArgumentException('Invalid token.', 0, $e);
        }

        if (! ctype_digit($plain)) {
            throw new InvalidArgumentException('Invalid token.');
        }

        return (int) $plain;
    }
}
