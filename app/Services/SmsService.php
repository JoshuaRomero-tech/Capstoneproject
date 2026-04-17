<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(?string $to, string $message, array $context = []): bool
    {
        $normalizedNumber = $this->normalizePhoneNumber($to);
        $message = trim($message);

        if (!$normalizedNumber || $message === '') {
            Log::warning('SMS skipped due to invalid recipient or empty message.', $context + [
                'to' => $to,
            ]);

            return false;
        }

        if (!config('services.sms.enabled', false)) {
            Log::info('SMS notifications are disabled.', $context + [
                'to' => $normalizedNumber,
            ]);

            return false;
        }

        $driver = config('services.sms.driver', 'log');

        return match ($driver) {
            'semaphore' => $this->sendViaSemaphore($normalizedNumber, $message, $context),
            'log' => $this->sendViaLog($normalizedNumber, $message, $context),
            default => $this->sendViaLog($normalizedNumber, $message, $context + ['driver' => $driver]),
        };
    }

    private function sendViaLog(string $to, string $message, array $context = []): bool
    {
        Log::info('SMS sent via log driver.', $context + [
            'to' => $to,
            'message' => $message,
        ]);

        return true;
    }

    private function sendViaSemaphore(string $to, string $message, array $context = []): bool
    {
        $apiKey = config('services.sms.semaphore.api_key');
        $senderName = config('services.sms.semaphore.sender_name');
        $endpoint = config('services.sms.semaphore.endpoint', 'https://api.semaphore.co/api/v4/messages');

        if (!$apiKey) {
            Log::warning('SMS skipped because SEMAPHORE_API_KEY is missing.', $context + [
                'to' => $to,
            ]);

            return false;
        }

        $payload = [
            'apikey' => $apiKey,
            'number' => $to,
            'message' => $message,
        ];

        if (!empty($senderName)) {
            $payload['sendername'] = $senderName;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post($endpoint, $payload);

            if (!$response->successful()) {
                Log::error('SMS sending failed at provider.', $context + [
                    'to' => $to,
                    'http_status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return false;
            }

            Log::info('SMS sent via Semaphore.', $context + [
                'to' => $to,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('SMS provider request threw an exception.', $context + [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function normalizePhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if (!$digits) {
            return null;
        }

        if (str_starts_with($digits, '0063')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            $digits = '63' . substr($digits, 1);
        }

        if (str_starts_with($digits, '9') && strlen($digits) === 10) {
            $digits = '63' . $digits;
        }

        if (!str_starts_with($digits, '63')) {
            return null;
        }

        if (strlen($digits) < 12 || strlen($digits) > 13) {
            return null;
        }

        return '+' . $digits;
    }
}
