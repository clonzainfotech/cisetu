<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZeptoMailService
{
    protected string $apiKey;

    protected string $templateKey;

    protected string $fromAddress;

    protected string $fromName;

    protected string $toAddress;

    protected string $toName;

    public function __construct()
    {
        $this->apiKey = config('services.zeptomail.api_key');
        $this->templateKey = config('services.zeptomail.template_key');
        $this->fromAddress = config('services.zeptomail.from_address');
        $this->fromName = config('services.zeptomail.from_name');
        $this->toAddress = config('services.zeptomail.to_address');
        $this->toName = config('services.zeptomail.to_name');
    }

    /**
     * Send an email using a ZeptoMail template.
     */
    public function sendTemplatedEmail(array $mergeInfo, ?string $templateKey = null): bool
    {
        $authorization = str_starts_with($this->apiKey, 'Zoho-enczapikey')
            ? $this->apiKey
            : 'Zoho-enczapikey '.$this->apiKey;

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => $authorization,
            'cache-control' => 'no-cache',
            'content-type' => 'application/json',
        ])->post('https://api.zeptomail.in/v1.1/email/template', [
            'template_key' => $templateKey ?? $this->templateKey,
            'from' => [
                'address' => $this->fromAddress,
                'name' => $this->fromName,
            ],
            'to' => [
                [
                    'email_address' => [
                        'address' => $this->toAddress,
                        'name' => $this->toName,
                    ],
                ],
            ],
            'merge_info' => $mergeInfo,
        ]);

        if ($response->failed()) {
            Log::error('ZeptoMail Send Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'merge_info' => $mergeInfo,
            ]);

            return false;
        }

        return true;
    }

    /**
     * @deprecated Use sendTemplatedEmail instead.
     */
    public function sendRenewalRequest(array $mergeInfo): bool
    {
        return $this->sendTemplatedEmail($mergeInfo);
    }
}
